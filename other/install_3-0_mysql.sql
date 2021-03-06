#### ATTENTION: You do not need to run or use this file!  The install.php script does everything for you!
#### Install script for MySQL 4.0.18+

#
# Table structure for table `admin_info_files`
#

CREATE TABLE {$db_prefix}admin_info_files (
  id_file TINYINT UNSIGNED AUTO_INCREMENT,
  filename VARCHAR(255) NOT NULL DEFAULT '',
  path VARCHAR(255) NOT NULL DEFAULT '',
  parameters VARCHAR(255) NOT NULL DEFAULT '',
  data TEXT NOT NULL,
  filetype VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_file),
  INDEX idx_filename (filename(30))
) ENGINE={$engine};

#
# Table structure for table `approval_queue`
#

CREATE TABLE {$db_prefix}approval_queue (
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  id_attach INT UNSIGNED NOT NULL DEFAULT '0'
) ENGINE={$engine};

#
# Table structure for table `attachments`
#

CREATE TABLE {$db_prefix}attachments (
  id_attach INT UNSIGNED AUTO_INCREMENT,
  id_thumb INT UNSIGNED NOT NULL DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  id_character INT NOT NULL DEFAULT '0',
  id_folder TINYINT NOT NULL DEFAULT '1',
  attachment_type TINYINT UNSIGNED NOT NULL DEFAULT '0',
  filename VARCHAR(255) NOT NULL DEFAULT '',
  file_hash VARCHAR(40) NOT NULL DEFAULT '',
  fileext VARCHAR(8) NOT NULL DEFAULT '',
  size INT UNSIGNED NOT NULL DEFAULT '0',
  downloads MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  width MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  height MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  mime_type VARCHAR(128) NOT NULL DEFAULT '',
  approved TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (id_attach),
  UNIQUE idx_id_character (id_character, id_attach),
  INDEX idx_id_msg (id_msg),
  INDEX idx_attachment_type (attachment_type)
) ENGINE={$engine};

#
# Table structure for table `background_tasks`
#

CREATE TABLE {$db_prefix}background_tasks (
  id_task INT UNSIGNED AUTO_INCREMENT,
  task_file VARCHAR(255) NOT NULL DEFAULT '',
  task_class VARCHAR(255) NOT NULL DEFAULT '',
  task_data MEDIUMTEXT NOT NULL,
  claimed_time INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_task)
) ENGINE={$engine};

#
# Table structure for table `ban_groups`
#

CREATE TABLE {$db_prefix}ban_groups (
  id_ban_group MEDIUMINT UNSIGNED AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL DEFAULT '',
  ban_time INT UNSIGNED NOT NULL DEFAULT '0',
  expire_time INT UNSIGNED,
  cannot_access TINYINT UNSIGNED NOT NULL DEFAULT '0',
  cannot_register TINYINT UNSIGNED NOT NULL DEFAULT '0',
  cannot_post TINYINT UNSIGNED NOT NULL DEFAULT '0',
  cannot_login TINYINT UNSIGNED NOT NULL DEFAULT '0',
  reason VARCHAR(255) NOT NULL DEFAULT '',
  notes TEXT NOT NULL,
  PRIMARY KEY (id_ban_group)
) ENGINE={$engine};

#
# Table structure for table `ban_items`
#

CREATE TABLE {$db_prefix}ban_items (
  id_ban MEDIUMINT UNSIGNED AUTO_INCREMENT,
  id_ban_group SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  ip_low VARBINARY(16),
  ip_high VARBINARY(16),
  hostname VARCHAR(255) NOT NULL DEFAULT '',
  email_address VARCHAR(255) NOT NULL DEFAULT '',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  hits MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_ban),
  INDEX idx_id_ban_group (id_ban_group),
  INDEX idx_id_ban_ip (ip_low,ip_high)
) ENGINE={$engine};

#
# Table structure for table `board_permissions`
#

CREATE TABLE {$db_prefix}board_permissions (
  id_group SMALLINT DEFAULT '0',
  id_profile SMALLINT UNSIGNED DEFAULT '0',
  permission VARCHAR(30) DEFAULT '',
  add_deny TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (id_group, id_profile, permission)
) ENGINE={$engine};

#
# Table structure for table `boards`
#

CREATE TABLE {$db_prefix}boards (
  id_board SMALLINT UNSIGNED AUTO_INCREMENT,
  id_cat TINYINT UNSIGNED NOT NULL DEFAULT '0',
  child_level TINYINT UNSIGNED NOT NULL DEFAULT '0',
  id_parent SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  board_order SMALLINT NOT NULL DEFAULT '0',
  id_last_msg INT UNSIGNED NOT NULL DEFAULT '0',
  id_msg_updated INT UNSIGNED NOT NULL DEFAULT '0',
  member_groups VARCHAR(255) NOT NULL DEFAULT '-1,0',
  id_profile SMALLINT UNSIGNED NOT NULL DEFAULT '1',
  name VARCHAR(255) NOT NULL DEFAULT '',
  description TEXT NOT NULL,
  num_topics MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  num_posts MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  count_posts TINYINT NOT NULL DEFAULT '0',
  id_theme TINYINT UNSIGNED NOT NULL DEFAULT '0',
  override_theme TINYINT UNSIGNED NOT NULL DEFAULT '0',
  unapproved_posts SMALLINT NOT NULL DEFAULT '0',
  unapproved_topics SMALLINT NOT NULL DEFAULT '0',
  redirect VARCHAR(255) NOT NULL DEFAULT '',
  deny_member_groups VARCHAR(255) NOT NULL DEFAULT '',
  in_character TINYINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_board),
  UNIQUE idx_categories (id_cat, id_board),
  INDEX idx_id_parent (id_parent),
  INDEX idx_id_msg_updated (id_msg_updated),
  INDEX idx_member_groups (member_groups(48))
) ENGINE={$engine};

#
# Table structure for table `categories`
#

CREATE TABLE {$db_prefix}categories (
  id_cat TINYINT UNSIGNED AUTO_INCREMENT,
  cat_order TINYINT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL DEFAULT '',
  description TEXT NOT NULL,
  can_collapse TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (id_cat)
) ENGINE={$engine};

#
# Table structure for table `characters`
#

CREATE TABLE {$db_prefix}characters (
  id_character INT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT NOT NULL DEFAULT '0',
  character_name VARCHAR(255) NOT NULL DEFAULT '',
  avatar VARCHAR(255) NOT NULL DEFAULT '',
  signature TEXT NOT NULL,
  id_theme TINYINT NOT NULL DEFAULT '0',
  posts MEDIUMINT NOT NULL DEFAULT '0',
  age VARCHAR(255) NOT NULL DEFAULT '',
  date_created INT NOT NULL DEFAULT '0',
  last_active INT NOT NULL DEFAULT '0',
  is_main TINYINT NOT NULL DEFAULT '0',
  main_char_group SMALLINT NOT NULL DEFAULT '0',
  char_groups VARCHAR(255) NOT NULL DEFAULT '',
  char_sheet INT NOT NULL DEFAULT '0',
  retired TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_character),
  INDEX idx_id_member (id_member)
) ENGINE={$engine};

#
# Table structure for table `character_sheet_comments`
#

CREATE TABLE {$db_prefix}character_sheet_comments (
  id_comment INT UNSIGNED AUTO_INCREMENT,
  id_character INT NOT NULL DEFAULT '0',
  id_author MEDIUMINT NOT NULL DEFAULT '0',
  time_posted INT NOT NULL DEFAULT '0',
  sheet_comment TEXT NOT NULL,
  PRIMARY KEY (id_comment),
  INDEX idx_id_character_time_posted (id_character, time_posted)
) ENGINE={$engine};

#
# Table structure for table `character_sheet_templates`
#

CREATE TABLE {$db_prefix}character_sheet_templates (
  id_template SMALLINT UNSIGNED AUTO_INCREMENT,
  template_name VARCHAR(100) NOT NULL DEFAULT '',
  template TEXT NOT NULL,
  position SMALLINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_template)
) ENGINE={$engine};

#
# Table structure for table `character_sheet_versions`
#

CREATE TABLE {$db_prefix}character_sheet_versions (
  id_version INT UNSIGNED AUTO_INCREMENT,
  sheet_text MEDIUMTEXT NOT NULL,
  id_character INT NOT NULL DEFAULT '0',
  id_member MEDIUMINT NOT NULL DEFAULT '0',
  created_time INT NOT NULL DEFAULT '0',
  id_approver MEDIUMINT NOT NULL DEFAULT '0',
  approved_time INT NOT NULL DEFAULT '0',
  approval_state TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_version),
  INDEX idx_id_character_id_approver (id_character, id_approver)
) ENGINE={$engine};

#
# Table structure for table `contact_form`
#

CREATE TABLE {$db_prefix}contact_form (
  id_message MEDIUMINT AUTO_INCREMENT,
  id_member MEDIUMINT NOT NULL DEFAULT '0',
  contact_name VARCHAR(255) NOT NULL DEFAULT '',
  contact_email VARCHAR(255) NOT NULL DEFAULT '',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  message TEXT NOT NULL,
  time_received INT NOT NULL DEFAULT '0',
  status TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_message)
) ENGINE={$engine};

#
# Table structure for table `contact_form_response`
#

CREATE TABLE {$db_prefix}contact_form_response (
  id_response MEDIUMINT AUTO_INCREMENT,
  id_message MEDIUMINT NOT NULL DEFAULT '0',
  id_member MEDIUMINT NOT NULL DEFAULT '0',
  response TEXT NOT NULL,
  time_sent INT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_response),
  INDEX idx_id_message (id_message)
) ENGINE={$engine};

#
# Table structure for table `custom_fields`
#

