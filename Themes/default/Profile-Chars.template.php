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