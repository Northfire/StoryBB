<?php

/**
 * This file, unpredictable as this might be, handles basic administration.
 *
 * @package StoryBB (storybb.org) - A roleplayer's forum software
 * @copyright 2018 StoryBB and individual contributors (see contributors.txt)
 * @license 3-clause BSD (see accompanying LICENSE file)
 *
 * @version 3.0 Alpha 1
 */

use StoryBB\Helper\Environment;

/**
 * The main admin handling function.<br>
 * It initialises all the basic context required for the admin center.<br>
 * It passes execution onto the relevant admin section.<br>
 * If the passed section is not found it shows the admin home page.
 */
function AdminMain()
{
	global $txt, $context, $scripturl, $modSettings, $settings;
	global $sourcedir, $options, $boarddir;

	// Load the language and templates....
	loadLanguage('Admin');
	loadJavaScriptFile('admin.js', array(), 'sbb_admin');
	loadCSSFile('admin.css', array(), 'sbb_admin');

	$context['show_admin_search'] = $context['user']['is_admin'];

	// No indexing evil stuff.
	$context['robot_no_index'] = true;

	require_once($sourcedir . '/Subs-Menu.php');

	// Some preferences.
	$context['admin_preferences'] = !empty($options['admin_preferences']) ? sbb_json_decode($options['admin_preferences'], true) : array();

	/** @var array $admin_areas Defines the menu structure for the admin center. See {@link Subs-Menu.php Subs-Menu.php} for details! */
	$admin_areas = array(
		'forum' => array(
			'title' => $txt['admin_main'],
			'permission' => array('admin_forum', 'manage_permissions', 'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news', 'manage_boards', 'manage_smileys', 'manage_attachments'),
			'areas' => array(
				'index' => array(
					'label' => $txt['admin_center'],
					'function' => 'AdminHome',
					'icon' => 'administration',
				),
				'news' => array(
					'label' => $txt['news_title'],
					'file' => 'ManageNews.php',
					'function' => 'ManageNews',
					'icon' => 'news',
					'permission' => array('edit_news', 'send_mail', 'admin_forum'),
					'subsections' => array(
						'editnews' => array($txt['admin_edit_news'], 'edit_news'),
						'mailingmembers' => array($txt['admin_newsletters'], 'send_mail'),
						'settings' => array($txt['settings'], 'admin_forum'),
					),
				),
				'contactform' => [
					'label' => $txt['contact_us'],
					'file' => 'ManageContact.php',
					'function' => 'ManageContact',
					'icon' => 'contact',
					'permission' => ['admin_forum'],
				],
				'search' => array(
					'function' => 'AdminSearch',
					'permission' => array('admin_forum'),
					'select' => 'index'
				),
				'adminlogoff' => array(
					'label' => $txt['admin_logoff'],
					'function' => 'AdminEndSession',
					'enabled' => empty($modSettings['securityDisable']),
					'icon' => 'exit',
				),

			),
		),
		'config' => array(
			'title' => $txt['admin_config'],
			'permission' => array('admin_forum'),
			'areas' => array(
				'featuresettings' => array(
					'label' => $txt['modSettings_title'],
					'file' => 'ManageSettings.php',
					'function' => 'ModifyFeatureSettings',
					'icon' => 'features',
					'subsections' => array(
						'basic' => array($txt['mods_cat_features']),
						'bbc' => array($txt['manageposts_bbc_settings']),
						'layout' => array($txt['mods_cat_layout']),
						'sig' => array($txt['signature_settings_short']),
						'profile' => array($txt['custom_profile_shorttitle']),
						'alerts' => array($txt['notifications']),
					),
				),
				'antispam' => array(
					'label' => $txt['antispam_title'],
					'file' => 'ManageSettings.php',
					'function' => 'ModifyAntispamSettings',
					'icon' => 'security',
				),
				'languages' => array(
					'label' => $txt['language_configuration'],
					'file' => 'ManageLanguages.php',
					'function' => 'ManageLanguages',
					'icon' => 'languages',
					'subsections' => array(
						'edit' => array($txt['language_edit']),
						'settings' => array($txt['language_settings']),
					),
				),
				'current_theme' => array(
					'label' => $txt['theme_current_settings'],
					'file' => 'Themes.php',
					'function' => 'ThemesMain',
					'custom_url' => $scripturl . '?action=admin;area=theme;sa=list;th=' . $settings['theme_id'],
					'icon' => 'current_theme',
				),
				'theme' => array(
					'label' => $txt['theme_admin'],
					'file' => 'Themes.php',
					'function' => 'ThemesMain',
					'custom_url' => $scripturl . '?action=admin;area=theme',
					'icon' => 'themes',
					'subsections' => array(
						'admin' => array($txt['themeadmin_admin_title']),
						'list' => array($txt['themeadmin_list_title']),
						'reset' => array($txt['themeadmin_reset_title']),
					),
				),
			),
		),
		'layout' => array(
			'title' => $txt['layout_controls'],
			'permission' => array('manage_boards', 'admin_forum', 'manage_smileys', 'manage_attachments', 'moderate_forum'),
			'areas' => array(
				'manageboards' => array(
					'label' => $txt['admin_boards'],
					'file' => 'ManageBoards.php',
					'function' => 'ManageBoards',
					'icon' => 'boards',
					'permission' => array('manage_boards'),
					'subsections' => array(
						'main' => array($txt['boardsEdit']),
						'newcat' => array($txt['mboards_new_cat']),
						'settings' => array($txt['settings'], 'admin_forum'),
					),
				),
				'postsettings' => array(
					'label' => $txt['manageposts'],
					'file' => 'ManagePosts.php',
					'function' => 'ManagePostSettings',
					'permission' => array('admin_forum'),
					'icon' => 'posts',
					'subsections' => array(
						'posts' => array($txt['manageposts_settings']),
						'censor' => array($txt['admin_censored_words']),
						'topics' => array($txt['manageposts_topic_settings']),
						'drafts' => array($txt['manage_drafts']),
					),
				),
				'managesearch' => array(
					'label' => $txt['manage_search'],
					'file' => 'ManageSearch.php',
					'function' => 'ManageSearch',
					'icon' => 'search',
					'permission' => array('admin_forum'),
					'subsections' => array(
						'method' => array($txt['search_method']),
						'settings' => array($txt['settings']),
					),
				),
				'smileys' => array(
					'label' => $txt['smileys_manage'],
					'file' => 'ManageSmileys.php',
					'function' => 'ManageSmileys',
					'icon' => 'smiley',
					'permission' => array('manage_smileys'),
					'subsections' => array(
						'addsmiley' => array($txt['smileys_add']),
						'editsmileys' => array($txt['smileys_edit']),
						'setorder' => array($txt['smileys_set_order']),
						'editicons' => array($txt['icons_edit_message_icons'], 'enabled' => !empty($modSettings['messageIcons_enable'])),
						'settings' => array($txt['settings']),
					),
				),
				'manageattachments' => array(
					'label' => $txt['attachments_avatars'],
					'file' => 'ManageAttachments.php',
					'function' => 'ManageAttachments',
					'icon' => 'attachment',
					'permission' => array('manage_attachments'),
					'subsections' => array(
						'browse' => array($txt['attachment_manager_browse']),
						'attachments' => array($txt['attachment_manager_settings']),
						'avatars' => array($txt['attachment_manager_avatar_settings']),
						'attachpaths' => array($txt['attach_directories']),
						'maintenance' => array($txt['attachment_manager_maintenance']),
					),
				),
			),
		),
		'members' => array(
			'title' => $txt['admin_manage_members'],
			'permission' => array('moderate_forum', 'manage_membergroups', 'manage_bans', 'manage_permissions', 'admin_forum'),
			'areas' => array(
				'viewmembers' => array(
					'label' => $txt['admin_users'],
					'file' => 'ManageMembers.php',
					'function' => 'ViewMembers',
					'icon' => 'members',
					'permission' => array('moderate_forum'),
					'subsections' => array(
						'all' => array($txt['view_all_members']),
						'search' => array($txt['mlist_search']),
					),
				),
				'membergroups' => array(
					'label' => $txt['admin_groups'],
					'file' => 'ManageMembergroups.php',
					'function' => 'ModifyMembergroups',
					'icon' => 'membergroups',
					'permission' => array('manage_membergroups'),
					'subsections' => array(
						'index' => array($txt['membergroups_edit_groups'], 'manage_membergroups'),
						'add' => array($txt['membergroups_new_group'], 'manage_membergroups'),
						'badges' => array($txt['badges'], 'admin_forum'),
					),
				),
				'permissions' => array(
					'label' => $txt['edit_permissions'],
					'file' => 'ManagePermissions.php',
					'function' => 'ModifyPermissions',
					'icon' => 'permissions',
					'permission' => array('manage_permissions'),
					'subsections' => array(
						'index' => array($txt['permissions_groups'], 'manage_permissions'),
						'board' => array($txt['permissions_boards'], 'manage_permissions'),
						'profiles' => array($txt['permissions_profiles'], 'manage_permissions'),
						'postmod' => array($txt['permissions_post_moderation'], 'manage_permissions'),
					),
				),
				'regcenter' => array(
					'label' => $txt['registration_center'],
					'file' => 'ManageRegistration.php',
					'function' => 'RegCenter',
					'icon' => 'regcenter',
					'permission' => array('admin_forum', 'moderate_forum'),
					'subsections' => array(
						'register' => array($txt['admin_browse_register_new'], 'moderate_forum'),
						'reservednames' => array($txt['admin_reserved_set'], 'admin_forum'),
						'policies' => array($txt['admin_policies'], 'admin_forum'),
						'settings' => array($txt['settings'], 'admin_forum'),
					),
				),
				'warnings' => array(
					'label' => $txt['warnings'],
					'file' => 'ManageSettings.php',
					'function' => 'ModifyWarningSettings',
					'icon' => 'warning',
					'inactive' => $modSettings['warning_settings'][0] == 0,
					'permission' => array('admin_forum'),
				),
				'ban' => array(
					'label' => $txt['ban_title'],
					'file' => 'ManageBans.php',
					'function' => 'Ban',
					'icon' => 'ban',
					'permission' => 'manage_bans',
					'subsections' => array(
						'list' => array($txt['ban_edit_list']),
						'add' => array($txt['ban_add_new']),
						'browse' => array($txt['ban_trigger_browse']),
						'log' => array($txt['ban_log']),
					),
				),
				'paidsubscribe' => array(
					'label' => $txt['paid_subscriptions'],
					'inactive' => empty($modSettings['paid_enabled']),
					'file' => 'ManagePaid.php',
					'icon' => 'paid',
					'function' => 'ManagePaidSubscriptions',
					'permission' => 'admin_forum',
					'subsections' => empty($modSettings['paid_enabled']) ? array() : array(
						'view' => array($txt['paid_subs_view']),
						'settings' => array($txt['settings']),
					),
				),
			),
		),
		'characters' => array(
			'title' => $txt['chars_menu_title'],
			'permission' => array('admin_forum'),
			'areas' => array(
				'immersion' => array(
					'label' => $txt['immersion'],
					'file' => 'Admin-Chars.php',
					'function' => 'CharacterImmersion',
					'icon' => 'quick_edit_button',
					'permission' => array('admin_forum'),
					'subsections' => [],
				),
				'templates' => array(
					'label' => $txt['char_templates'],
					'file' => 'Admin-Chars.php',
					'function' => 'CharacterTemplates',
					'icon' => 'quick_edit_button',
					'permission' => array('admin_forum'),
					'subsections' => array(),
				),
				'sheets' => [
					'label' => $txt['char_sheet_admin'],
					'file' => 'Admin-Chars.php',
					'function' => 'CharacterSheets',
					'icon' => 'package_ops',
					'permission' => array('admin_forum'),
					'subsections' => array(),
				],
			),
		),
		'maintenance' => array(
			'title' => $txt['admin_maintenance'],
			'permission' => array('admin_forum'),
			'areas' => array(
				'serversettings' => array(
					'label' => $txt['admin_server_settings'],
					'file' => 'ManageServer.php',
					'function' => 'ModifySettings',
					'icon' => 'server',
					'subsections' => array(
						'general' => array($txt['general_settings']),
						'cookie' => array($txt['cookies_sessions_settings']),
						'security' => array($txt['security_settings']),
						'cache' => array($txt['caching_settings']),
						'phpinfo' => array($txt['phpinfo_settings']),
					),
				),
				'maintain' => array(
					'label' => $txt['maintain_title'],
					'file' => 'ManageMaintenance.php',
					'icon' => 'maintain',
					'function' => 'ManageMaintenance',
					'subsections' => array(
						'routine' => array($txt['maintain_sub_routine'], 'admin_forum'),
						'database' => array($txt['maintain_sub_database'], 'admin_forum'),
						'members' => array($txt['maintain_sub_members'], 'admin_forum'),
						'topics' => array($txt['maintain_sub_topics'], 'admin_forum'),
						'hooks' => array($txt['hooks_title_list'], 'admin_forum'),
					),
				),
				'scheduledtasks' => array(
					'label' => $txt['maintain_tasks'],
					'file' => 'ManageScheduledTasks.php',
					'icon' => 'scheduled',
					'function' => 'ManageScheduledTasks',
					'subsections' => array(
						'tasks' => array($txt['maintain_tasks'], 'admin_forum'),
						'tasklog' => array($txt['scheduled_log'], 'admin_forum'),
					),
				),
				'mailqueue' => array(
					'label' => $txt['mailqueue_title'],
					'file' => 'ManageMail.php',
					'function' => 'ManageMail',
					'icon' => 'mail',
					'subsections' => array(
						'browse' => array($txt['mailqueue_browse'], 'admin_forum'),
						'settings' => array($txt['mailqueue_settings'], 'admin_forum'),
					),
				),
				'reports' => array(
					'label' => $txt['generate_reports'],
					'file' => 'Reports.php',
					'function' => 'ReportsMain',
					'icon' => 'reports',
				),
				'logs' => array(
					'label' => $txt['logs'],
					'function' => 'AdminLogs',
					'icon' => 'logs',
					'subsections' => array(
						'errorlog' => array($txt['errlog'], 'admin_forum', 'enabled' => !empty($modSettings['enableErrorLogging']), 'url' => $scripturl . '?action=admin;area=logs;sa=errorlog;desc'),
						'adminlog' => array($txt['admin_log'], 'admin_forum', 'enabled' => !empty($modSettings['adminlog_enabled'])),
						'modlog' => array($txt['moderation_log'], 'admin_forum', 'enabled' => !empty($modSettings['modlog_enabled'])),
						'banlog' => array($txt['ban_log'], 'manage_bans'),
						'tasklog' => array($txt['scheduled_log'], 'admin_forum'),
						'settings' => array($txt['log_settings'], 'admin_forum'),
					),
				),
				'repairboards' => array(
					'label' => $txt['admin_repair'],
					'file' => 'RepairBoards.php',
					'function' => 'RepairBoards',
					'select' => 'maintain',
					'hidden' => true,
				),
			),
		),
	);

	// Any files to include for administration?
	if (!empty($modSettings['integrate_admin_include']))
	{
		$admin_includes = explode(',', $modSettings['integrate_admin_include']);
		foreach ($admin_includes as $include)
		{
			$include = strtr(trim($include), array('$boarddir' => $boarddir, '$sourcedir' => $sourcedir, '$themedir' => $settings['theme_dir']));
			if (file_exists($include))
				require_once($include);
		}
	}

	// Make sure the administrator has a valid session...
	validateSession();

	// Actually create the menu!
	$admin_include_data = createMenu($admin_areas, array('do_big_icons' => true));
	unset($admin_areas);

	// Nothing valid?
	if ($admin_include_data == false)
		fatal_lang_error('no_access', false);

	// Build the link tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=admin',
		'name' => $txt['admin_center'],
	);
	if (isset($admin_include_data['current_area']) && $admin_include_data['current_area'] != 'index')
		$context['linktree'][] = array(
			'url' => $scripturl . '?action=admin;area=' . $admin_include_data['current_area'] . ';' . $context['session_var'] . '=' . $context['session_id'],
			'name' => $admin_include_data['label'],
		);
	if (!empty($admin_include_data['current_subsection']) && $admin_include_data['subsections'][$admin_include_data['current_subsection']][0] != $admin_include_data['label'])
		$context['linktree'][] = array(
			'url' => $scripturl . '?action=admin;area=' . $admin_include_data['current_area'] . ';sa=' . $admin_include_data['current_subsection'] . ';' . $context['session_var'] . '=' . $context['session_id'],
			'name' => $admin_include_data['subsections'][$admin_include_data['current_subsection']][0],
		);

	// Make a note of the Unique ID for this menu.
	$context['admin_menu_id'] = $context['max_menu_id'];
	$context['admin_menu_name'] = 'menu_data_' . $context['admin_menu_id'];

	// Where in the admin are we?
	$context['admin_area'] = $admin_include_data['current_area'];

	// Now - finally - call the right place!
	if (isset($admin_include_data['file']))
		require_once($sourcedir . '/' . $admin_include_data['file']);

	// Get the right callable.
	$call = call_helper($admin_include_data['function'], true);

	// Is it valid?
	if (!empty($call))
		call_user_func($call);
}

