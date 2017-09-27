<?php
/**
 * @package StoryBB (storybb.org) - A roleplayer's forum software
 * @copyright 2017 StoryBB and individual contributors (see contributors.txt)
 * @license 3-clause BSD (see accompanying LICENSE file)
 *
 * @version 3.0 Alpha 1
 */

function character_popup_row($id_character, $char) {
	global $context, $scripturl, $txt, $user_info, $cur_profile, $settings;
	echo '
				<li>
					<div class="character">
						<span class="avatar">
							', !empty($char['avatar']) ? '<img src="' . $char['avatar'] . '" alt="" />' : '<img src="' . $settings['images_url'] . '/default.png" alt="" />', '
						</span>
						<a href="', $scripturl, $char['character_url'], '">', $char['character_name'], '</a>';
	if (!empty($char['is_main']))
	{
		echo '
						(<abbr title="', $txt['main_char_desc'], '">', $txt['main_char'], '</abbr>)';
	}
	if ($id_character != $user_info['id_character'])
		echo '
						<span class="switch">
							<span data-href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=char_switch;char=', $id_character, ';', $context['session_var'], '=', $context['session_id'], '" class="button">', $txt['switch_chars'], '</a>
						</span>';
	if (empty($char['is_main']))
	{
		echo '
						<span class="switch">
							<a class="button" href="', $scripturl, $char['character_url'], ';sa=sheet">', $txt['char_sheet_link'], '</a>
						</span>';
	}

	echo '
					</div>
				</li>';
}

function template_characters_popup()
{
	global $context, $scripturl, $txt, $user_info, $cur_profile, $modSettings;
	echo '
		<div id="posting_as">', sprintf($txt['you_are_posting_as'], $user_info['character_name']), '
		<div id="my_account" class="chars_container">
			<ul>';

	foreach ($cur_profile['characters'] as $id_character => $char)
	{
		if (!empty($char['is_main']))
			character_popup_row($id_character, $char);
	}
	echo '
			</ul>
		</div>
		<div id="my_characters">', $txt['my_characters'], '</div>
		<div id="chars_container" class="chars_container">
			<ul>';
	foreach ($cur_profile['characters'] as $id_character => $char)
	{
		if ($char['retired'])
			continue;
		if (empty($char['is_main']))
			character_popup_row($id_character, $char);
	}
	echo '
			</ul>
		</div>
		<br />
		<div class="clear centertext">
			<a class="button" href="', $scripturl, '?action=profile;u=', $user_info['id'], ';area=char_create">', $txt['char_create'], '</a>
		</div>
		<script>
		$(".chars_container .switch span.button").on("click", function() {
			$.ajax({
				url: $(this).data("href")
			}).done(function( data ) {
				console.log("done");
				location.reload();
			});
		});
		</script>';
}