CREATE TABLE {$db_prefix}custom_fields (
  id_field SMALLINT AUTO_INCREMENT,
  col_name VARCHAR(12) NOT NULL DEFAULT '',
  field_name VARCHAR(40) NOT NULL DEFAULT '',
  field_desc VARCHAR(255) NOT NULL DEFAULT '',
  field_type VARCHAR(8) NOT NULL DEFAULT 'text',
  field_length SMALLINT NOT NULL DEFAULT '255',
  field_options TEXT NOT NULL,
  field_order SMALLINT NOT NULL DEFAULT '0',
  mask VARCHAR(255) NOT NULL DEFAULT '',
  show_reg TINYINT NOT NULL DEFAULT '0',
  show_display TINYINT NOT NULL DEFAULT '0',
  show_mlist SMALLINT NOT NULL DEFAULT '0',
  show_profile VARCHAR(20) NOT NULL DEFAULT 'forumprofile',
  private TINYINT NOT NULL DEFAULT '0',
  active TINYINT NOT NULL DEFAULT '1',
  bbc TINYINT NOT NULL DEFAULT '0',
  can_search TINYINT NOT NULL DEFAULT '0',
  default_value VARCHAR(255) NOT NULL DEFAULT '',
  enclose TEXT NOT NULL,
  placement TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_field),
  UNIQUE idx_col_name (col_name)
) ENGINE={$engine};

#
# Table structure for table `group_moderators`
#

CREATE TABLE {$db_prefix}group_moderators (
  id_group SMALLINT UNSIGNED DEFAULT '0',
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_group, id_member)
) ENGINE={$engine};

#
# Table structure for table `log_actions`
#

CREATE TABLE {$db_prefix}log_actions (
  id_action INT UNSIGNED AUTO_INCREMENT,
  id_log TINYINT UNSIGNED NOT NULL DEFAULT '1',
  log_time INT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  ip VARBINARY(16),
  action VARCHAR(30) NOT NULL DEFAULT '',
  id_board SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  id_topic MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  extra TEXT NOT NULL,
  PRIMARY KEY (id_action),
  INDEX idx_id_log (id_log),
  INDEX idx_log_time (log_time),
  INDEX idx_id_member (id_member),
  INDEX idx_id_board (id_board),
  INDEX idx_id_msg (id_msg),
  INDEX idx_id_topic_id_log (id_topic, id_log)
) ENGINE={$engine};

#
# Table structure for table `log_activity`
#

CREATE TABLE {$db_prefix}log_activity (
  date DATE DEFAULT '1004-01-01',
  hits MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  topics SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  posts SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  chars SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  registers SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  most_on SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (date)
) ENGINE={$engine};

#
# Table structure for table `log_banned`
#

CREATE TABLE {$db_prefix}log_banned (
  id_ban_log MEDIUMINT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  ip VARBINARY(16),
  email VARCHAR(255) NOT NULL DEFAULT '',
  log_time INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_ban_log),
  INDEX idx_log_time (log_time)
) ENGINE={$engine};

#
# Table structure for table `log_boards`
#

CREATE TABLE {$db_prefix}log_boards (
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  id_board SMALLINT UNSIGNED DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_member, id_board)
) ENGINE={$engine};

#
# Table structure for table `log_comments`
#

CREATE TABLE {$db_prefix}log_comments (
  id_comment MEDIUMINT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  member_name VARCHAR(80) NOT NULL DEFAULT '',
  comment_type VARCHAR(8) NOT NULL DEFAULT 'warning',
  id_recipient MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  recipient_name VARCHAR(255) NOT NULL DEFAULT '',
  log_time INT NOT NULL DEFAULT '0',
  id_notice MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  counter TINYINT NOT NULL DEFAULT '0',
  body TEXT NOT NULL,
  PRIMARY KEY (id_comment),
  INDEX idx_id_recipient (id_recipient),
  INDEX idx_log_time (log_time),
  INDEX idx_comment_type (comment_type(8))
) ENGINE={$engine};

#
# Table structure for table `log_digest`
#

CREATE TABLE {$db_prefix}log_digest (
  id_topic MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  note_type VARCHAR(10) NOT NULL DEFAULT 'post',
  daily TINYINT UNSIGNED NOT NULL DEFAULT '0',
  exclude MEDIUMINT UNSIGNED NOT NULL DEFAULT '0'
) ENGINE={$engine};

#
# Table structure for table `log_errors`
#

CREATE TABLE {$db_prefix}log_errors (
  id_error MEDIUMINT UNSIGNED AUTO_INCREMENT,
  log_time INT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  ip VARBINARY(16),
  url TEXT NOT NULL,
  message TEXT NOT NULL,
  session VARCHAR(128) NOT NULL DEFAULT '',
  error_type CHAR(15) NOT NULL DEFAULT 'general',
  file VARCHAR(255) NOT NULL DEFAULT '',
  line MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_error),
  INDEX idx_log_time (log_time),
  INDEX idx_id_member (id_member),
  INDEX idx_ip (ip)
) ENGINE={$engine};

#
# Table structure for table `log_floodcontrol`
#

CREATE TABLE {$db_prefix}log_floodcontrol (
  ip VARBINARY(16),
  log_time INT UNSIGNED NOT NULL DEFAULT '0',
  log_type VARCHAR(8) DEFAULT 'post',
  PRIMARY KEY (ip, log_type(8))
) ENGINE={$memory};

#
# Table structure for table `log_group_requests`
#

CREATE TABLE {$db_prefix}log_group_requests (
  id_request MEDIUMINT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_group SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  time_applied INT UNSIGNED NOT NULL DEFAULT '0',
  reason TEXT NOT NULL,
  status TINYINT UNSIGNED NOT NULL DEFAULT '0',
  id_member_acted MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  member_name_acted VARCHAR(255) NOT NULL DEFAULT '',
  time_acted INT UNSIGNED NOT NULL DEFAULT '0',
  act_reason TEXT NOT NULL,
  PRIMARY KEY (id_request),
  INDEX idx_id_member (id_member, id_group)
) ENGINE={$engine};

#
# Table structure for table `log_mark_read`
#

CREATE TABLE {$db_prefix}log_mark_read (
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  id_board SMALLINT UNSIGNED DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_member, id_board)
) ENGINE={$engine};

#
# Table structure for table `log_member_notices`
#

CREATE TABLE {$db_prefix}log_member_notices (
  id_notice MEDIUMINT UNSIGNED AUTO_INCREMENT,
  subject VARCHAR(255) NOT NULL DEFAULT '',
  body TEXT NOT NULL,
  PRIMARY KEY (id_notice)
) ENGINE={$engine};

#
# Table structure for table `log_notify`
#

CREATE TABLE {$db_prefix}log_notify (
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  id_topic MEDIUMINT UNSIGNED DEFAULT '0',
  id_board SMALLINT UNSIGNED DEFAULT '0',
  sent TINYINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_member, id_topic, id_board),
  INDEX idx_id_topic (id_topic, id_member)
) ENGINE={$engine};

#
# Table structure for table `log_online`
#

CREATE TABLE {$db_prefix}log_online (
  session VARCHAR(128) DEFAULT '',
  log_time INT NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_character INT UNSIGNED NOT NULL DEFAULT '0',
  robot_name VARCHAR(20) NOT NULL DEFAULT '',
  ip VARBINARY(16),
  url VARCHAR(2048) NOT NULL DEFAULT '',
  PRIMARY KEY (session),
  INDEX idx_log_time (log_time),
  INDEX idx_id_member (id_member)
) ENGINE={$memory};

#
# Table structure for table `log_polls`
#

CREATE TABLE {$db_prefix}log_polls (
  id_poll MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_choice TINYINT UNSIGNED NOT NULL DEFAULT '0',
  INDEX idx_id_poll (id_poll, id_member, id_choice)
) ENGINE={$engine};

#
# Table structure for table `log_reported`
#

CREATE TABLE {$db_prefix}log_reported (
  id_report MEDIUMINT UNSIGNED AUTO_INCREMENT,
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  id_topic MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_board SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  membername VARCHAR(255) NOT NULL DEFAULT '',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  body MEDIUMTEXT NOT NULL,
  time_started INT NOT NULL DEFAULT '0',
  time_updated INT NOT NULL DEFAULT '0',
  num_reports MEDIUMINT NOT NULL DEFAULT '0',
  closed TINYINT NOT NULL DEFAULT '0',
  ignore_all TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_report),
  INDEX idx_id_member (id_member),
  INDEX idx_id_topic (id_topic),
  INDEX idx_closed (closed),
  INDEX idx_time_started (time_started),
  INDEX idx_id_msg (id_msg)
) ENGINE={$engine};

#
# Table structure for table `log_reported_comments`
#

CREATE TABLE {$db_prefix}log_reported_comments (
  id_comment MEDIUMINT UNSIGNED AUTO_INCREMENT,
  id_report MEDIUMINT NOT NULL DEFAULT '0',
  id_member MEDIUMINT NOT NULL,
  membername VARCHAR(255) NOT NULL DEFAULT '',
  member_ip VARBINARY(16),
  comment VARCHAR(255) NOT NULL DEFAULT '',
  time_sent INT NOT NULL,
  PRIMARY KEY (id_comment),
  INDEX idx_id_report (id_report),
  INDEX idx_id_member (id_member),
  INDEX idx_time_sent (time_sent)
) ENGINE={$engine};

#
# Table structure for table `log_scheduled_tasks`
#

CREATE TABLE {$db_prefix}log_scheduled_tasks (
  id_log MEDIUMINT AUTO_INCREMENT,
  id_task SMALLINT NOT NULL DEFAULT '0',
  time_run INT NOT NULL DEFAULT '0',
  time_taken float NOT NULL DEFAULT '0',
  PRIMARY KEY (id_log)
) ENGINE={$engine};

#
# Table structure for table `log_search_messages`
#

CREATE TABLE {$db_prefix}log_search_messages (
  id_search TINYINT UNSIGNED DEFAULT '0',
  id_msg INT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_search, id_msg)
) ENGINE={$engine};

#
# Table structure for table `log_search_results`
#