/**
 * The main administration section.
 * It prepares all the data necessary for the administration front page.
 * It uses the Admin template along with the admin sub template.
 * It requires the moderate_forum, manage_membergroups, manage_bans,
 *  admin_forum, manage_permissions, manage_attachments, manage_smileys,
 *  manage_boards, edit_news, or send_mail permission.
 *  It uses the index administrative area.
 *  It can be found by going to ?action=admin.
*/
function AdminHome()
{
	global $sourcedir, $forum_version, $txt, $scripturl, $context, $user_info;

	// You have to be able to do at least one of the below to see this page.
	isAllowedTo(array('admin_forum', 'manage_permissions', 'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news', 'manage_boards', 'manage_smileys', 'manage_attachments'));

	// Find all of this forum's administrators...
	require_once($sourcedir . '/Subs-Membergroups.php');
	if (listMembergroupMembers_Href($context['administrators'], 1, 32) && allowedTo('manage_membergroups'))
	{
		// Add a 'more'-link if there are more than 32.
		$context['more_admins_link'] = '<a href="' . $scripturl . '?action=moderate;area=viewgroups;sa=members;group=1">' . $txt['more'] . '</a>';
	}

	// This makes it easier to get the latest news with your time format.
	$context['time_format'] = urlencode($user_info['time_format']);
	$context['forum_version'] = $forum_version;

	// Get a list of current server versions.
	require_once($sourcedir . '/Subs-Admin.php');
	$checkFor = array(
		'gd',
		'imagemagick',
		'db_server',
		'phpa',
		'apc',
		'memcache',
		'xcache',
		'php',
		'server',
	);
	$context['current_versions'] = getServerVersions($checkFor);

	$context['can_admin'] = allowedTo('admin_forum');

	// Set up for the template, like some convenient derefencing.
	$context['admin_menu'] = $context[$context['admin_menu_name']];
	$context['sub_template'] = 'admin_home';
	$context['page_title'] = $context['admin_area'] == 'support' ? $txt['support_title'] : $txt['admin_center'];
	if ($context['admin_area'] != 'support')
		$context[$context['admin_menu_name']]['tab_data'] = array(
			'title' => $txt['admin_center'],
			'help' => '',
			'description' => '<strong>' . $txt['hello_guest'] . ' ' . $context['user']['name'] . '!</strong>
						' . sprintf($txt['admin_main_welcome'], $txt['admin_center'], $txt['help'], $txt['help']),
		);

	$context['admin_news'] = getAdminFile('updates.json');
	if (empty($context['admin_news']))
	{
		$context['admin_news'] = [
			'current_version' => '??',
			'sbbAnnouncements' => [],
			'needs_update' => false,
		];
	}

	if (!empty($context['admin_news']['current_version']))
	{

		$context['admin_news']['needs_update'] = version_compare(strtr($context['forum_version'], ['StoryBB ' => '']), $context['admin_news']['current_version'], '<');
	}
}