function template_character_profile()
{
	global $context, $txt, $user_profile, $scripturl, $user_info, $modSettings, $settings;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', !empty($context['character']['avatar']) ? '<img class="icon" style="max-width: 25px; max-height: 25px;" src="' . $context['character']['avatar'] . '" alt="">' : '', '
			', $context['character']['character_name'], '
			', $context['character']['retired'] ? ' - ' . $txt['char_retired'] : '', '
		</h3>
	</div>

	<div class="errorbox" style="display:none" id="profile_error"></div>
	<div id="profileview" class="roundframe flow_auto">
		<div id="basicinfo">';

	if (!empty($context['character']['avatar']))
		echo '
			<img class="avatar" src="', $context['character']['avatar'], '" alt=""><br /><br />';
	else
		echo '
			<img class="avatar" src="', $settings['images_url'], '/default.png" alt=""><br /><br />';

	if ($context['user']['is_owner'] && $user_info['id_character'] != $context['character']['id_character'])
	{
		echo '
			<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=char_switch_redir;char=', $context['character']['id_character'], ';', $context['session_var'], '=', $context['session_id'], '" class="button">', $txt['switch_to_char'], '</a><br /><br />';
	}
	if (!$context['character']['is_main'] && !empty($context['character']['char_sheet']))
	{
		echo '
			<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=sheet" class="button">', $txt['char_sheet'], '</a><br /><br />';
	}
	if ($context['character']['editable'])
	{
		echo '
			<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=edit" class="button">', $txt['edit_char'], '</a><br /><br />';
	}
	if ($context['character']['editable'] && $context['character']['retire_eligible'])
	{
		echo '
			<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=retire;', $context['session_var'], '=', $context['session_id'], '" class="button">', $context['character']['retired'] ? $txt['char_unretire_char'] : $txt['char_retire_char'], '</a><br /><br />';
	}
	if ($context['character']['editable'] && $context['character']['posts'] == 0 && !$context['character']['is_main'])
	{
		echo '
			<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=delete;', $context['session_var'], '=', $context['session_id'], '" class="button" onclick="return confirm(', JavaScriptEscape($txt['are_you_sure_delete_char']), ');">', $txt['delete_char'], '</a><br /><br />';
	}
	if (!$context['character']['is_main'] && allowedTo('admin_forum'))
	{
		echo '
			<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=move_acct;', $context['session_var'], '=', $context['session_id'], '" class="button">', $txt['move_char_action'], '</a><br /><br />';
	}
	$days_registered = (int) ((time() - $user_profile[$context['id_member']]['date_registered']) / (3600 * 24));
	$posts_per_day = $days_registered > 1 ? comma_format($context['character']['posts'] / $days_registered, 2) : '';
	echo '
		</div>
		<div id="detailedinfo">
			<dl>
				<dt>', $txt['char_name'], '</dt>
				<dd>', $context['character']['character_name'], '</dd>
				<dt>', $txt['profile_posts'], ':</dt>
				<dd>', comma_format($context['character']['posts']), $days_registered > 1 ? ' (' . $posts_per_day . ' per day)' : '', '</dd>';
	if (!empty($context['character']['char_title']))
	{
		echo '
				<dt>', $txt['custom_title'], ':</dt>
				<dd>', parse_bbc($context['character']['char_title'], false), '</dd>';
	}
	echo '
				<dt>', $txt['age'], ':</dt>
				<dd>', !empty($context['character']['age']) ? $context['character']['age'] : 'N/A', '</dd>
			</dl>';

	if (!empty($context['character']['signature'])) {
		echo '
			<div class="char_signature">', parse_bbc($context['character']['signature'], true, 'sig_char' . $context['character']['id_character']), '</div>
			<dl></dl>';
	}

	echo '
			<dl class="noborder">
				<dt>', $txt['date_created'], '</dt>
				<dd>', timeformat($context['character']['date_created']), '</dd>
				<dt>', $txt['lastLoggedIn'], ': </dt>
				<dd>', !empty($context['character']['last_active']) ? timeformat($context['character']['last_active']) : '<em>' . $txt['never'] . '</em>', '</dd>';

	if ($context['character']['editable'])
		echo '
				<dt>', $txt['current_theme'], ':</dt>
				<dd>', $context['character']['theme_name'], ' <a class="button" href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=theme">', $txt['change_theme'], '</a></dd>';

	echo '
			</dl>
		</div>
	</div>';
}

