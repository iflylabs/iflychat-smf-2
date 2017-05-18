<?php

// iFlyChat START //

	$txt['iflychat'] = '$iFlyChat';
	
	$txt['iflychat_option_yes'] = '$Yes';
	
	$txt['iflychat_external_api_key'] = '$iFlyChat API Key
	<div class="smalltext">Please enter the API key by registering at <a href ="https://iflychat.com/">iFlyChat.com</a></div>';

	$txt['iflychat_external_app_id'] = '$iFlyChat APP Id
	<div class="smalltext">register at <a href ="https://iflychat.com/">iFlyChat.com</a> to get it </div>';

	$txt['iflychat_use_name'] = '$Specify whether to use display name or username for logged-in user';
	$txt['iflychat_use_name_username'] = '$Username';
	$txt['iflychat_use_name_displayName'] = '$Display Name';


	$txt['iflychat_enable_friends'] = '$Show only friends in online user list';
	$txt['iflychat_option_no'] = '$No';
	$txt['iflychat_option_buddyPress'] = '$BuddyPress Friends';
	

	$txt['iflychat_popup_chat'] = '$Show Popup Chat';
	$txt['iflychat_popup_chat_everywhere'] = '$Everywhere';
	$txt['iflychat_popup_chat_frontend_only'] = '$Frontend Only';
	$txt['iflychat_popup_chat_everywhere_except_those_listed'] = '$Everywhere except those listed';
	$txt['iflychat_popup_chat_only_the_listed_pages'] = '$Only the listed pages';
	$txt['iflychat_popup_chat_disable'] = '$Disable';

	$txt['iflychat_path_pages'] = '$Specify pages by using their paths. Enter one path per line. The \'*\' character is a wildcard. Example paths are /2012/10/my-post for a single post and /2012/* for a group of posts. The path should always start with a forward slash(/).
	<div class="smalltext"></div>';

	$txt['iflychat_chat_moderators_array'] = '$Specify SMF username of users who should be chat moderators (separated by comma)
	<div class="smalltext"></div>';

	$txt['iflychat_chat_admins_array'] = '$Specify SMF username of users who should be chat admininstrators (separated by comma)
	<div class="smalltext"></div>';

	$txt['iflychat_session_caching'] = '$Enable Session Caching';
	$txt['iflychat_session_caching_yes'] = '$Yes';
	$txt['iflychat_session_caching_no'] = '$No';