/**
 * This function allocates out all the search stuff.
 */
function AdminSearch()
{
	global $txt, $context, $smcFunc, $sourcedir;

	isAllowedTo('admin_forum');

	// What can we search for?
	$subActions = array(
		'internal' => 'AdminSearchInternal',
		'member' => 'AdminSearchMember',
	);

	$context['search_type'] = !isset($_REQUEST['search_type']) || !isset($subActions[$_REQUEST['search_type']]) ? 'internal' : $_REQUEST['search_type'];
	$context['search_term'] = isset($_REQUEST['search_term']) ? $smcFunc['htmlspecialchars']($_REQUEST['search_term'], ENT_QUOTES) : '';

	$context['sub_template'] = 'admin_search_results';
	$context['page_title'] = $txt['admin_search_results'];

	// Keep track of what the admin wants.
	if (empty($context['admin_preferences']['sb']) || $context['admin_preferences']['sb'] != $context['search_type'])
	{
		$context['admin_preferences']['sb'] = $context['search_type'];

		// Update the preferences.
		require_once($sourcedir . '/Subs-Admin.php');
		updateAdminPreferences();
	}

	if (trim($context['search_term']) == '')
		$context['search_results'] = array();
	else
		call_helper($subActions[$context['search_type']]);
}

/**
 * A complicated but relatively quick internal search.
 */
