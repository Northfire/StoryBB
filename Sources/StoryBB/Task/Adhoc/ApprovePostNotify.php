<?php
/**
 * Notify moderators that a post needs to be approved.
 *
 * @package StoryBB (storybb.org) - A roleplayer's forum software
 * @copyright 2018 StoryBB and individual contributors (see contributors.txt)
 * @license 3-clause BSD (see accompanying LICENSE file)
 *
 * @version 3.0 Alpha 1
 */

namespace StoryBB\Task\Adhoc;

/**
 * Notify moderators that a post needs to be approved.
 */
class ApprovePostNotify extends \StoryBB\Task\Adhoc
{
	/**
	 * This executes the task - loads up the info, puts the email in the queue and inserts any alerts as needed.
	 * @return bool Always returns true
	 */
	public function execute()
	{
		global $smcFunc, $sourcedir, $scripturl, $modSettings, $language;

		$msgOptions = $this->_details['msgOptions'];
		$topicOptions = $this->_details['topicOptions'];
		$posterOptions = $this->_details['posterOptions'];
		$type = $this->_details['type'];

		$members = array();
		$alert_rows = array();

		// We need to know who can approve this post.
		require_once($sourcedir . '/Subs-Members.php');
		$modMembers = membersAllowedTo('approve_posts', $topicOptions['board']);

		$request = $smcFunc['db_query']('', '
			SELECT id_member, email_address, lngfile
			FROM {db_prefix}members
			WHERE id_member IN({array_int:members})',
			array(
				'members' => $modMembers,
			)
		);

		$watched = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			$members[] = $row['id_member'];
			$watched[$row['id_member']] = $row;
		}
		$smcFunc['db_free_result']($request);

		if (empty($members))
			return true;

		require_once($sourcedir . '/Subs-Notify.php');
		$members = array_unique($members);
		$prefs = getNotifyPrefs($members, 'unapproved_post', true);
		foreach ($watched as $member => $data)
		{
			$pref = !empty($prefs[$member]['unapproved_post']) ? $prefs[$member]['unapproved_post'] : 0;

			if ($pref & 0x02)
			{
				// Emails are a bit complicated. We have to do language stuff.
				require_once($sourcedir . '/Subs-Post.php');
				require_once($sourcedir . '/ScheduledTasks.php');
				loadEssentialThemeData();

				$replacements = array(
					'SUBJECT' => $msgOptions['subject'],
					'LINK' => $scripturl . '?topic=' . $topicOptions['id'] . '.new#new',
				);

				$emaildata = loadEmailTemplate('alert_unapproved_post', $replacements, empty($data['lngfile']) || empty($modSettings['userLanguage']) ? $language : $data['lngfile']);
				StoryBB\Helper\Mail::send($data['email_address'], $emaildata['subject'], $emaildata['body'], null, 'm' . $topicOptions['id'], $emaildata['is_html']);
			}

			if ($pref & 0x01)
			{
				$alert_rows[] = array(
					'alert_time' => time(),
					'id_member' => $member,
					'id_member_started' => $posterOptions['id'],
					'member_name' => $posterOptions['name'],
					'content_type' => 'unapproved',
					'content_id' => $topicOptions['id'],
					'content_action' => $type,
					'is_read' => 0,
					'extra' => json_encode(array(
						'topic' => $topicOptions['id'],
						'board' => $topicOptions['board'],
						'content_subject' => $msgOptions['subject'],
						'content_link' => $scripturl . '?topic=' . $topicOptions['id'] . '.new;topicseen#new',
					)),
				);
				updateMemberData($member, array('alerts' => '+'));
			}
		}

		// Insert the alerts if any
		if (!empty($alert_rows))
			$smcFunc['db_insert']('',
				'{db_prefix}user_alerts',
				array('alert_time' => 'int', 'id_member' => 'int', 'id_member_started' => 'int', 'member_name' => 'string',
					'content_type' => 'string', 'content_id' => 'int', 'content_action' => 'string', 'is_read' => 'int', 'extra' => 'string'),
				$alert_rows,
				array()
			);

		return true;
	}
}