function template_edit_char()
{
	global $context, $txt, $scripturl, $modSettings;

	if ($context['char_updated'])
	{
		echo '
		<div class="infobox">
			', sprintf($txt[$context['user']['is_owner'] ? 'character_updated_you' : 'character_updated_else'], $context['character']['character_name']), '
		</div>';
	}

	echo '
	<div class="cat_bar">
		<h3 class="catbg">', $txt['edit_char'], '</h3>
	</div>';

	echo '
	<div id="profileview" class="roundframe flow_auto">
		<div class="errorbox" id="profile_error"', empty($context['form_errors']) ? ' style="display:none"' : '', '>
			<span>', $txt['char_editing_error'], '</span>
			<ul id="list_errors">';
	foreach ($context['form_errors'] as $err)
		echo '
				<li>', $err, '</li>';
	echo '
			</ul>
		</div>
		<div id="basicinfo">';

	echo '
		</div>
		<div id="detailedinfo">
			<form id="creator" action="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=edit" method="post" accept-charset="UTF-8">';

	if ($context['character']['groups_editable'])
	{
		echo '
				<dl>
					<dt>
						<strong>', $txt['primary_membergroup'], ': </strong><br>
					</dt>
					<dd>
						<select name="id_group">';

		// Fill the select box with all primary member groups that can be assigned to a member.
		foreach ($context['member_groups'] as $member_group)
			if (!empty($member_group['can_be_primary']))
				echo '
							<option value="', $member_group['id'], '"', $member_group['is_primary'] ? ' selected' : '', '>
								', $member_group['name'], '
							</option>';
		echo '
						</select>
					</dd>
					<dt>
						<strong>', $txt['additional_membergroups'], ':</strong>
					</dt>
					<dd>
						<span id="additional_groupsList">
							<input type="hidden" name="additional_groups[]" value="0">';

		// For each membergroup show a checkbox so members can be assigned to more than one group.
		foreach ($context['member_groups'] as $member_group)
			if ($member_group['can_be_additional'])
				echo '
							<label for="additional_groups-', $member_group['id'], '"><input type="checkbox" name="additional_groups[]" value="', $member_group['id'], '" id="additional_groups-', $member_group['id'], '"', $member_group['is_additional'] ? ' checked' : '', ' class="input_check"> ', $member_group['name'], '</label><br>';
		echo '
						</span>
						<a href="javascript:void(0);" onclick="document.getElementById(\'additional_groupsList\').style.display = \'block\'; document.getElementById(\'additional_groupsLink\').style.display = \'none\'; return false;" id="additional_groupsLink" style="display: none;" class="toggle_down">', $txt['additional_membergroups_show'], '</a>
						<script>
							document.getElementById("additional_groupsList").style.display = "none";
							document.getElementById("additional_groupsLink").style.display = "";
						</script>
					</dd>
				</dl>';
	}

	echo '
				<dl>
					<dt>', $txt['char_name'], '</dt>
					<dd>
						<input type="text" name="char_name" id="char_name" size="50" value="', $context['character']['character_name'], '" maxlength="50" class="input_text">
					</dd>';
	if ($context['character']['title_editable'])
	{
		echo '
					<dt>', $txt['custom_title'], '</dt>
					<dd>
						<input type="text" name="char_title" id="char_title" size="50" value="', $context['character']['char_title_raw'], '" maxlength="255" class="input_text">
					</dd>';
	}
	echo '
					<dt>
						', $txt['avatar_link'];
	if (!empty($modSettings['avatar_max_width_external']))
	{
		echo '
						<div class="smalltext">', sprintf(
							$txt['max_avatar_size'],
							$modSettings['avatar_max_width_external'],
							$modSettings['avatar_max_height_external']
						), '</div>';
	}

	echo '
					</dt>
					<dd>
						<input type="text" name="avatar" id="avatar" size="50" value="', !empty($context['character']['avatar']) ? $context['character']['avatar'] : '', '" maxlength="255" class="input_type">
					</dd>
					<dt>', $txt['avatar_preview'], '</dt>
					<dd id="avatar_preview"></dd>
					<dt>', $txt['age'], ':</dt>
					<dd>
						<input type="text" name="age" id="age" size="50" value="', !empty($context['character']['age']) ? $context['character']['age'] : '', '" maxlength="50" class="input_text">
					</dd>
				</dl>
				<div class="char_signature"></div>
				<dl class="noborder" id="current_sig">
					<dt>', $txt['current_signature'], ':</dt>
					<dd></dd>
				</dl>
				<div class="signature" id="current_sig_parsed">
					', !empty($context['character']['signature']) ? parse_bbc($context['character']['signature'], true, 'sig_char_' . $context['character']['id_character']) : '<em>' . $txt['no_signature_set'] . '</em>', '
				</div>
				<dl></dl>
				<dl class="noborder" id="sig_preview">
					<dt>', $txt['signature_preview'], ':</dt>
					<dd></dd>
				</dl>
				<div class="signature" id="sig_preview_parsed"></div>
				<dl class="noborder" id="sig_header">
					<dt>', $txt['signature'], ':</dt>
					<dd></dd>
				</dl>
				', template_control_richedit('char_signature', 'smileyBox_message', 'bbcBox_message');

	echo '
				<div>';
	// If there is a limit at all!
	if (!empty($context['signature_limits']['max_length']))
		echo '
				<span class="smalltext">', sprintf($txt['max_sig_characters'], $context['signature_limits']['max_length']), ' <span id="signatureLeft">', $context['signature_limits']['max_length'], '</span></span>';

	echo '
				<span class="floatright"><input type="button" name="preview_signature" id="preview_button" value="', $txt['preview_signature'], '" class="button_submit"></span>
				</div>';

	if ($context['signature_warning'])
		echo '
					<span class="smalltext">', $context['signature_warning'], '</span>';

	// Some javascript used to count how many characters have been used so far in the signature.
	echo '
				<script>
					var maxLength = ', $context['signature_limits']['max_length'], ';
					last_signature = false;

					function calcCharLeft()
					{
						var oldSignature = "", currentSignature = $("#char_signature").data("sceditor").getText().replace(/&#/g, \'&#38;#\');
						var currentChars = 0;

						if (!document.getElementById("signatureLeft"))
							return;

						// Changed since we were last here?
						if (last_signature === currentSignature)
							return;
						last_signature = currentSignature;

						if (oldSignature != currentSignature)
						{
							oldSignature = currentSignature;

							var currentChars = currentSignature.replace(/\r/, "").length;
							if (is_opera)
								currentChars = currentSignature.replace(/\r/g, "").length;

							if (currentChars > maxLength)
								document.getElementById("signatureLeft").className = "error";
							else
								document.getElementById("signatureLeft").className = "";

							if (currentChars > maxLength)
								chars_ajax_getSignaturePreview(false);
							// Only hide it if the only errors were signature errors...
							else if (currentChars <= maxLength)
							{
								// Are there any errors to begin with?
								if ($(document).has("#list_errors"))
								{
									// Remove any signature errors
									$("#list_errors").remove(".sig_error");

									// Show this if other errors remain
									if (!$("#list_errors").has("li"))
									{
										$("#profile_error").css({display:"none"});
										$("#profile_error").html("");
									}
								}
							}
						}

						setInnerHTML(document.getElementById("signatureLeft"), maxLength - currentChars);
					}
					$(document).ready(function() {
						calcCharLeft();
						$("#preview_button").click(function() {
							return chars_ajax_getSignaturePreview(true);
						});
					});
					window.setInterval(calcCharLeft, 1000);
				</script>
				<dl></dl>
				<input type="hidden" name="u" value="', $context['id_member'], '" />
				<input type="submit" name="edit_char" class="button_submit" value="', $txt['save_changes'], '" />
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input type="hidden" name="', $context['edit-char' . $context['character']['id_character'] . '_token_var'], '" value="', $context['edit-char' . $context['character']['id_character'] . '_token'], '">
			</form>
		</div>
	</div>';
}