function AdminSearchInternal()
{
	global $context, $txt, $helptxt, $scripturl, $sourcedir;

	// Try to get some more memory.
	Environment::setMemoryLimit('128M');

	// Load a lot of language files.
	$language_files = array(
		'Help', 'ManageMail', 'ManageSettings', 'ManageBoards', 'ManagePaid', 'ManagePermissions', 'Search',
		'Login', 'ManageSmileys', 'Drafts',
	);

	// All the files we need to include.
	$include_files = array(
		'ManageSettings', 'ManageBoards', 'ManageNews', 'ManageAttachments', 'ManageMail', 'ManagePaid', 'ManagePermissions',
		'ManagePosts', 'ManageRegistration', 'ManageSearch', 'ManageServer', 'ManageSmileys', 'ManageLanguages',
	);

	// This is a special array of functions that contain setting data - we query all these to simply pull all setting bits!
	$settings_search = array(
		array('ModifyBasicSettings', 'area=featuresettings;sa=basic'),
		array('ModifyBBCSettings', 'area=featuresettings;sa=bbc'),
		array('ModifyLayoutSettings', 'area=featuresettings;sa=layout'),
		array('ModifySignatureSettings', 'area=featuresettings;sa=sig'),
		array('ModifyAntispamSettings', 'area=antispam'),
		array('ModifyWarningSettings', 'area=warnings'),
		array('ManageAttachmentSettings', 'area=manageattachments;sa=attachments'),
		array('ManageAvatarSettings', 'area=manageattachments;sa=avatars'),
		array('EditBoardSettings', 'area=manageboards;sa=settings'),
		array('ModifyMailSettings', 'area=mailqueue;sa=settings'),
		array('ModifyNewsSettings', 'area=news;sa=settings'),
		array('ModifyPostSettings', 'area=postsettings;sa=posts'),
		array('ModifyTopicSettings', 'area=postsettings;sa=topics'),
		array('ModifyDraftSettings', 'area=postsettings;sa=drafts'),
		array('EditSearchSettings', 'area=managesearch;sa=settings'),
		array('EditSmileySettings', 'area=smileys;sa=settings'),
		array('ModifyGeneralSettings', 'area=serversettings;sa=general'),
		array('ModifyCookieSettings', 'area=serversettings;sa=cookie'),
		array('ModifyGeneralSecuritySettings', 'area=serversettings;sa=security'),
		array('ModifyCacheSettings', 'area=serversettings;sa=cache'),
		array('ModifyLanguageSettings', 'area=languages;sa=settings'),
		array('ModifyRegistrationSettings', 'area=regcenter;sa=settings'),
		array('ModifySubscriptionSettings', 'area=paidsubscribe;sa=settings'),
		array('ModifyLogSettings', 'area=logs;sa=settings'),
	);

	call_integration_hook('integrate_admin_search', array(&$language_files, &$include_files, &$settings_search));

	loadLanguage(implode('+', $language_files));

	foreach ($include_files as $file)
		require_once($sourcedir . '/' . $file . '.php');

	/* This is the huge array that defines everything... it's a huge array of items formatted as follows:
		0 = Language index (Can be array of indexes) to search through for this setting.
		1 = URL for this indexes page.
		2 = Help index for help associated with this item (If different from 0)
	*/

	$search_data = array(
		// All the major sections of the forum.
		'sections' => array(
		),
		'settings' => array(
			array('CAPTCHA', 'area=antispam'),
		),
	);

	// Go through the admin menu structure trying to find suitably named areas!
	foreach ($context[$context['admin_menu_name']]['sections'] as $section)
	{
		foreach ($section['areas'] as $menu_key => $menu_item)
		{
			$search_data['sections'][] = array($menu_item['label'], 'area=' . $menu_key);
			if (!empty($menu_item['subsections']))
				foreach ($menu_item['subsections'] as $key => $sublabel)
				{
					if (isset($sublabel['label']))
						$search_data['sections'][] = array($sublabel['label'], 'area=' . $menu_key . ';sa=' . $key);
				}
		}
	}

	foreach ($settings_search as $setting_area)
	{
		// Get a list of their variables.
		$config_vars = $setting_area[0](true);

		foreach ($config_vars as $var)
			if (!empty($var[1]) && !in_array($var[0], array('permissions', 'switch', 'desc')))
				$search_data['settings'][] = array($var[(isset($var[2]) && in_array($var[2], array('file', 'db'))) ? 0 : 1], $setting_area[1], 'alttxt' => (isset($var[2]) && in_array($var[2], array('file', 'db'))) || isset($var[3]) ? (in_array($var[2], array('file', 'db')) ? $var[1] : $var[3]) : '');
	}

	$context['page_title'] = $txt['admin_search_results'];
	$context['search_results'] = array();

	$search_term = strtolower(un_htmlspecialchars($context['search_term']));
	// Go through all the search data trying to find this text!
	foreach ($search_data as $section => $data)
	{
		foreach ($data as $item)
		{
			$found = false;
			if (!is_array($item[0]))
				$item[0] = array($item[0]);
			foreach ($item[0] as $term)
			{
				if (stripos($term, $search_term) !== false || (isset($txt[$term]) && stripos($txt[$term], $search_term) !== false) || (isset($txt['setting_' . $term]) && stripos($txt['setting_' . $term], $search_term) !== false))
				{
					$found = $term;
					break;
				}
			}

			if ($found)
			{
				// Format the name - and remove any descriptions the entry may have.
				$name = isset($txt[$found]) ? $txt[$found] : (isset($txt['setting_' . $found]) ? $txt['setting_' . $found] : (!empty($item['alttxt']) ? $item['alttxt'] : $found));
				$name = preg_replace('~<(?:div|span)\sclass="smalltext">.+?</(?:div|span)>~', '', $name);

				$context['search_results'][] = array(
					'url' => (substr($item[1], 0, 4) == 'area' ? $scripturl . '?action=admin;' . $item[1] : $item[1]) . ';' . $context['session_var'] . '=' . $context['session_id'] . ((substr($item[1], 0, 4) == 'area' && $section == 'settings' ? '#' . $item[0][0] : '')),
					'name' => $name,
					'type' => $section,
					'type_string' => isset($txt['admin_search_section_' . $section]) ? $txt['admin_search_section_' . $section] : $section,
					'help' => shorten_subject(isset($item[2]) ? strip_tags($helptxt[$item[2]]) : (isset($helptxt[$found]) ? strip_tags($helptxt[$found]) : ''), 255),
				);
			}
		}
	}
}