CREATE TABLE {$db_prefix}log_search_results (
  id_search TINYINT UNSIGNED DEFAULT '0',
  id_topic MEDIUMINT UNSIGNED DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  relevance SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  num_matches SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_search, id_topic)
) ENGINE={$engine};

#
# Table structure for table `log_search_subjects`
#

CREATE TABLE {$db_prefix}log_search_subjects (
  word VARCHAR(20) DEFAULT '',
  id_topic MEDIUMINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (word, id_topic),
  INDEX idx_id_topic (id_topic)
) ENGINE={$engine};

#
# Table structure for table `log_search_topics`
#

CREATE TABLE {$db_prefix}log_search_topics (
  id_search TINYINT UNSIGNED DEFAULT '0',
  id_topic MEDIUMINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_search, id_topic)
) ENGINE={$engine};

#
# Table structure for table `log_subscribed`
#

CREATE TABLE {$db_prefix}log_subscribed (
  id_sublog INT UNSIGNED AUTO_INCREMENT,
  id_subscribe MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_member INT NOT NULL DEFAULT '0',
  old_id_group SMALLINT NOT NULL DEFAULT '0',
  start_time INT NOT NULL DEFAULT '0',
  end_time INT NOT NULL DEFAULT '0',
  status TINYINT NOT NULL DEFAULT '0',
  payments_pending TINYINT NOT NULL DEFAULT '0',
  pending_details TEXT NOT NULL,
  reminder_sent TINYINT NOT NULL DEFAULT '0',
  vendor_ref VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_sublog),
  UNIQUE KEY id_subscribe (id_subscribe, id_member),
  INDEX idx_end_time (end_time),
  INDEX idx_reminder_sent (reminder_sent),
  INDEX idx_payments_pending (payments_pending),
  INDEX idx_status (status),
  INDEX idx_id_member (id_member)
) ENGINE={$engine};

#
# Table structure for table `log_topics`
#

CREATE TABLE {$db_prefix}log_topics (
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  id_topic MEDIUMINT UNSIGNED DEFAULT '0',
  id_msg INT UNSIGNED NOT NULL DEFAULT '0',
  unwatched TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_member, id_topic),
  INDEX idx_id_topic (id_topic)
) ENGINE={$engine};

#
# Table structure for table `mail_queue`
#

CREATE TABLE {$db_prefix}mail_queue (
  id_mail INT UNSIGNED AUTO_INCREMENT,
  time_sent INT NOT NULL DEFAULT '0',
  recipient VARCHAR(255) NOT NULL DEFAULT '',
  body MEDIUMTEXT NOT NULL,
  subject VARCHAR(255) NOT NULL DEFAULT '',
  headers TEXT NOT NULL,
  send_html TINYINT NOT NULL DEFAULT '0',
  priority TINYINT NOT NULL DEFAULT '1',
  private TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_mail),
  INDEX idx_time_sent (time_sent),
  INDEX idx_mail_priority (priority, id_mail)
) ENGINE={$engine};

#
# Table structure for table `membergroups`
#

CREATE TABLE {$db_prefix}membergroups (
  id_group SMALLINT UNSIGNED AUTO_INCREMENT,
  group_name VARCHAR(80) NOT NULL DEFAULT '',
  description TEXT NOT NULL,
  online_color VARCHAR(20) NOT NULL DEFAULT '',
  min_posts MEDIUMINT NOT NULL DEFAULT '-1',
  max_messages SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  icons VARCHAR(255) NOT NULL DEFAULT '',
  group_type TINYINT NOT NULL DEFAULT '0',
  hidden TINYINT NOT NULL DEFAULT '0',
  id_parent SMALLINT NOT NULL DEFAULT '-2',
  tfa_required TINYINT NOT NULL DEFAULT '0',
  is_character TINYINT NOT NULL DEFAULT '0',
  badge_order SMALLINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_group),
  INDEX idx_min_posts (min_posts)
) ENGINE={$engine};

#
# Table structure for table `members`
#

CREATE TABLE {$db_prefix}members (
  id_member MEDIUMINT UNSIGNED AUTO_INCREMENT,
  member_name VARCHAR(80) NOT NULL DEFAULT '',
  date_registered INT UNSIGNED NOT NULL DEFAULT '0',
  posts MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_group SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  current_character INT UNSIGNED NOT NULL DEFAULT '0',
  immersive_mode INT UNSIGNED NOT NULL DEFAULT '0',
  lngfile VARCHAR(255) NOT NULL DEFAULT '',
  last_login INT UNSIGNED NOT NULL DEFAULT '0',
  real_name VARCHAR(255) NOT NULL DEFAULT '',
  instant_messages SMALLINT NOT NULL DEFAULT 0,
  unread_messages SMALLINT NOT NULL DEFAULT 0,
  new_pm TINYINT UNSIGNED NOT NULL DEFAULT '0',
  alerts INT UNSIGNED NOT NULL DEFAULT '0',
  buddy_list TEXT NOT NULL,
  pm_ignore_list VARCHAR(255) NOT NULL DEFAULT '',
  pm_prefs MEDIUMINT NOT NULL DEFAULT '0',
  mod_prefs VARCHAR(20) NOT NULL DEFAULT '',
  passwd VARCHAR(64) NOT NULL DEFAULT '',
  email_address VARCHAR(255) NOT NULL DEFAULT '',
  birthdate date NOT NULL DEFAULT '1004-01-01',
  birthday_visibility TINYINT UNSIGNED NOT NULL DEFAULT '0',
  website_title VARCHAR(255) NOT NULL DEFAULT '',
  website_url VARCHAR(255) NOT NULL DEFAULT '',
  show_online TINYINT NOT NULL DEFAULT '1',
  time_format VARCHAR(80) NOT NULL DEFAULT '',
  signature TEXT NOT NULL,
  time_offset float NOT NULL DEFAULT '0',
  avatar VARCHAR(255) NOT NULL DEFAULT '',
  member_ip VARBINARY(16),
  member_ip2 VARBINARY(16),
  secret_question VARCHAR(255) NOT NULL DEFAULT '',
  secret_answer VARCHAR(64) NOT NULL DEFAULT '',
  id_theme TINYINT UNSIGNED NOT NULL DEFAULT '0',
  is_activated TINYINT UNSIGNED NOT NULL DEFAULT '1',
  validation_code VARCHAR(10) NOT NULL DEFAULT '',
  id_msg_last_visit INT UNSIGNED NOT NULL DEFAULT '0',
  additional_groups VARCHAR(255) NOT NULL DEFAULT '',
  id_post_group SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  total_time_logged_in INT UNSIGNED NOT NULL DEFAULT '0',
  password_salt VARCHAR(255) NOT NULL DEFAULT '',
  ignore_boards TEXT NOT NULL,
  warning TINYINT NOT NULL DEFAULT '0',
  passwd_flood VARCHAR(12) NOT NULL DEFAULT '',
  pm_receive_from TINYINT UNSIGNED NOT NULL DEFAULT '1',
  timezone VARCHAR(80) NOT NULL DEFAULT 'UTC',
  tfa_secret VARCHAR(24) NOT NULL DEFAULT '',
  tfa_backup VARCHAR(64) NOT NULL DEFAULT '',
  policy_acceptance TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_member),
  INDEX idx_member_name (member_name),
  INDEX idx_real_name (real_name(80)),
  INDEX idx_email_address (email_address(30)),
  INDEX idx_date_registered (date_registered),
  INDEX idx_id_group (id_group),
  INDEX idx_birthdate (birthdate),
  INDEX idx_posts (posts),
  INDEX idx_last_login (last_login),
  INDEX idx_lngfile (lngfile(30)),
  INDEX idx_id_post_group (id_post_group),
  INDEX idx_warning (warning),
  INDEX idx_total_time_logged_in (total_time_logged_in),
  INDEX idx_id_theme (id_theme)
) ENGINE={$engine};

#
# Table structure for table `member_logins`
#

CREATE TABLE {$db_prefix}member_logins (
  id_login INT AUTO_INCREMENT,
  id_member MEDIUMINT NOT NULL DEFAULT '0',
  time INT NOT NULL DEFAULT '0',
  ip VARBINARY(16),
  ip2 VARBINARY(16),
  PRIMARY KEY (id_login),
  INDEX idx_id_member (id_member),
  INDEX idx_time (time)
) ENGINE={$engine};

#
# Table structure for table `message_icons`
#

CREATE TABLE {$db_prefix}message_icons (
  id_icon SMALLINT UNSIGNED AUTO_INCREMENT,
  title VARCHAR(80) NOT NULL DEFAULT '',
  filename VARCHAR(80) NOT NULL DEFAULT '',
  id_board SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  icon_order SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_icon),
  INDEX idx_id_board (id_board)
) ENGINE={$engine};

#
# Table structure for table `messages`
#

CREATE TABLE {$db_prefix}messages (
  id_msg INT UNSIGNED AUTO_INCREMENT,
  id_topic MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_board SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  poster_time INT UNSIGNED NOT NULL DEFAULT '0',
  id_creator MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_character INT UNSIGNED NOT NULL DEFAULT '0',
  id_msg_modified INT UNSIGNED NOT NULL DEFAULT '0',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  poster_name VARCHAR(255) NOT NULL DEFAULT '',
  poster_email VARCHAR(255) NOT NULL DEFAULT '',
  poster_ip VARBINARY(16),
  smileys_enabled TINYINT NOT NULL DEFAULT '1',
  modified_time INT UNSIGNED NOT NULL DEFAULT '0',
  modified_name VARCHAR(255) NOT NULL DEFAULT '',
  modified_reason VARCHAR(255) NOT NULL DEFAULT '',
  body TEXT NOT NULL,
  icon VARCHAR(16) NOT NULL DEFAULT 'xx',
  approved TINYINT NOT NULL DEFAULT '1',
  likes SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_msg),
  UNIQUE idx_id_board (id_board, id_msg),
  UNIQUE idx_id_member (id_member, id_msg),
  INDEX idx_approved (approved),
  INDEX idx_ip_index (poster_ip, id_topic),
  INDEX idx_participation (id_member, id_topic),
  INDEX idx_show_posts (id_member, id_board),
  INDEX idx_id_member_msg (id_member, approved, id_msg),
  INDEX idx_current_topic (id_topic, id_msg, id_member, approved),
  INDEX idx_related_ip (id_member, poster_ip, id_msg),
  INDEX idx_likes (likes)
) ENGINE={$engine};

