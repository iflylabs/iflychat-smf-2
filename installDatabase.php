<?php

if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$newSettings = array(
	'iflychat_external_api_key'   =>  '',
	'iflychat_external_app_id'    =>  '',
    'iflychat_enable_friends'			=> '2',
    'iflychat_popup_chat'         => '1',
	'iflychat_path_pages'	=> '',
	'iflychat_chat_moderators_array'	=> '',
	'iflychat_chat_admins_array'	=> '',
	'iflychat_session_caching'	=> '2',
);

updateSettings($newSettings);