function template_char_stats()
{
	global $context, $txt;

	// First, show a few text statistics such as post/topic count.
	echo '
	<div id="profileview" class="roundframe">
		<div id="generalstats">
			<dl class="stats">
				<dt>', $txt['statPanel_total_posts'], ':</dt>
				<dd>', $context['num_posts'], ' ', $txt['statPanel_posts'], '</dd>
				<dt>', $txt['statPanel_total_topics'], ':</dt>
				<dd>', $context['num_topics'], ' ', $txt['statPanel_topics'], '</dd>
			</dl>
		</div>';

	// This next section draws a graph showing what times of day they post the most.
	echo '
		<div id="activitytime" class="flow_hidden">
			<div class="title_bar">
				<h3 class="titlebg">
					<span class="generic_icons history"></span> ', $txt['statPanel_activityTime'], '
				</h3>
			</div>';

	// If they haven't post at all, don't draw the graph.
	if (empty($context['posts_by_time']))
		echo '
			<span class="centertext">', $txt['statPanel_noPosts'], '</span>';
	// Otherwise do!
	else
	{
		echo '
			<ul class="activity_stats flow_hidden">';

		// The labels.
		foreach ($context['posts_by_time'] as $time_of_day)
		{
			echo '
				<li', $time_of_day['is_last'] ? ' class="last"' : '', '>
					<div class="bar" style="padding-top: ', ((int) (100 - $time_of_day['relative_percent'])), 'px;" title="', sprintf($txt['statPanel_activityTime_posts'], $time_of_day['posts'], $time_of_day['posts_percent']), '">
						<div style="height: ', (int) $time_of_day['relative_percent'], 'px;">
							<span>', sprintf($txt['statPanel_activityTime_posts'], $time_of_day['posts'], $time_of_day['posts_percent']), '</span>
						</div>
					</div>
					<span class="stats_hour">', $time_of_day['hour_format'], '</span>
				</li>';
		}

		echo '

			</ul>';
	}

	echo '
			<span class="clear">
		</div>';

	// Two columns with the most popular boards by posts and activity (activity = users posts / total posts).
	echo '
		<div class="flow_hidden">
			<div class="half_content">
				<div class="title_bar">
					<h3 class="titlebg">
						<span class="generic_icons replies"></span> ', $txt['statPanel_topBoards'], '
					</h3>
				</div>';

	if (empty($context['popular_boards']))
		echo '
				<span class="centertext">', $txt['statPanel_noPosts'], '</span>';

	else
	{
		echo '
				<dl class="stats">';

		// Draw a bar for every board.
		foreach ($context['popular_boards'] as $board)
		{
			echo '
					<dt>', $board['link'], '</dt>
					<dd>
						<div class="profile_pie" style="background-position: -', ((int) ($board['posts_percent'] / 5) * 20), 'px 0;" title="', sprintf($txt['statPanel_topBoards_memberposts'], $board['posts'], $board['total_posts_char'], $board['posts_percent']), '">
							', sprintf($txt['statPanel_topBoards_memberposts'], $board['posts'], $board['total_posts_char'], $board['posts_percent']), '
						</div>
						', empty($context['hide_num_posts']) ? $board['posts'] : '', '
					</dd>';
		}

		echo '
				</dl>';
	}
	echo '
			</div>';
	echo '
			<div class="half_content">
				<div class="title_bar">
					<h3 class="titlebg">
						<span class="generic_icons replies"></span> ', $txt['statPanel_topBoardsActivity'], '
					</h3>
				</div>';

	if (empty($context['board_activity']))
		echo '
				<span>', $txt['statPanel_noPosts'], '</span>';
	else
	{
		echo '
				<dl class="stats">';

		// Draw a bar for every board.
		foreach ($context['board_activity'] as $activity)
		{
			echo '
					<dt>', $activity['link'], '</dt>
					<dd>
						<div class="profile_pie" style="background-position: -', ((int) ($activity['percent'] / 5) * 20), 'px 0;" title="', sprintf($txt['statPanel_topBoards_posts'], $activity['posts'], $activity['total_posts'], $activity['posts_percent']), '">
							', sprintf($txt['statPanel_topBoards_posts'], $activity['posts'], $activity['total_posts'], $activity['posts_percent']), '
						</div>
						', $activity['percent'], '%
					</dd>';
		}

		echo '
				</dl>';
	}
	echo '
			</div>
		</div>';

	echo '
	</div>';
}