#
# Table structure for table `moderators`
#

CREATE TABLE {$db_prefix}moderators (
  id_board SMALLINT UNSIGNED DEFAULT '0',
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_board, id_member)
) ENGINE={$engine};

#
# Table structure for table `moderator_groups`
#

CREATE TABLE {$db_prefix}moderator_groups (
  id_board SMALLINT UNSIGNED DEFAULT '0',
  id_group SMALLINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_board, id_group)
) ENGINE={$engine};

#
# Table structure for table `permission_profiles`
#

CREATE TABLE {$db_prefix}permission_profiles (
  id_profile SMALLINT AUTO_INCREMENT,
  profile_name VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_profile)
) ENGINE={$engine};

#
# Table structure for table `permissions`
#

CREATE TABLE {$db_prefix}permissions (
  id_group SMALLINT DEFAULT '0',
  permission VARCHAR(30) DEFAULT '',
  add_deny TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (id_group, permission)
) ENGINE={$engine};

#
# Table structure for table `personal_messages`
#

CREATE TABLE {$db_prefix}personal_messages (
  id_pm INT UNSIGNED AUTO_INCREMENT,
  id_pm_head INT UNSIGNED NOT NULL DEFAULT '0',
  id_member_from MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  deleted_by_sender TINYINT UNSIGNED NOT NULL DEFAULT '0',
  from_name VARCHAR(255) NOT NULL DEFAULT '',
  msgtime INT UNSIGNED NOT NULL DEFAULT '0',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  body TEXT NOT NULL,
  PRIMARY KEY (id_pm),
  INDEX idx_id_member (id_member_from, deleted_by_sender),
  INDEX idx_msgtime (msgtime),
  INDEX idx_id_pm_head (id_pm_head)
) ENGINE={$engine};

#
# Table structure for table `pm_labels`
#
CREATE TABLE {$db_prefix}pm_labels (
  id_label INT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  name VARCHAR(30) NOT NULL DEFAULT '',
  PRIMARY KEY (id_label)
) ENGINE={$engine};

#
# Table structure for table `pm_labeled_messages`
#
CREATE TABLE {$db_prefix}pm_labeled_messages (
  id_label INT UNSIGNED DEFAULT '0',
  id_pm INT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_label, id_pm)
) ENGINE={$engine};

#
# Table structure for table `pm_recipients`
#

CREATE TABLE {$db_prefix}pm_recipients (
  id_pm INT UNSIGNED DEFAULT '0',
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  bcc TINYINT UNSIGNED NOT NULL DEFAULT '0',
  is_read TINYINT UNSIGNED NOT NULL DEFAULT '0',
  is_new TINYINT UNSIGNED NOT NULL DEFAULT '0',
  deleted TINYINT UNSIGNED NOT NULL DEFAULT '0',
  in_inbox TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (id_pm, id_member),
  UNIQUE idx_id_member (id_member, deleted, id_pm)
) ENGINE={$engine};

#
# Table structure for table `pm_rules`
#

CREATE TABLE {$db_prefix}pm_rules (
  id_rule INT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  rule_name VARCHAR(60) NOT NULL,
  criteria TEXT NOT NULL,
  actions TEXT NOT NULL,
  delete_pm TINYINT UNSIGNED NOT NULL DEFAULT '0',
  is_or TINYINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_rule),
  INDEX idx_id_member (id_member),
  INDEX idx_delete_pm (delete_pm)
) ENGINE={$engine};

#
# Table structure for table `policy`
#

CREATE TABLE {$db_prefix}policy (
  id_policy SMALLINT UNSIGNED AUTO_INCREMENT,
  policy_type TINYINT UNSIGNED NOT NULL DEFAULT '0',
  language VARCHAR(20) NOT NULL DEFAULT '',
  title VARCHAR(100) NOT NULL DEFAULT '',
  description VARCHAR(200) NOT NULL DEFAULT '',
  last_revision INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_policy)
) ENGINE={$engine};

#
# Table structure for table `policy_acceptance`
#

CREATE TABLE {$db_prefix}policy_acceptance (
  id_policy SMALLINT UNSIGNED AUTO_INCREMENT,
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_revision INT UNSIGNED NOT NULL DEFAULT '0',
  acceptance_time INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_policy, id_member, id_revision)
) ENGINE={$engine};

#
# Table structure for table `policy_revision`
#

CREATE TABLE {$db_prefix}policy_revision (
  id_revision INT UNSIGNED AUTO_INCREMENT,
  id_policy SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  last_change INT UNSIGNED NOT NULL DEFAULT '0',
  short_revision_note TEXT NOT NULL,
  revision_text TEXT NOT NULL,
  edit_id_member INT UNSIGNED NOT NULL DEFAULT '0',
  edit_member_name VARCHAR(50) NOT NULL DEFAULT '',
  PRIMARY KEY (id_revision),
  INDEX idx_id_policy (id_policy)
) ENGINE={$engine};

#
# Table structure for table `policy_types`
#

CREATE TABLE {$db_prefix}policy_types (
  id_policy_type TINYINT UNSIGNED AUTO_INCREMENT,
  policy_type VARCHAR(50) NOT NULL,
  require_acceptance TINYINT UNSIGNED DEFAULT '0',
  show_footer TINYINT UNSIGNED DEFAULT '0',
  show_reg TINYINT UNSIGNED DEFAULT '0',
  show_help TINYINT UNSIGNED DEFAULT '0',
  PRIMARY KEY (id_policy_type)
) ENGINE={$engine};

#
# Table structure for table `polls`
#

CREATE TABLE {$db_prefix}polls (
  id_poll MEDIUMINT UNSIGNED AUTO_INCREMENT,
  question VARCHAR(255) NOT NULL DEFAULT '',
  voting_locked TINYINT NOT NULL DEFAULT '0',
  max_votes TINYINT UNSIGNED NOT NULL DEFAULT '1',
  expire_time INT UNSIGNED NOT NULL DEFAULT '0',
  hide_results TINYINT UNSIGNED NOT NULL DEFAULT '0',
  change_vote TINYINT UNSIGNED NOT NULL DEFAULT '0',
  guest_vote TINYINT UNSIGNED NOT NULL DEFAULT '0',
  num_guest_voters INT UNSIGNED NOT NULL DEFAULT '0',
  reset_poll INT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  poster_name VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_poll)
) ENGINE={$engine};

#
# Table structure for table `poll_choices`
#

CREATE TABLE {$db_prefix}poll_choices (
  id_poll MEDIUMINT UNSIGNED DEFAULT '0',
  id_choice TINYINT UNSIGNED DEFAULT '0',
  label VARCHAR(255) NOT NULL DEFAULT '',
  votes SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_poll, id_choice)
) ENGINE={$engine};

#
# Table structure for table `qanda`
#

CREATE TABLE {$db_prefix}qanda (
  id_question SMALLINT UNSIGNED AUTO_INCREMENT,
  lngfile VARCHAR(255) NOT NULL DEFAULT '',
  question VARCHAR(255) NOT NULL DEFAULT '',
  answers TEXT NOT NULL,
  PRIMARY KEY (id_question),
  INDEX idx_lngfile (lngfile(30))
) ENGINE={$engine};

#
# Table structure for table `scheduled_tasks`
#

CREATE TABLE {$db_prefix}scheduled_tasks (
  id_task SMALLINT AUTO_INCREMENT,
  next_time INT NOT NULL DEFAULT '0',
  time_offset INT NOT NULL DEFAULT '0',
  time_regularity SMALLINT NOT NULL DEFAULT '0',
  time_unit VARCHAR(1) NOT NULL DEFAULT 'h',
  disabled TINYINT NOT NULL DEFAULT '0',
  task VARCHAR(24) NOT NULL DEFAULT '',
  callable VARCHAR(60) NOT NULL DEFAULT '',
  PRIMARY KEY (id_task),
  INDEX idx_next_time (next_time),
  INDEX idx_disabled (disabled),
  UNIQUE idx_task (task)
) ENGINE={$engine};

#
# Table structure for table `settings`
#

CREATE TABLE {$db_prefix}settings (
  variable VARCHAR(255) DEFAULT '',
  value TEXT NOT NULL,
  PRIMARY KEY (variable(30))
) ENGINE={$engine};

#
# Table structure for table `sessions`
#

CREATE TABLE {$db_prefix}sessions (
  session_id VARCHAR(128) NOT NULL DEFAULT '',
  last_update INT UNSIGNED NOT NULL DEFAULT '0',
  data TEXT NOT NULL,
  PRIMARY KEY (session_id)
) ENGINE={$engine};

#
# Table structure for table `smileys`
#

CREATE TABLE {$db_prefix}smileys (
  id_smiley SMALLINT UNSIGNED AUTO_INCREMENT,
  code VARCHAR(30) NOT NULL DEFAULT '',
  filename VARCHAR(48) NOT NULL DEFAULT '',
  description VARCHAR(80) NOT NULL DEFAULT '',
  smiley_row TINYINT UNSIGNED NOT NULL DEFAULT '0',
  smiley_order SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  hidden TINYINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id_smiley)
) ENGINE={$engine};

#
# Table structure for table `subscriptions`
#