/*






	
	$txt['iflychat_show_admin_list'] = '$Select which chat software to use
	<div class="smalltext">In Community chat, users with administer permission are considered as chat moderators/administrators. And, in Support chat, users with administer_iflychat permission are considered as support staff.</div>';
	$txt['iflychat_support_chat'] = '$Support Chat';
	$txt['iflychat_community_chat'] = '$Community Chat';
	
	$txt['iflychat_only_loggedin'] = '$Allow only logged-in users to access chat';
	
	$txt['iflychat_theme'] = '$Theme to use
	<div class="smalltext">All themes from inside the themes folder will be displayed here.</div>';
	$txt['iflychat_theme_light'] = '$Light';
	$txt['iflychat_theme_dark'] = '$Dark';
	
	$txt['iflychat_enable_friends'] = '$Show only friends in online user list';
	
	$txt['iflychat_notification_sound'] = '$Enable Notification Sound
	<div class="smalltext">Select whether to play notification sound when a new message is received.</div>';
	
	$txt['iflychat_user_picture'] = '$Enable User Picture
	<div class="smalltext">Select whether to show user pictures in chat.</div>';
	
	$txt['iflychat_enable_smileys'] = '$Enable Smileys
	<div class="smalltext">Select whether to show smileys.</div>';
	
	$txt['iflychat_enable_mobile_browser_app'] = '$Enable Mobile browser app
	<div class="smalltext">Select whether to enable browser based Mobile app.</div>';
	
	$txt['iflychat_log_messages'] = '$Log chat messages
	<div class="smalltext">Select whether to log chat messages, which can be later viewed in message inbox.</div>';
	
	$txt['iflychat_anon_prefix'] = '$Prefix to be used with anonymous users
	<div class="smalltext">Please specify the prefix to be used with anonymous users. It shouldn\'t be long. Ideally it should be between 4 to 7 characters.</div>';
	
	$txt['iflychat_anon_use_name'] = '$Use random name or number for anonymous user
	<div class="smalltext">Select whether to use random generated name or number to assign to a new anonymous user.</div>';
	$txt['iflychat_anon_name'] = '$Name';
	$txt['iflychat_anon_number'] = '$Number';
	
	$txt['iflychat_anon_change_name'] = '$Allow anonymous user to set his/her name
	<div class="smalltext">Select whether to allow anonymous user to be able to change his/her name.</div>';

	$txt['iflychat_minimize_chat_user_list'] = '$Select whether to minimize online user list in chat by default';
	
	$txt['iflychat_enable_chatroom'] = '$Enable Public Chatroom
	<div class="smalltext"></div>';
	$txt['iflychat_stop_word_list'] = '$Stop Words (separated by comma)
	<div class="smalltext"></div>';
	
	$txt['iflychat_use_stop_word_list'] = '$Use Stop Words to filter chat
	<div class="smalltext">Select whether to use stop words(entered above) for filtering</div>';
	$txt['use_stop_word_list_no_filter'] = '$Don\'t filter';
	$txt['use_stop_word_list_in_public'] = '$Filter in public chatroom';
	$txt['use_stop_word_list_in_private'] = '$Filter in private chats';
	$txt['use_stop_word_list_in_all'] = '$Filter in all rooms';
	
	$txt['iflychat_stop_links'] = '$Allow/Block hyperlinks
	<div class="smalltext">Select whether to allow/block hyperlinks posted in chats</div>';
	
	$txt['iflychat_stop_links_no_block'] = '$Don\'t block';
	$txt['iflychat_stop_links_block_public'] = '$Block in public chatroom';
	$txt['iflychat_stop_links_block_private'] = '$Block in private chats';
	$txt['iflychat_stop_links_block_all'] = '$Block in all rooms';
	
	$txt['iflychat_allow_render_images'] = '$Render image and video hyperlinks inline
	<div class="smalltext">Select whether to render image and video hyperlinks inline in chat.</div>';
	
	$txt['iflychat_allow_anon_links'] = '$Apply above defined block hyperlinks settings only to anonymous users
	<div class="smalltext">Select whether to apply above defined block hyperlinks setting only to anonymous users.</div>';
	
	$txt['iflychat_allow_single_message_delete'] = '$Allow users to delete messages selectively when in private conversation
	<div class="smalltext">Select whether to apply above defined block hyperlinks setting only to anonymous users.</div>';
	$txt['iflychat_allow_all_users'] = '$Allow all users';
	$txt['iflychat_allow_moderators'] = '$Allow only moderators';
	$txt['iflychat_disable'] = '$Disable';
	
	$txt['iflychat_allow_clear_room_history'] = '$Allow users to clear all messages in a room
	<div class="smalltext">Select whether to allow users to clear all messages in a room</div>';
	
	$txt['iflychat_enable_file_attachment'] = '$Enable File Attachment
	<div class="smalltext"></div>';
	
	$txt['iflychat_allow_user_font_color'] = '$Allow users to set color of their name in a room
	<div class="smalltext">Select whether to allow users to set color of their name in a room.</div>';
	
	$txt['iflychat_enable_search_bar'] = '$Select whether to show search bar in online user list';
	
	$txt['iflychat_path_visibility'] = '$Show chat on specific pages';
	
	$txt['iflychat_all_pages_except_listed'] = '$All pages except those listed';
	
	$txt['iflychat_chat_admins_array'] = '$Specify SMF username of users who should be chat admininstrators (separated by comma)';
	
	$txt['iflychat_only_listed_pages'] = '$Only the listed pages';
	
	$txt['iflychat_path_pages'] = '$Specify pages by using their paths. Enter one path per line. The \'*\' character is a wildcard. Example paths are /2012/10/my-post for a single post and /2012/* for a group of posts. The path should always start with a forward slash(/).';
	
	$txt['iflychat_chat_top_bar_color'] = '$Chat Top Bar Color';
	
	$txt['iflychat_chat_top_bar_text_color'] = '$Chat Top Bar Text Color';
	
	$txt['iflychat_chat_font_color'] = '$Chat Font Color';
	
	$txt['iflychat_public_chatroom_header'] = '$Public Chatroom Header';
	
	$txt['iflychat_chat_list_header'] = '$Chat List Header';
	
	$txt['iflychat_support_chat_init_label'] = '$Support Chat - Start Button Label';
	
	$txt['iflychat_support_chat_box_header'] = '$Support Chat - Chat Box Header Label';
	
	$txt['iflychat_support_chat_box_company_name'] = '$Support Chat - Team/Company Name';
	
	$txt['iflychat_support_chat_box_company_tagline'] = '$Support Chat - Tagline Label';
	
	$txt['iflychat_support_chat_auto_greet_enable'] = '$Support Chat - Enable Auto Greeting Message';
	
	$txt['iflychat_support_chat_auto_greet_message'] = '$Support Chat - Auto Greeting Message';
	
	$txt['iflychat_support_chat_auto_greet_time'] = '$Support Chat - Auto Greet Message Time Delay (in seconds)<div class="smalltext">The delay, in seconds, after which the first time visitors will be shown auto greeting message.</div>';
	
	$txt['iflychat_support_chat_init_label_off'] = '$Support Chat - Offline Message Button Label';
	
	$txt['iflychat_support_chat_offline_message_desc'] = '$Support Chat - Offline Message Description';
	
	$txt['iflychat_support_chat_offline_message_label'] = '$Support Chat - Offline Window - Message Label';
	
	$txt['iflychat_support_chat_offline_message_contact'] = '$Support Chat - Offline Window - Contact Details Label';
	
	$txt['iflychat_support_chat_offline_message_send_button'] = '$Support Chat - Offline Window - Send Button Label';
	
	$txt['iflychat_support_chat_offline_message_email'] = '$Support Chat - Email(s) to which mail offline messages should be sent (separated by comma)';
	
	$txt['MOD_BAN'] = '$Ban';
	
	$txt['MOD_BAN_IP'] = '$Ban IP';
	
	$txt['MOD_KICK'] = '$Kick';
	
	$txt['MOD_BANNED_USERS'] = '$Banned Users';
	
	$txt['MOD_NO_BAN'] = '$No users have been banned currently.';
	
	$txt['MOD_LOADING'] = '$Loading banned user list...';
	
	$txt['MOD_MANAGE_ROOMS'] = '$Manage Rooms';
	
	$txt['MOD_UNBAN'] = '$Unban';
	
	$txt['MOD_UNBAN_IP'] = '$Unban IP';
	
	$txt['MOD_GO_ONLINE'] = '$Go Online';
	
	$txt['MOD_GO_IDLE'] = '$Go Idle';
	
	$txt['MOD_NEW_CHAT_MESSAGE'] = '$New chat message!';
	
	$txt['MOD_USER_CURRENTLY_OFFLINE'] = '$User is currently offline.';
	
	$txt['MOD_USER_IS_TYPING'] = '$user is typing...';
	
	$txt['MOD_CLOSE'] = '$Close';
	
	$txt['MOD_MINIMIZE'] = '$Minimize';
	
	$txt['MOD_CLICK_TO_MUTE'] = '$Click to Mute';
	
	$txt['MOD_CLICK_TO_UNMUTE'] = '$Click to Unmute';
	
	$txt['MOD_AVAILABLE'] = '$Available';
	
	$txt['MOD_IDLE'] = '$Idle';
	
	$txt['MOD_BUSY'] = '$Busy';
		
	$txt['MOD_OFFLINE'] = '$Offline';
	
	$txt['MOD_LOAD_MORE_MESSAGES'] = '$Load More Messages';
	
	$txt['MOD_NO_MORE_MESSAGES'] = '$No More Messages';
	
	$txt['MOD_CLEAR_ALL_MESSAGES'] = '$Clear all messages';
		
	$txt['MOD_TYPE_AND_PRESS_ENTER'] = '$Type and Press Enter';
	
	$txt['MOD_TYPE_HERE_TO_SEARCH'] = '$Type here to search';
	
	$txt['MOD_USER_LIST_RECONNECT'] = '$Connecting...';
	
	$txt['MOD_USER_LIST_LOADING'] = '$Loading...';
	
	$txt['permissiongroup_iflychat'] = '$iFlyChat';
	
	$txt['permissiongroup_simple_iflychat'] = '$iFlyChat';
	$txt['permissionname_user_iflychatshow'] = '$Allow iFlyChat';
	$txt['permissionhelp_user_iflychatshow'] = '$Allows a user in this group to iFlyChat.';
	$txt['permissionname_user_iflychatpage'] = '$Allow iFlyChat Mod page';
	$txt['permissionhelp_user_iflychatpage'] = '$Allows a user to access iFlyChat settings from the main menu.';*/

// iFlyChat END  //


?>