function template_char_sheet()
{
	global $context, $txt, $scripturl;

	echo '
			<div class="cat_bar">
				<h3 class="catbg profile_hd">
					', $txt['char_sheet'], ' - ', $context['character']['character_name'], '
				</h3>
			</div>';

	if (empty($context['character']['sheet_details']['sheet_text']))
	{
		echo '
			<div class="windowbg2">
				', $txt['char_sheet_none'], '
			</div>';
		template_button_strip($context['sheet_buttons'], 'right');
		echo '
			<div class="clear"></div>';
	}
	else
	{
		if (empty($context['character']['sheet_details']['id_approver']))
		{
			echo '
			<div class="noticebox">
				', $txt['char_sheet_not_approved'], '
				', !empty($context['character']['sheet_details']['approval_state']) ? $txt['char_sheet_waiting_approval'] : '', '
			</div>';
		}
		template_button_strip($context['sheet_buttons'], 'right');
		echo '
			<div class="clear"></div>
			<div class="windowbg">
				', parse_bbc($context['character']['sheet_details']['sheet_text'], false), '
			</div>';
	}

	// If it's not set, there's not even a comment box, let alone history.
	if (isset($context['sheet_comments']))
	{
		echo '
			<br />
			<div class="cat_bar">
				<h3 class="catbg">
					', $txt['char_sheet_comments'], '
				</h3>
			</div>
			<div id="quickReplyOptions">
				<div class="roundframe">
					<form action="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=sheet" method="post" accept-charset="UTF-8" name="postmodify" id="postmodify" class="flow_hidden" onsubmit="submitonce(this);smc_saveEntities(\'postmodify\', [\'message\'], \'options\');">
						', template_control_richedit('message', 'smileyBox_message', 'bbcBox_message'), '
						<br class="clear_right">
						<span id="post_confirm_buttons">
							<input type="submit" value="', $txt['char_sheet_add_comment'], '" name="post" class="button_submit">
							<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '">
						</span>
					</form>
				</div>
			</div>';

		foreach ($context['sheet_comments'] as $comment)
		{
			echo '
			<div class="windowbg2">
				<div>
					<strong>', !empty($comment['real_name']) ? $comment['real_name'] : $txt['char_unknown'], '</strong> - ', timeformat($comment['time_posted']), '
				</div>
				<div>', parse_bbc($comment['sheet_comment'], true, 'sheet-comment-' . $comment['id_comment']), '</div>
			</div>';
		}
	}
}