CREATE TABLE {$db_prefix}subscriptions(
  id_subscribe MEDIUMINT UNSIGNED AUTO_INCREMENT,
  name VARCHAR(60) NOT NULL DEFAULT '',
  description VARCHAR(255) NOT NULL DEFAULT '',
  cost TEXT NOT NULL,
  length VARCHAR(6) NOT NULL DEFAULT '',
  id_group SMALLINT NOT NULL DEFAULT '0',
  add_groups VARCHAR(40) NOT NULL DEFAULT '',
  active TINYINT NOT NULL DEFAULT '1',
  repeatable TINYINT NOT NULL DEFAULT '0',
  allow_partial TINYINT NOT NULL DEFAULT '0',
  reminder TINYINT NOT NULL DEFAULT '0',
  email_complete TEXT NOT NULL,
  PRIMARY KEY (id_subscribe),
  INDEX idx_active (active)
) ENGINE={$engine};

#
# Table structure for table `themes`
#

CREATE TABLE {$db_prefix}themes (
  id_member MEDIUMINT DEFAULT '0',
  id_theme TINYINT UNSIGNED DEFAULT '1',
  variable VARCHAR(255) DEFAULT '',
  value TEXT NOT NULL,
  PRIMARY KEY (id_theme, id_member, variable(30)),
  INDEX idx_id_member (id_member)
) ENGINE={$engine};

#
# Table structure for table `topics`
#

CREATE TABLE {$db_prefix}topics (
  id_topic MEDIUMINT UNSIGNED AUTO_INCREMENT,
  is_sticky TINYINT NOT NULL DEFAULT '0',
  id_board SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  id_first_msg INT UNSIGNED NOT NULL DEFAULT '0',
  id_last_msg INT UNSIGNED NOT NULL DEFAULT '0',
  id_member_started MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_member_updated MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_poll MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_previous_board SMALLINT NOT NULL DEFAULT '0',
  id_previous_topic MEDIUMINT NOT NULL DEFAULT '0',
  num_replies INT UNSIGNED NOT NULL DEFAULT '0',
  num_views INT UNSIGNED NOT NULL DEFAULT '0',
  locked TINYINT NOT NULL DEFAULT '0',
  redirect_expires INT UNSIGNED NOT NULL DEFAULT '0',
  id_redirect_topic MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  unapproved_posts SMALLINT NOT NULL DEFAULT '0',
  approved TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (id_topic),
  UNIQUE idx_last_message (id_last_msg, id_board),
  UNIQUE idx_first_message (id_first_msg, id_board),
  UNIQUE idx_poll (id_poll, id_topic),
  INDEX idx_is_sticky (is_sticky),
  INDEX idx_approved (approved),
  INDEX idx_member_started (id_member_started, id_board),
  INDEX idx_last_message_sticky (id_board, is_sticky, id_last_msg),
  INDEX idx_board_news (id_board, id_first_msg)
) ENGINE={$engine};

#
# Table structure for table `user_alerts`
#

CREATE TABLE {$db_prefix}user_alerts (
  id_alert INT UNSIGNED AUTO_INCREMENT,
  alert_time INT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_member_started MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  member_name VARCHAR(255) NOT NULL DEFAULT '',
  content_type VARCHAR(255) NOT NULL DEFAULT '',
  content_id INT UNSIGNED NOT NULL DEFAULT '0',
  content_action VARCHAR(255) NOT NULL DEFAULT '',
  is_read INT UNSIGNED NOT NULL DEFAULT '0',
  extra TEXT NOT NULL,
  PRIMARY KEY (id_alert),
  INDEX idx_id_member (id_member),
  INDEX idx_alert_time (alert_time)
) ENGINE={$engine};

#
# Table structure for table `user_alerts_prefs`
#

CREATE TABLE {$db_prefix}user_alerts_prefs (
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  alert_pref VARCHAR(32) DEFAULT '',
  alert_value TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_member, alert_pref)
) ENGINE={$engine};

#
# Table structure for table `user_drafts`
#

CREATE TABLE {$db_prefix}user_drafts (
  id_draft INT UNSIGNED AUTO_INCREMENT,
  id_topic MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  id_board SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  id_reply INT UNSIGNED NOT NULL DEFAULT '0',
  type TINYINT NOT NULL DEFAULT '0',
  poster_time INT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  smileys_enabled TINYINT NOT NULL DEFAULT '1',
  body MEDIUMTEXT NOT NULL,
  icon VARCHAR(16) NOT NULL DEFAULT 'xx',
  locked TINYINT NOT NULL DEFAULT '0',
  is_sticky TINYINT NOT NULL DEFAULT '0',
  to_list VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_draft),
  UNIQUE idx_id_member (id_member, id_draft, type)
) ENGINE={$engine};

#
# Table structure for table `user_exports`
#

CREATE TABLE {$db_prefix}user_exports (
  id_export INT NOT NULL AUTO_INCREMENT,
  id_attach INT NOT NULL DEFAULT 0,
  id_member MEDIUMINT NOT NULL DEFAULT 0,
  id_requester MEDIUMINT NOT NULL DEFAULT 0,
  requested_on INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id_export),
  INDEX (id_member)
) ENGINE={$engine};

#
# Table structure for table `user_likes`
#

CREATE TABLE {$db_prefix}user_likes (
  id_member MEDIUMINT UNSIGNED DEFAULT '0',
  content_type CHAR(6) DEFAULT '',
  content_id INT UNSIGNED DEFAULT '0',
  like_time INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (content_id, content_type, id_member),
  INDEX content (content_id, content_type),
  INDEX liker (id_member)
) ENGINE={$engine};

#
# Table structure for table `mentions`
#
CREATE TABLE {$db_prefix}mentions (
  content_id INT DEFAULT '0',
  content_type VARCHAR(10) DEFAULT '',
  id_mentioned INT DEFAULT 0,
  id_character INT UNSIGNED NOT NULL DEFAULT '0',
  mentioned_chr INT UNSIGNED NOT NULL DEFAULT '0',
  id_member MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
  `time` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (content_id, content_type, id_mentioned),
  INDEX content (content_id, content_type),
  INDEX mentionee (id_member)
) ENGINE={$engine};

# Transactions for the win - only used if we have InnoDB available...
START TRANSACTION;

#
# Dumping data for table `admin_info_files`
#

INSERT INTO {$db_prefix}admin_info_files
  (id_file, filename, path, parameters, data, filetype)
VALUES
  (1, 'updates.json', '', '', '', 'application/json'),
  (2, 'versions.json', '', '', '', 'application/json');
# --------------------------------------------------------

#
# Dumping data for table `board_permissions`
#

INSERT INTO {$db_prefix}board_permissions
  (id_group, id_profile, permission)
