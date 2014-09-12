<?php

if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$settings = array(
  'iflychat_external_api_key',
  'iflychat_show_admin_list',
  'iflychat_theme',
  'iflychat_notification_sound',
  'iflychat_user_picture',  
  'iflychat_enable_smileys',
  'iflychat_enable_mobile_browser_app',
  'iflychat_log_messages',
  'iflychat_anon_prefix',
  'iflychat_anon_use_name',
  'iflychat_anon_change_name',
  'iflychat_ext_d_i',
  'iflychat_support_chat_init_label',
  'iflychat_support_chat_box_header',
  'iflychat_support_chat_box_company_name',
  'iflychat_support_chat_box_company_tagline',
  'iflychat_support_chat_auto_greet_enable',
  'iflychat_support_chat_auto_greet_message',
  'iflychat_support_chat_auto_greet_time',
  'iflychat_support_chat_init_label_off',
  'iflychat_support_chat_offline_message_desc',
  'iflychat_support_chat_offline_message_label',
  'iflychat_support_chat_offline_message_contact',
  'iflychat_support_chat_offline_message_send_button',
  'iflychat_support_chat_offline_message_email',
  'iflychat_enable_chatroom',
  'iflychat_stop_word_list',
  'iflychat_use_stop_word_list',
  'iflychat_stop_links',
  'iflychat_allow_anon_links',
  'iflychat_allow_render_images',
  'iflychat_enable_file_attachment',
  'iflychat_allow_single_message_delete',
  'iflychat_allow_clear_room_history',
  'iflychat_allow_user_font_color',
  'iflychat_path_visibility',
  'iflychat_path_pages',
  'iflychat_chat_topbar_color',
  'iflychat_chat_topbar_text_color',
  'iflychat_font_color',
  'iflychat_public_chatroom_header',
  'iflychat_chat_list_header',
  'iflychat_minimize_chat_user_list',
  'iflychat_enable_search_bar',
  'iflychat_enable_friends',
);

if (isset($smcFunc) && !empty($smcFunc))
  $smcFunc['db_query']('', '
    DELETE FROM {db_prefix}settings
    WHERE variable IN ({array_string:settings})',
    array(
      'settings' => $settings,
    )
  );