function template_char_sheet_history()
{
	global $context, $txt;

	echo '
			<div class="cat_bar">
				<h3 class="catbg profile_hd">
					', $txt['char_sheet'], ' - ', $context['character']['character_name'], ' - ', $txt['char_sheet_history'], '
				</h3>
			</div>';

	foreach ($context['history_items'] as $history_key => $item)
	{
		switch ($history_key[10])
		{
			case 'S':
				echo '
			<div class="windowbg2" id="version', $item['id_version'], '">
				<div class="sheet_info">
					<span class="generic_icons modify_button"></span> ', sprintf($txt['char_sheet_updated'], timeformat($item['created_time'])), '
					(', $txt['char_sheet_click_to_expand'], $txt['char_sheet_click_to_collapse'], ')
					<div class="floatright">', !empty($item['id_approver']) ? sprintf($txt['char_sheet_approved_on'], timeformat($item['approved_time']), !empty($item['approver_name']) ? $item['approver_name'] : $txt['char_unknown']) : '', '</div>
				</div>
				<div class="clear"></div>
				<div class="sheet"><hr>', parse_bbc($item['sheet_text'], false), '</div>
			</div>';
				break;
			case 'c':
				echo '
			<div class="windowbg2" id="comment', $item['id_comment'], '">
				<div>
					<span class="generic_icons im_on"></span> <strong>', !empty($item['real_name']) ? $item['real_name'] : $txt['char_unknown'], '</strong> - ', timeformat($item['time_posted']), '
				</div>
				<div>', parse_bbc($item['sheet_comment'], true, 'sheet-comment-' . $item['id_comment']), '</div>
			</div>';
				break;
			case 'a':
				echo '
			<div class="windowbg2">
				<span class="generic_icons approve_button"></span> ', sprintf($txt['char_sheet_was_approved'], '#version' . $item), '
			</div>';
				break;
		}
	}

	addInlineJavascript('
	$(".click_collapse, .windowbg2 .sheet").hide();
	$(".click_expand, .click_collapse").on("click", function(e) {
		e.preventDefault();
		$(this).closest(".windowbg2").find(".click_expand, .click_collapse, .sheet").toggle();
	});
	', true);
}

function template_char_sheet_edit()
{
	global $context, $txt, $scripturl;

	echo '
			<div class="cat_bar">
				<h3 class="catbg profile_hd">
					', $txt['char_sheet'], ' - ', $context['character']['character_name'], '
				</h3>
			</div>
			<form action="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=characters;char=', $context['character']['id_character'], ';sa=sheet_edit" method="post" accept-charset="UTF-8" name="postmodify" id="postmodify" class="flow_hidden" onsubmit="submitonce(this);smc_saveEntities(\'postmodify\', [\'message\'], \'options\');">
				<div id="post_area">
					<div class="roundframe">';

	if (!empty($context['sheet_templates']))
	{
		echo '
						', $txt['char_templates_sel'], ' &nbsp;
						<select id="char_sheet_template">
							<option>-- ', $txt['char_templates'], ' --</option>';
		foreach ($context['sheet_templates'] as $id_template => $template)
		{
			echo '
							<option value="', $id_template, '">', $template['name'], '</option>';
		}
		echo '
						</select>
						<a href="#" class="button" id="insert_char_template">', $txt['char_templates_add'], '</a><br><br>';
	}

	template_control_richedit('message', null, 'bbcBox');

	echo '
						<br class="clear">
						<input type="submit" class="button_submit" value="', $txt['save'], '" />
					</div>
				</div>
				<br class="clear">
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '">
			</form>';

	insert_char_sheet_js();

	if (!empty($context['sheet_comments']))
	{
		echo '
			<div class="cat_bar">
				<h3 class="catbg">
					', $txt['char_sheet_comments'], '
				</h3>
			</div>';

		foreach ($context['sheet_comments'] as $id_comment => $comment)
		{
			echo '
			<div class="windowbg">
				<h4>', $comment['real_name'], ' - ', timeformat($comment['time_posted']), '</h4>
				<div class="list_posts">
					', nl2br($comment['sheet_comment']), '
				</div>
			</div>';
		}
	}
}

function insert_char_sheet_js()
{
	global $context;

	addInlineJavascript('
	var sheet_templates = ' . json_encode($context['sheet_templates']) . ';
	$("#insert_char_template").on("click", function (e) {
		e.preventDefault();
		var tmpl = $("#char_sheet_template").val();
		if (sheet_templates.hasOwnProperty(tmpl))
			$("#message").data("sceditor").InsertText(sheet_templates[tmpl].body);
	});', true);
}

function template_character_list()
{
	global $context, $txt, $scripturl;

	echo '
		<div class="cat_bar">
			<h3 class="catbg">
				<span class="generic_icons mlist"></span>
				', $txt['chars_menu_title'], '
			</h3>
		</div>';

	if (!empty($context['filterable_groups']))
	{
		echo '
		<div class="information">
			<form action="', $scripturl, '?action=characters" method="post">
				<a href="javascript:void(0);" id="filter_opts_link" onclick="$(\'#filter_opts\').show(); $(this).hide(); return false;" class="toggle_down">', $txt['filter_characters'], '</a>
				<fieldset id="filter_opts" style="display:none">
					<legend>
						<a href="javascript:void(0);" onclick="$(this).closest(\'fieldset\').hide();$(\'#filter_opts_link\').show(); return false;" class="toggle_up"> ', $txt['filter_characters'], '</a>
					</legend>';
		foreach ($context['filterable_groups'] as $id_group => $group)
		{
			if (is_array($context['filter_groups']))
			{
				$disabled = false;
				$checked = in_array($id_group, $context['filter_groups']);
			}
			else
			{
				$disabled = true;
				$checked = false;
			}
			echo '
					<div class="filter_container">
						<label>
							<input type="checkbox"', $checked ? ' checked' : '', $disabled ? ' disabled' : '', ' name="filter[]" value="', $id_group, '">
							<div class="group_name">', $group['group_name'], '</div>
							<div class="group_badge">', $group['parsed_icons'], '</div>
						</label>
					</div>';
		}
		if (allowedTo('admin_forum'))
		{
			echo '
					<div class="filter_container">
						<label>
							<input type="checkbox"', $context['filter_groups'] === true ? ' checked' : '', ' name="filter[]" id="ungroup" value="-1" onchange="$(\'.filter_container input:not(#ungroup)\').prop(\'disabled\', this.checked)">
							<div class="group_name">', $txt['characters_in_no_groups'], '</div>
							<div class="group_badge"></div>
						</label>
					</div>';
		}
		echo '
					<div class="clearfix">
						<input type="submit" class="button_submit" value="', $txt['apply_filter'], '">
					</div>
				</fieldset>
			</form>
		</div>';
		if (!empty($context['filter_groups']))
		{
			addInlineJavascript('$(\'#filter_opts_link\').trigger(\'click\');', true);
		}
	}

	if (empty($context['char_list']))
	{
		echo '
		<div class="windowbg2">', $txt['characters_none'], '</div>';
	}
	else
	{
		echo $context['page_index'], '
			<div class="char_list_container">';
		foreach ($context['char_list'] as $char)
		{
			echo '
				<div class="windowbg2 char_list">
					<div class="char_list_name"><a href="', $scripturl, '?action=profile;u=', $char['id_member'], ';area=characters;char=', $char['id_character'], '">', $char['character_name'], '</a></div>
					<div class="char_list_avatar"><img src="', $char['avatar'], '" class="avatar"></div>
					<div class="char_list_group">', !empty($char['group_title']) ? $char['group_title'] : '<em>' . $txt['char_no_group'] . '</em>', '</div>
					<div class="char_list_posts">', $txt['member_postcount'], ': ', $char['posts'], '</div>
					<div class="char_list_created">', timeformat($char['date_created']), '</div>
					<div class="char_list_sheet">', !empty($char['retired']) ? $txt['char_retired'] : (!empty($char['char_sheet']) ? '<a href="' . $scripturl . '?action=profile;u=' . $char['id_member'] . ';area=characters;char=' . $char['id_character'] . ';sa=sheet">' . $txt['char_sheet'] . '</a>' : '<em>' . $txt['char_sheet_none_short'] . '</em>'), '</div>
				</div>';
		}
		echo '
			</div>';
	}
}

function template_character_sheet_list()
{
	global $context, $txt, $scripturl, $modSettings, $settings;

	echo '
		<div id="messageindex">
			<div class="title_bar" id="topic_header">
				<div class="board_icon">&nbsp;</div>
				<div class="info"><a href="', $scripturl, '?action=characters;sa=sheets;group=', $context['group_id'], ';sort=name', $context['sort_by'] == 'name' && $context['sort_order'] == 'asc' ? ';dir=desc' : ';dir=asc', '">', $txt['chars_menu_title'], $context['sort_by'] == 'name' ? '<span class="generic_icons ' . ($context['sort_order'] == 'desc' ? 'sort_down' : 'sort_up') . '"></span>' : '', '</a></div>
				<div class="lastpost"></div>
				<div class="board_stats"><a href="', $scripturl, '?action=characters;sa=sheets;group=', $context['group_id'], ';sort=last_active', $context['sort_by'] == 'last_active' && $context['sort_order'] == 'asc' ? ';dir=desc' : ';dir=asc', '">', $txt['lastLoggedIn'], $context['sort_by'] == 'last_active' ? '<span class="generic_icons ' . ($context['sort_order'] == 'desc' ? 'sort_down' : 'sort_up') . '"></span>' : '', '</a></div>
			</div>
			<div id="topic_container">';
	foreach ($context['characters'] as $character)
	{
		echo '
				<div class="windowbg">
					<div class="board_icon">
						<img class="avatar_small" src="', !empty($character['avatar']) ? $character['avatar'] : $settings['images_url'] . '/default.png', '" alt="" />
					</div>
					<div class="info">
						<div>
							<div class="message_index_title">
								<span id="char', $character['id_character'], '">
									<a href="', $scripturl, '?action=profile;u=', $character['id_member'], ';area=characters;char=', $character['id_character'], '">', $character['character_name'], '</a>', $character['retired'] ? ' (' . $txt['char_retired'] . ')' : '', '
									<a class="button" href="', $scripturl, '?action=profile;u=', $character['id_member'], ';area=characters;char=', $character['id_character'], '">', $txt['profile'], '</a>
									<a class="button" href="', $scripturl, '?action=profile;u=', $character['id_member'], ';area=characters;char=', $character['id_character'], ';sa=sheet">', $txt['char_sheet_link'], '</a>
								</span>
							</div>
							<p class="floatleft">', $txt['date_created'], ' ', timeformat($character['date_created']), '</p>
							<br class="clear" />
						</div>
					</div>
					<div class="lastpost char_group_container">
						<div class="char_group">', implode('</div><div class="char_group">', $character['groups']['combined_badges']), '</div>
					</div>
					<div class="board_stats">', timeformat($character['last_active']), '</div>
				</div>';
	}

	echo '
			</div>
		</div>
		<div class="clear"></div>';
}

?>