VALUES (-1, 1, 'poll_view'),
  (0, 1, 'remove_own'),
  (0, 1, 'lock_own'),
  (0, 1, 'modify_own'),
  (0, 1, 'poll_add_own'),
  (0, 1, 'poll_edit_own'),
  (0, 1, 'poll_lock_own'),
  (0, 1, 'poll_post'),
  (0, 1, 'poll_view'),
  (0, 1, 'poll_vote'),
  (0, 1, 'post_attachment'),
  (0, 1, 'post_new'),
  (0, 1, 'post_draft'),
  (0, 1, 'post_reply_any'),
  (0, 1, 'post_reply_own'),
  (0, 1, 'post_unapproved_topics'),
  (0, 1, 'post_unapproved_replies_any'),
  (0, 1, 'post_unapproved_replies_own'),
  (0, 1, 'post_unapproved_attachments'),
  (0, 1, 'delete_own'),
  (0, 1, 'report_any'),
  (0, 1, 'view_attachments'),
  (2, 1, 'moderate_board'),
  (2, 1, 'post_new'),
  (2, 1, 'post_draft'),
  (2, 1, 'post_reply_own'),
  (2, 1, 'post_reply_any'),
  (2, 1, 'post_unapproved_topics'),
  (2, 1, 'post_unapproved_replies_any'),
  (2, 1, 'post_unapproved_replies_own'),
  (2, 1, 'post_unapproved_attachments'),
  (2, 1, 'poll_post'),
  (2, 1, 'poll_add_any'),
  (2, 1, 'poll_remove_any'),
  (2, 1, 'poll_view'),
  (2, 1, 'poll_vote'),
  (2, 1, 'poll_lock_any'),
  (2, 1, 'poll_edit_any'),
  (2, 1, 'report_any'),
  (2, 1, 'lock_own'),
  (2, 1, 'delete_own'),
  (2, 1, 'modify_own'),
  (2, 1, 'make_sticky'),
  (2, 1, 'lock_any'),
  (2, 1, 'remove_any'),
  (2, 1, 'move_any'),
  (2, 1, 'merge_any'),
  (2, 1, 'split_any'),
  (2, 1, 'delete_any'),
  (2, 1, 'modify_any'),
  (2, 1, 'approve_posts'),
  (2, 1, 'post_attachment'),
  (2, 1, 'view_attachments'),
  (3, 1, 'moderate_board'),
  (3, 1, 'post_new'),
  (3, 1, 'post_draft'),
  (3, 1, 'post_reply_own'),
  (3, 1, 'post_reply_any'),
  (3, 1, 'post_unapproved_topics'),
  (3, 1, 'post_unapproved_replies_any'),
  (3, 1, 'post_unapproved_replies_own'),
  (3, 1, 'post_unapproved_attachments'),
  (3, 1, 'poll_post'),
  (3, 1, 'poll_add_any'),
  (3, 1, 'poll_remove_any'),
  (3, 1, 'poll_view'),
  (3, 1, 'poll_vote'),
  (3, 1, 'poll_lock_any'),
  (3, 1, 'poll_edit_any'),
  (3, 1, 'report_any'),
  (3, 1, 'lock_own'),
  (3, 1, 'delete_own'),
  (3, 1, 'modify_own'),
  (3, 1, 'make_sticky'),
  (3, 1, 'lock_any'),
  (3, 1, 'remove_any'),
  (3, 1, 'move_any'),
  (3, 1, 'merge_any'),
  (3, 1, 'split_any'),
  (3, 1, 'delete_any'),
  (3, 1, 'modify_any'),
  (3, 1, 'approve_posts'),
  (3, 1, 'post_attachment'),
  (3, 1, 'view_attachments'),
  (-1, 2, 'poll_view'),
  (0, 2, 'remove_own'),
  (0, 2, 'lock_own'),
  (0, 2, 'modify_own'),
  (0, 2, 'poll_view'),
  (0, 2, 'poll_vote'),
  (0, 2, 'post_attachment'),
  (0, 2, 'post_new'),
  (0, 2, 'post_draft'),
  (0, 2, 'post_reply_any'),
  (0, 2, 'post_reply_own'),
  (0, 2, 'post_unapproved_topics'),
  (0, 2, 'post_unapproved_replies_any'),
  (0, 2, 'post_unapproved_replies_own'),
  (0, 2, 'post_unapproved_attachments'),
  (0, 2, 'delete_own'),
  (0, 2, 'report_any'),
  (0, 2, 'view_attachments'),
  (2, 2, 'moderate_board'),
  (2, 2, 'post_new'),
  (2, 2, 'post_draft'),
  (2, 2, 'post_reply_own'),
  (2, 2, 'post_reply_any'),
  (2, 2, 'post_unapproved_topics'),
  (2, 2, 'post_unapproved_replies_any'),
  (2, 2, 'post_unapproved_replies_own'),
  (2, 2, 'post_unapproved_attachments'),
  (2, 2, 'poll_post'),
  (2, 2, 'poll_add_any'),
  (2, 2, 'poll_remove_any'),
  (2, 2, 'poll_view'),
  (2, 2, 'poll_vote'),
  (2, 2, 'poll_lock_any'),
  (2, 2, 'poll_edit_any'),
  (2, 2, 'report_any'),
  (2, 2, 'lock_own'),
  (2, 2, 'delete_own'),
  (2, 2, 'modify_own'),
  (2, 2, 'make_sticky'),
  (2, 2, 'lock_any'),
  (2, 2, 'remove_any'),
  (2, 2, 'move_any'),
  (2, 2, 'merge_any'),
  (2, 2, 'split_any'),
  (2, 2, 'delete_any'),
  (2, 2, 'modify_any'),
  (2, 2, 'approve_posts'),
  (2, 2, 'post_attachment'),
  (2, 2, 'view_attachments'),
  (3, 2, 'moderate_board'),
  (3, 2, 'post_new'),
  (3, 2, 'post_draft'),
  (3, 2, 'post_reply_own'),
  (3, 2, 'post_reply_any'),
  (3, 2, 'post_unapproved_topics'),
  (3, 2, 'post_unapproved_replies_any'),
  (3, 2, 'post_unapproved_replies_own'),
  (3, 2, 'post_unapproved_attachments'),
  (3, 2, 'poll_post'),
  (3, 2, 'poll_add_any'),
  (3, 2, 'poll_remove_any'),
  (3, 2, 'poll_view'),
  (3, 2, 'poll_vote'),
  (3, 2, 'poll_lock_any'),
  (3, 2, 'poll_edit_any'),
  (3, 2, 'report_any'),
  (3, 2, 'lock_own'),
  (3, 2, 'delete_own'),
  (3, 2, 'modify_own'),
  (3, 2, 'make_sticky'),
  (3, 2, 'lock_any'),
  (3, 2, 'remove_any'),
  (3, 2, 'move_any'),
  (3, 2, 'merge_any'),
  (3, 2, 'split_any'),
  (3, 2, 'delete_any'),
  (3, 2, 'modify_any'),
  (3, 2, 'approve_posts'),
  (3, 2, 'post_attachment'),
  (3, 2, 'view_attachments'),
  (-1, 3, 'poll_view'),
  (0, 3, 'remove_own'),
  (0, 3, 'lock_own'),
  (0, 3, 'modify_own'),
  (0, 3, 'poll_view'),
  (0, 3, 'poll_vote'),
  (0, 3, 'post_attachment'),
  (0, 3, 'post_reply_any'),
  (0, 3, 'post_reply_own'),
  (0, 3, 'post_unapproved_replies_any'),
  (0, 3, 'post_unapproved_replies_own'),
  (0, 3, 'post_unapproved_attachments'),
  (0, 3, 'delete_own'),
  (0, 3, 'report_any'),
  (0, 3, 'view_attachments'),
  (2, 3, 'moderate_board'),
  (2, 3, 'post_new'),
  (2, 3, 'post_draft'),
  (2, 3, 'post_reply_own'),
  (2, 3, 'post_reply_any'),
  (2, 3, 'post_unapproved_topics'),
  (2, 3, 'post_unapproved_replies_any'),
  (2, 3, 'post_unapproved_replies_own'),
  (2, 3, 'post_unapproved_attachments'),
  (2, 3, 'poll_post'),
  (2, 3, 'poll_add_any'),
  (2, 3, 'poll_remove_any'),
  (2, 3, 'poll_view'),
  (2, 3, 'poll_vote'),
  (2, 3, 'poll_lock_any'),
  (2, 3, 'poll_edit_any'),
  (2, 3, 'report_any'),
  (2, 3, 'lock_own'),
  (2, 3, 'delete_own'),
  (2, 3, 'modify_own'),
  (2, 3, 'make_sticky'),
  (2, 3, 'lock_any'),
  (2, 3, 'remove_any'),
  (2, 3, 'move_any'),
  (2, 3, 'merge_any'),
  (2, 3, 'split_any'),
  (2, 3, 'delete_any'),
  (2, 3, 'modify_any'),
  (2, 3, 'approve_posts'),
  (2, 3, 'post_attachment'),
  (2, 3, 'view_attachments'),
  (3, 3, 'moderate_board'),
  (3, 3, 'post_new'),
  (3, 3, 'post_draft'),
  (3, 3, 'post_reply_own'),
  (3, 3, 'post_reply_any'),
  (3, 3, 'post_unapproved_topics'),
  (3, 3, 'post_unapproved_replies_any'),
  (3, 3, 'post_unapproved_replies_own'),
  (3, 3, 'post_unapproved_attachments'),
  (3, 3, 'poll_post'),
  (3, 3, 'poll_add_any'),
  (3, 3, 'poll_remove_any'),
  (3, 3, 'poll_view'),
  (3, 3, 'poll_vote'),
  (3, 3, 'poll_lock_any'),
  (3, 3, 'poll_edit_any'),
  (3, 3, 'report_any'),
  (3, 3, 'lock_own'),
  (3, 3, 'delete_own'),
  (3, 3, 'modify_own'),
  (3, 3, 'make_sticky'),
  (3, 3, 'lock_any'),
  (3, 3, 'remove_any'),
  (3, 3, 'move_any'),
  (3, 3, 'merge_any'),
  (3, 3, 'split_any'),
  (3, 3, 'delete_any'),
  (3, 3, 'modify_any'),
  (3, 3, 'approve_posts'),
  (3, 3, 'post_attachment'),
  (3, 3, 'view_attachments'),
  (-1, 4, 'poll_view'),
  (0, 4, 'poll_view'),
  (0, 4, 'poll_vote'),
  (0, 4, 'report_any'),
  (0, 4, 'view_attachments'),
  (2, 4, 'moderate_board'),
  (2, 4, 'post_new'),
  (2, 4, 'post_draft'),
  (2, 4, 'post_reply_own'),
  (2, 4, 'post_reply_any'),
  (2, 4, 'post_unapproved_topics'),
  (2, 4, 'post_unapproved_replies_any'),
  (2, 4, 'post_unapproved_replies_own'),
  (2, 4, 'post_unapproved_attachments'),
  (2, 4, 'poll_post'),
  (2, 4, 'poll_add_any'),
  (2, 4, 'poll_remove_any'),
  (2, 4, 'poll_view'),
  (2, 4, 'poll_vote'),
  (2, 4, 'poll_lock_any'),
  (2, 4, 'poll_edit_any'),
  (2, 4, 'report_any'),
  (2, 4, 'lock_own'),
  (2, 4, 'delete_own'),
  (2, 4, 'modify_own'),
  (2, 4, 'make_sticky'),
  (2, 4, 'lock_any'),
  (2, 4, 'remove_any'),
  (2, 4, 'move_any'),
  (2, 4, 'merge_any'),
  (2, 4, 'split_any'),
  (2, 4, 'delete_any'),
  (2, 4, 'modify_any'),
  (2, 4, 'approve_posts'),
  (2, 4, 'post_attachment'),
  (2, 4, 'view_attachments'),
  (3, 4, 'moderate_board'),
  (3, 4, 'post_new'),
  (3, 4, 'post_draft'),
  (3, 4, 'post_reply_own'),
  (3, 4, 'post_reply_any'),
  (3, 4, 'post_unapproved_topics'),
  (3, 4, 'post_unapproved_replies_any'),
  (3, 4, 'post_unapproved_replies_own'),
  (3, 4, 'post_unapproved_attachments'),
  (3, 4, 'poll_post'),
  (3, 4, 'poll_add_any'),
  (3, 4, 'poll_remove_any'),
  (3, 4, 'poll_view'),
  (3, 4, 'poll_vote'),
  (3, 4, 'poll_lock_any'),
  (3, 4, 'poll_edit_any'),
  (3, 4, 'report_any'),
  (3, 4, 'lock_own'),
  (3, 4, 'delete_own'),
  (3, 4, 'modify_own'),
  (3, 4, 'make_sticky'),
  (3, 4, 'lock_any'),
  (3, 4, 'remove_any'),
  (3, 4, 'move_any'),
  (3, 4, 'merge_any'),
  (3, 4, 'split_any'),
  (3, 4, 'delete_any'),
  (3, 4, 'modify_any'),
  (3, 4, 'approve_posts'),
  (3, 4, 'post_attachment'),
  (3, 4, 'view_attachments');
# --------------------------------------------------------

#
# Dumping data for table `boards`
#

INSERT INTO {$db_prefix}boards
  (id_board, id_cat, board_order, id_last_msg, id_msg_updated, name, description, num_topics, num_posts, member_groups)
VALUES (1, 1, 1, 1, 1, '{$default_board_name}', '{$default_board_description}', 1, 1, '-1,0,2');
# --------------------------------------------------------