/**
 * All this does is pass through to manage members.
 * {@see ViewMembers()}
 */
function AdminSearchMember()
{
	global $context, $sourcedir;

	require_once($sourcedir . '/ManageMembers.php');
	$_REQUEST['sa'] = 'query';

	$_POST['membername'] = un_htmlspecialchars($context['search_term']);
	$_POST['types'] = '';

	ViewMembers();
}

/**
 * This function decides which log to load.
 */
function AdminLogs()
{
	global $sourcedir, $context, $txt, $scripturl, $modSettings;

	// These are the logs they can load.
	$log_functions = array(
		'errorlog' => array('ManageErrors.php', 'ViewErrorLog'),
		'adminlog' => array('Modlog.php', 'ViewModlog', 'disabled' => empty($modSettings['adminlog_enabled'])),
		'modlog' => array('Modlog.php', 'ViewModlog', 'disabled' => empty($modSettings['modlog_enabled'])),
		'banlog' => array('ManageBans.php', 'BanLog'),
		'tasklog' => array('ManageScheduledTasks.php', 'TaskLog'),
		'settings' => array('ManageSettings.php', 'ModifyLogSettings'),
	);

	// If it's not got a sa set it must have come here for first time, pretend error log should be reversed.
	if (!isset($_REQUEST['sa']))
		$_REQUEST['desc'] = true;

	// Setup some tab stuff.
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['logs'],
		'help' => '',
		'description' => $txt['maintain_info'],
		'tabs' => array(
			'errorlog' => array(
				'url' => $scripturl . '?action=admin;area=logs;sa=errorlog;desc',
				'description' => sprintf($txt['errlog_desc'], $txt['remove']),
			),
			'adminlog' => array(
				'description' => $txt['admin_log_desc'],
			),
			'modlog' => array(
				'description' => $txt['moderation_log_desc'],
			),
			'banlog' => array(
				'description' => $txt['ban_log_description'],
			),
			'tasklog' => array(
				'description' => $txt['scheduled_log_desc'],
			),
			'settings' => array(
				'description' => $txt['log_settings_desc'],
			),
		),
	);

	call_integration_hook('integrate_manage_logs', array(&$log_functions));

	$subAction = isset($_REQUEST['sa']) && isset($log_functions[$_REQUEST['sa']]) && empty($log_functions[$_REQUEST['sa']]['disabled']) ? $_REQUEST['sa'] : 'errorlog';

	require_once($sourcedir . '/' . $log_functions[$subAction][0]);
	call_helper($log_functions[$subAction][1]);
}

/**
 * This ends a admin session, requiring authentication to access the ACP again.
 */
function AdminEndSession()
{
	// This is so easy!
	unset($_SESSION['admin_time']);

	// Clean any admin tokens as well.
	foreach ($_SESSION['token'] as $key => $token)
		if (strpos($key, '-admin') !== false)
			unset($_SESSION['token'][$key]);

	redirectexit();
}