#
# Dumping data for table `categories`
#

INSERT INTO {$db_prefix}categories
VALUES (1, 0, '{$default_category_name}', '', 1);
# --------------------------------------------------------

#
# Dumping data for table `custom_fields`
#

INSERT INTO {$db_prefix}custom_fields
  (`col_name`, `field_name`, `field_desc`, `field_type`, `field_length`, `field_options`, `field_order`, `mask`, `show_reg`, `show_display`, `show_mlist`, `show_profile`, `private`, `active`, `bbc`, `can_search`, `default_value`, `enclose`, `placement`)
VALUES ('cust_skype', 'Skype', 'Your Skype name', 'text', 32, '', 1, 'nohtml', 0, 1, 0, 'forumprofile', 0, 1, 0, 0, '', '<a href="skype:{INPUT}?call"><img src="{DEFAULT_IMAGES_URL}/skype.png" alt="{INPUT}" title="{INPUT}" /></a> ', 1),
  ('cust_loca', 'Location', 'Geographic location.', 'text', 50, '', 2, 'nohtml', 0, 1, 0, 'forumprofile', 0, 1, 0, 0, '', '', 0);

# --------------------------------------------------------

#
# Dumping data for table `membergroups`
#

INSERT INTO {$db_prefix}membergroups
  (id_group, group_name, description, online_color, min_posts, icons, group_type)
VALUES (1, '{$default_administrator_group}', '', '#FF0000', -1, '5#iconadmin.png', 1),
  (2, '{$default_global_moderator_group}', '', '#0000FF', -1, '5#icongmod.png', 0),
  (3, '{$default_moderator_group}', '', '', -1, '5#iconmod.png', 0),
  (4, '{$default_newbie_group}', '', '', 0, '1#icon.png', 0),
  (5, '{$default_junior_group}', '', '', 50, '2#icon.png', 0),
  (6, '{$default_full_group}', '', '', 100, '3#icon.png', 0),
  (7, '{$default_senior_group}', '', '', 250, '4#icon.png', 0),
  (8, '{$default_hero_group}', '', '', 500, '5#icon.png', 0);
# --------------------------------------------------------

#
# Dumping data for table `message_icons`
#

# // @todo i18n
INSERT INTO {$db_prefix}message_icons
  (filename, title, icon_order)
VALUES ('xx', 'Standard', '0'),
  ('thumbup', 'Thumb Up', '1'),
  ('thumbdown', 'Thumb Down', '2'),
  ('exclamation', 'Exclamation point', '3'),
  ('question', 'Question mark', '4'),
  ('lamp', 'Lamp', '5'),
  ('smiley', 'Smiley', '6'),
  ('angry', 'Angry', '7'),
  ('cheesy', 'Cheesy', '8'),
  ('grin', 'Grin', '9'),
  ('sad', 'Sad', '10'),
  ('wink', 'Wink', '11'),
  ('poll', 'Poll', '12');
# --------------------------------------------------------

#
# Dumping data for table `messages`
#

INSERT INTO {$db_prefix}messages
  (id_msg, id_msg_modified, id_topic, id_board, poster_time, subject, poster_name, poster_email, modified_name, body, icon)
VALUES (1, 1, 1, 1, UNIX_TIMESTAMP(), '{$default_topic_subject}', 'StoryBB', 'info@storybb.org', '', '{$default_topic_message}', 'xx');
# --------------------------------------------------------

#
# Dumping data for table `permission_profiles`
#

INSERT INTO {$db_prefix}permission_profiles
  (id_profile, profile_name)
VALUES (1, 'default'), (2, 'no_polls'), (3, 'reply_only'), (4, 'read_only');
# --------------------------------------------------------

#
# Dumping data for table `permissions`
#

INSERT INTO {$db_prefix}permissions
  (id_group, permission)
VALUES (-1, 'search_posts'),
  (-1, 'view_stats'),
  (0, 'view_mlist'),
  (0, 'search_posts'),
  (0, 'profile_view'),
  (0, 'pm_read'),
  (0, 'pm_send'),
  (0, 'pm_draft'),
  (0, 'view_stats'),
  (0, 'who_view'),
  (0, 'profile_identity_own'),
  (0, 'profile_password_own'),
  (0, 'profile_displayed_name_own'),
  (0, 'profile_signature_own'),
  (0, 'profile_website_own'),
  (0, 'profile_forum_own'),
  (0, 'profile_extra_own'),
  (0, 'profile_remove_own'),
  (0, 'profile_upload_avatar'),
  (0, 'profile_remote_avatar'),
  (0, 'mention'),
  (2, 'view_mlist'),
  (2, 'search_posts'),
  (2, 'profile_view'),
  (2, 'pm_read'),
  (2, 'pm_send'),
  (2, 'pm_draft'),
  (2, 'view_stats'),
  (2, 'who_view'),
  (2, 'profile_identity_own'),
  (2, 'profile_password_own'),
  (2, 'profile_displayed_name_own'),
  (2, 'profile_signature_own'),
  (2, 'profile_website_own'),
  (2, 'profile_forum_own'),
  (2, 'profile_extra_own'),
  (2, 'profile_remove_own'),
  (2, 'profile_upload_avatar'),
  (2, 'profile_remote_avatar'),
  (2, 'mention'),
  (2, 'access_mod_center');
# --------------------------------------------------------

#
# Dumping data for table `policy`
#

INSERT INTO {$db_prefix}policy
  (id_policy, policy_type, language, title, description, last_revision)
VALUES
  (1, 1, '{$language}', '{$default_policy_terms}', '{$default_policy_terms_desc}', 1),
  (2, 2, '{$language}', '{$default_policy_privacy}', '{$default_policy_privacy_desc}', 2),
  (3, 3, '{$language}', '{$default_policy_roleplay}', '{$default_policy_roleplay_desc}', 3),
  (4, 4, '{$language}', '{$default_policy_cookies}', '{$default_policy_cookies_desc}', 4);

# --------------------------------------------------------

#
# Dumping data for table `policy_revision`
#

INSERT INTO {$db_prefix}policy_revision
  (id_revision, id_policy, last_change, short_revision_note, revision_text, edit_id_member, edit_member_name)
VALUES
  (1, 1, {$current_time}, '', '{$default_policy_terms_text}', 0, ''),
  (2, 2, {$current_time}, '', '{$default_policy_privacy_text}', 0, ''),
  (3, 3, {$current_time}, '', '{$default_policy_roleplay_text}', 0, ''),
  (4, 4, {$current_time}, '', '{$default_policy_cookies_text}', 0, '');

# --------------------------------------------------------

#
# Dumping data for table `policy_types`
#

INSERT INTO {$db_prefix}policy_types
  (id_policy_type, policy_type, require_acceptance, show_footer, show_reg, show_help)
VALUES
  (1, 'terms', 1, 1, 1, 1),
  (2, 'privacy', 1, 1, 1, 1),
  (3, 'roleplay', 0, 0, 0, 0),
  (4, 'cookies', 0, 0, 0, 1);

# --------------------------------------------------------

#
# Dumping data for table `scheduled_tasks`
#

INSERT INTO {$db_prefix}scheduled_tasks
  (id_task, next_time, time_offset, time_regularity, time_unit, disabled, task, callable)
VALUES
  (1, 0, 0, 2, 'h', 0, 'approval_notification', ''),
  (3, 0, 60, 1, 'd', 0, 'daily_maintenance', ''),
  (5, 0, 0, 1, 'd', 0, 'daily_digest', ''),
  (6, 0, 0, 1, 'w', 0, 'weekly_digest', ''),
  (7, 0, {$sched_task_offset}, 1, 'd', 0, 'fetchStoryBBfiles', ''),
  (8, 0, 0, 1, 'd', 1, 'birthdayemails', ''),
  (9, 0, 0, 1, 'w', 0, 'weekly_maintenance', ''),
  (10, 0, 120, 1, 'd', 1, 'paid_subscriptions', ''),
  (11, 0, 120, 1, 'd', 0, 'remove_temp_attachments', ''),
  (12, 0, 180, 1, 'd', 0, 'remove_topic_redirect', ''),
  (13, 0, 240, 1, 'd', 0, 'remove_old_drafts', ''),
  (14, 0, 300, 1, 'd', 0, 'clean_exports', ''),
  (15, 0, 360, 1, 'd', 0, 'scrub_logs', '');

# --------------------------------------------------------

#
# Dumping data for table `settings`
#

INSERT INTO {$db_prefix}settings
  (variable, value)
VALUES ('sbbVersion', '{$sbb_version}'),
  ('news', '{$default_news}'),
  ('todayMod', '1'),
  ('pollMode', '1'),
  ('attachmentSizeLimit', '128'),
  ('attachmentPostLimit', '192'),
  ('attachmentNumPerPostLimit', '4'),
  ('attachmentDirSizeLimit', '10240'),
  ('attachmentDirFileLimit', '1000'),
  ('attachmentUploadDir', '{$attachdir}'),
  ('attachmentExtensions', 'doc,gif,jpg,mpg,pdf,png,txt,zip'),
  ('attachmentCheckExtensions', '0'),
  ('attachmentShowImages', '1'),
  ('attachmentEnable', '1'),
  ('attachmentThumbnails', '1'),
  ('attachmentThumbWidth', '150'),
  ('attachmentThumbHeight', '150'),
  ('use_subdirectories_for_attachments', '1'),
  ('currentAttachmentUploadDir', 1),
  ('censorIgnoreCase', '1'),
  ('mostOnline', '1'),
  ('mostOnlineToday', '1'),
  ('mostDate', UNIX_TIMESTAMP()),
  ('allow_disableAnnounce', '1'),
  ('trackStats', '1'),
  ('userLanguage', '1'),
  ('topicSummaryPosts', '15'),
  ('enableErrorLogging', '1'),
  ('log_ban_hits', '1'),
  ('max_image_width', '0'),
  ('max_image_height', '0'),
  ('smtp_host', ''),
  ('smtp_port', '25'),
  ('smtp_username', ''),
  ('smtp_password', ''),
  ('mail_type', '0'),
  ('totalMembers', '0'),
  ('totalTopics', '1'),
  ('totalMessages', '1'),
  ('censor_vulgar', ''),
  ('censor_proper', ''),
  ('enablePostHTML', '0'),
  ('theme_allow', '1'),
  ('theme_default', '1'),
  ('theme_guests', '1'),
  ('xmlnews_enable', '1'),
  ('xmlnews_maxlen', '255'),
  ('registration_method', '{$registration_method}'),
  ('send_validation_onChange', '0'),
  ('send_welcomeEmail', '1'),
  ('allow_editDisplayName', '1'),
  ('allow_hideOnline', '1'),
  ('spamWaitTime', '5'),
  ('pm_spam_settings', '10,5,20'),
  ('reserveWord', '0'),
  ('reserveCase', '1'),
  ('reserveUser', '1'),
  ('reserveName', '1'),
  ('reserveNames', '{$default_reserved_names}'),
  ('autoLinkUrls', '1'),
  ('banLastUpdated', '0'),
  ('smileys_dir', '{$boarddir}/Smileys'),
  ('smileys_url', '{$boardurl}/Smileys'),
  ('custom_avatar_dir', '{$boarddir}/custom_avatar'),
  ('custom_avatar_url', '{$boardurl}/custom_avatar'),
  ('avatar_max_width', '125'),
  ('avatar_max_height', '125'),
  ('avatar_action_too_large', 'option_css_resize'),
  ('avatar_resize_upload', '1'),
  ('avatar_download_png', '1'),
  ('failed_login_threshold', '3'),
  ('oldTopicDays', '120'),
  ('edit_wait_time', '90'),
  ('edit_disable_time', '0'),
  ('allow_guestAccess', '1'),
  ('time_format', '{$default_time_format}'),
  ('number_format', '1234.00'),
  ('enableBBC', '1'),
  ('max_messageLength', '20000'),
  ('signature_settings', '1,300,0,0,0,0,0,0:'),
  ('defaultMaxMessages', '15'),
  ('defaultMaxTopics', '20'),
  ('defaultMaxMembers', '30'),
  ('recycle_enable', '0'),
  ('recycle_board', '0'),
  ('maxMsgID', '1'),
  ('enableAllMessages', '0'),
  ('knownThemes', '1'),
  ('enableThemes', '1'),
  ('who_enabled', '1'),
  ('time_offset', '0'),
  ('cookieTime', '60'),
  ('lastActive', '15'),
  ('unapprovedMembers', '0'),
  ('databaseSession_enable', '{$databaseSession_enable}'),
  ('databaseSession_loose', '1'),
  ('databaseSession_lifetime', '2880'),
  ('search_cache_size', '50'),
  ('search_results_per_page', '30'),
  ('search_weight_frequency', '30'),
  ('search_weight_age', '25'),
  ('search_weight_length', '20'),
  ('search_weight_subject', '15'),
  ('search_weight_first_message', '10'),
  ('search_max_results', '1200'),
  ('search_floodcontrol_time', '5'),
  ('mail_next_send', '0'),
  ('mail_recent', '0000000000|0'),
  ('settings_updated', '0'),
  ('next_task_time', '1'),
  ('warning_settings', '1,20,0'),
  ('warning_watch', '10'),
  ('warning_moderate', '35'),
  ('warning_mute', '60'),
  ('last_mod_report_action', '0'),
  ('pruningOptions', '30,180,180,180,30'),
  ('modlog_enabled', '1'),
  ('adminlog_enabled', '1'),
  ('cache_enable', '1'),
  ('minimum_age', '16'),
  ('reg_verification', '1'),
  ('visual_verification_type', '3'),
  ('enable_buddylist', '1'),
  ('birthday_email', 'happy_birthday'),
  ('attachment_image_reencode', '1'),
  ('attachment_image_paranoid', '0'),
  ('attachment_thumb_png', '1'),
  ('avatar_reencode', '1'),
  ('avatar_paranoid', '0'),
  ('drafts_post_enabled', '1'),
  ('drafts_pm_enabled', '1'),
  ('drafts_autosave_enabled', '1'),
  ('drafts_show_saved_enabled', '1'),
  ('drafts_keep_days', '7'),
  ('topic_move_any', '0'),
  ('browser_cache', '?beta21'),
  ('mail_limit', '5'),
  ('mail_quantity', '5'),
  ('additional_options_collapsable', '1'),
  ('show_modify', '1'),
  ('show_user_images', '1'),
  ('show_profile_buttons', '1'),
  ('enable_ajax_alerts', '1'),
  ('defaultMaxListItems', '15'),
  ('loginHistoryDays', '30'),
  ('httponlyCookies', '1'),
  ('tfa_mode', '1'),
  ('allow_expire_redirect', '1'),
  ('displayFields', '[{"col_name":"cust_skype","title":"Skype","type":"text","order":"1","bbc":"0","placement":"1","enclose":"<a href=\\"skype:{INPUT}?call\\"><img src=\\"{DEFAULT_IMAGES_URL}\\/skype.png\\" alt=\\"{INPUT}\\" title=\\"{INPUT}\\" \\/><\\/a> ","mlist":"0"},{"col_name":"cust_loca","title":"Location","type":"text","order":"2","bbc":"0","placement":"0","enclose":"","mlist":"0"}]'),
  ('minimize_files', '1'),
  ('enable_mentions', '1'),
  ('retention_policy_standard', 90),
  ('retention_policy_sensitive', 15);

# --------------------------------------------------------

#
# Dumping data for table `smileys`
#

INSERT INTO {$db_prefix}smileys
  (code, filename, description, smiley_order, hidden)
VALUES (':)', 'smiley.gif', '{$default_smiley_smiley}', 0, 0),
  (';)', 'wink.gif', '{$default_wink_smiley}', 1, 0),
  (':D', 'cheesy.gif', '{$default_cheesy_smiley}', 2, 0),
  (';D', 'grin.gif', '{$default_grin_smiley}', 3, 0),
  ('>:(', 'angry.gif', '{$default_angry_smiley}', 4, 0),
  (':(', 'sad.gif', '{$default_sad_smiley}', 5, 0),
  (':o', 'shocked.gif', '{$default_shocked_smiley}', 6, 0),
  ('8)', 'cool.gif', '{$default_cool_smiley}', 7, 0),
  ('???', 'huh.gif', '{$default_huh_smiley}', 8, 0),
  ('::)', 'rolleyes.gif', '{$default_roll_eyes_smiley}', 9, 0),
  (':P', 'tongue.gif', '{$default_tongue_smiley}', 10, 0),
  (':-[', 'embarrassed.gif', '{$default_embarrassed_smiley}', 11, 0),
  (':-X', 'lipsrsealed.gif', '{$default_lips_sealed_smiley}', 12, 0),
  (':-\\', 'undecided.gif', '{$default_undecided_smiley}', 13, 0),
  (':-*', 'kiss.gif', '{$default_kiss_smiley}', 14, 0),
  (':''(', 'cry.gif', '{$default_cry_smiley}', 15, 0),
  ('>:D', 'evil.gif', '{$default_evil_smiley}', 16, 1),
  ('^-^', 'azn.gif', '{$default_azn_smiley}', 17, 1),
  ('O0', 'afro.gif', '{$default_afro_smiley}', 18, 1),
  (':))', 'laugh.gif', '{$default_laugh_smiley}', 19, 1),
  ('C:-)', 'police.gif', '{$default_police_smiley}', 20, 1),
  ('O:-)', 'angel.gif', '{$default_angel_smiley}', 21, 1);
# --------------------------------------------------------

#
# Dumping data for table `themes`
#

INSERT INTO {$db_prefix}themes
  (id_theme, variable, value)
VALUES (1, 'name', '{$default_theme_name}'),
  (1, 'theme_url', '{$boardurl}/Themes/default'),
  (1, 'images_url', '{$boardurl}/Themes/default/images'),
  (1, 'theme_dir', '{$boarddir}/Themes/default'),
  (1, 'show_latest_member', '1'),
  (1, 'show_newsfader', '0'),
  (1, 'number_recent_posts', '0'),
  (1, 'show_stats_index', '1'),
  (1, 'newsfader_time', '3000'),
  (1, 'enable_news', '1'),
  (1, 'drafts_show_saved_enabled', '1');

INSERT INTO {$db_prefix}themes
  (id_member, id_theme, variable, value)
VALUES (-1, 1, 'posts_apply_ignore_list', '1'),
  (-1, 1, 'return_to_post', '1');
# --------------------------------------------------------

#
# Dumping data for table `topics`
#

INSERT INTO {$db_prefix}topics
  (id_topic, id_board, id_first_msg, id_last_msg, id_member_started, id_member_updated)
VALUES (1, 1, 1, 1, 0, 0);
# --------------------------------------------------------

#
# Dumping data for table `user_alerts_prefs`
#

INSERT INTO {$db_prefix}user_alerts_prefs
  (id_member, alert_pref, alert_value)
VALUES (0, 'member_group_request', 1),
  (0, 'member_register', 1),
  (0, 'msg_like', 1),
  (0, 'msg_report', 1),
  (0, 'msg_report_reply', 1),
  (0, 'unapproved_reply', 3),
  (0, 'topic_notify', 1),
  (0, 'board_notify', 1),
  (0, 'msg_mention', 1),
  (0, 'msg_quote', 1),
  (0, 'pm_new', 1),
  (0, 'pm_reply', 1),
  (0, 'groupr_approved', 3),
  (0, 'groupr_rejected', 3),
  (0, 'member_report_reply', 3),
  (0, 'birthday', 2),
  (0, '_announcements', 2),
  (0, 'member_report', 3),
  (0, 'unapproved_post', 1),
  (0, 'buddy_request', 1),
  (0, 'warn_any', 1),
  (0, 'request_group', 1);
# --------------------------------------------------------

COMMIT;