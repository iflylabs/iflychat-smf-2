<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
 
require_once("{$sourcedir}/Subs-Package.php");
require_once("{$sourcedir}/Subs.php");
require_once("{$sourcedir}/Security.php");
require_once("{$sourcedir}/ManageServer.php");
require_once("{$sourcedir}/Groups.php");
require_once("{$sourcedir}/helper-iflychat.php");

define('SMFCHAT_EXTERNAL_HOST', 'http://api'.iflychat_get_option('iflychat_ext_d_i').'.iflychat.com');
define('SMFCHAT_EXTERNAL_PORT', '80');
define('SMFCHAT_EXTERNAL_A_HOST', 'https://api'.iflychat_get_option('iflychat_ext_d_i').'.iflychat.com');
define('SMFCHAT_EXTERNAL_A_PORT', '443');

loadLanguage('iflychat');

/*Add action hook
 *Params: actionArray
 *Returns: void
 */
function iflychat_add_hook(&$actionArray)
{
  global $sourcedir;
  
  $actionArray['iflychat-get'] = array($sourcedir .'/Subs-iflychat.php', 'iflychat_auth');
  
  $actionArray['iflychat'] = array($sourcedir .'/Subs-iflychat.php', 'iflychat_settings');
  
  $actionArray['iflychat-mob-auth'] = array($sourcedir .'/Subs-iflychat.php', 'iflychat_mob_auth');
  
  $actionArray['iflychat-init'] = array($sourcedir .'/Subs-iflychat.php', 'iflychat_init');
}

/*Load permissions
 *Params: permissionGroups, permissionList, leftPermissionGroups, hiddenPermissions, relabelPermissions
 *Returns: void
 */
function iflychat_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
  global $context;
  $permissionGroups['membergroup']['simple'] = array('iflychat');
  $permissionGroups['membergroup']['classic'] = array('iflychat');
  $permissionList['membergroup']['user_iflychatpage'] = array(false, 'iflychat', 'iflychat');
  $permissionList['membergroup']['user_iflychatshow'] = array(false, 'iflychat', 'iflychat');
}

/*Editing template buffer before rendering it to user
 *Params: template buffer
 *Returns: edited template buffer
 */
function iflychat_template_init($buffer)
{
  global $context, $settings, $scripturl, $modSettings;

  if(allowedTo('user_iflychatpage'))
  {
    $search_menu = '<li id="button_profile">';
  
    $replace_menu = '<li id="button_iflychat">
          <a class="firstlevel" href="'.$scripturl.'?action=iflychat">
            <span class="firstlevel">iFlyChat</span>
          </a>
        </li>
        <li id="button_profile">';
      
    $buffer = str_replace($search_menu, $replace_menu, $buffer);
  }
  
  if(iflychat_path_check() && (($modSettings['iflychat_only_loggedin'] == '2') || iflychat_is_user_loggedin()) && allowedTo('user_iflychatshow') && !empty($modSettings['iflychat_external_api_key']))
  {
    $search_footer = "</body>";
  
    $replace_footer = '<script type="text/javascript" src="'.$settings['default_theme_url'].'/iflychat/js/jquery.min.js"></script>
          <script type="text/javascript" src="'.$scripturl.'?action=iflychat-init"></script>
          <script type="text/javascript" src="'.$settings['default_theme_url'].'/iflychat_static/js/iflychat.js"></script>
          </body>';
          
    $buffer = str_replace($search_footer, $replace_footer, $buffer);
  }
          
  return $buffer;
}

/*iFlyChat authentication
 *Params: void
 *Returns: JSON data with key, css, _i, name, uid, userpic, userprofile
 */
function iflychat_auth()
{
  global $txt, $scripturl, $context, $settings, $sc, $modSettings, $user_info, $user_profile;
  
  header("Content-Type: application/json");

  if(empty($modSettings['iflychat_external_api_key']))
  {
    return null;
  }

  $_i = $modSettings['iflychat_ext_d_i'];

  if(iflychat_check_chat_admin())
  {
    $role = 'admin';
  }
  else
  {
    $groups = list_getGroups(0);
    $role = array();
    foreach($groups as $group)
    {
      if(in_array($group['id'], $user_info['groups']))
      {
        $role[$group['id']] = $group['name'];
      }
    }
  }
  
  if($modSettings['iflychat_theme'] == 1)
  {
    $iflychat_theme = 'light';
  }
  else
  {
    $iflychat_theme = 'dark';
  }
  
  $api_key = $modSettings['iflychat_external_api_key'];
  
  $data = array(
    'uname' => !($user_info['is_guest']) ? $user_info['username'] : iflychat_get_current_guest_name(),
    'uid' => !($user_info['is_guest']) ? $user_info['id'] : iflychat_get_current_guest_id(),
    'api_key' => $api_key,
    'image_path' => $settings['default_theme_url'].'/iflychat_static/themes/' . $iflychat_theme . '/images/',
    'isLog' => TRUE,
    'role' => $role,
    'whichTheme' => 'blue',
    'enableStatus' => TRUE,
    'validState' => array('available','offline','busy','idle'),
    'rel' => '0'
  );
  
  if($modSettings['iflychat_enable_friends'] == '1')
  {
    loadMemberData($user_info['buddies']);
    
    $data['rel'] = '1';
    $final_list = array();
    $final_list['1']['name'] = 'friend';
    $final_list['1']['plural'] = 'friends';

    foreach($user_info['buddies'] as $uid)
    {
      loadMemberContext($uid);
      
      $has = (in_array($user_info['id'], explode(",", $user_profile[$uid]['buddy_list'])));
      if($has)
      {
        $final_list['1']['valid_uids'][] = $uid;
      }
    }
    $data['valid_uids'] = $final_list;  
  }

  if($modSettings['iflychat_user_picture'] == '1')
  {
    $data['up'] = iflychat_get_user_pic_url();
  }
  
  $data['upl'] = iflychat_get_user_profile_url();
  
  $post_data = http_build_query($data, '', '&');

  if(!($modSettings['iflychat_enable_friends'] == '1' && $user_info['is_guest']))
  {
    $result = fetch_web_data(SMFCHAT_EXTERNAL_HOST . ':' . SMFCHAT_EXTERNAL_PORT .  '/p/', $post_data, false, 3);
  }
  
  if(empty($result) || !isset($result))
  {
    $var = array(
      'uname' => !($user_info['is_guest']) ? $user_info['username'] : iflychat_get_current_guest_name(),
      'uid' => !($user_info['is_guest']) ? $user_info['id'] : iflychat_get_current_guest_id()
    );
    
    exit(json_encode($var));
  }
  
  $jsonDataObj = json_decode($result);

  //updating value of iflychat_ext_d_i
  if(isset($jsonData->_i) && ($jsonData->_i != $_i))
  {
    $updateSettings = array(
      'iflychat_ext_d_i' => $jsonData->_i
    );
    
    updateSettings($updateSettings);
  }
  
  $jsonDataAssoc = json_decode($result, TRUE);
  
  $jsonDataAssoc['name'] = !($user_info['is_guest']) ? $user_info['username'] : iflychat_get_current_guest_name();
  
  $jsonDataAssoc['uid'] = !($user_info['is_guest']) ? $user_info['id'] : iflychat_get_current_guest_id();
  
  $jsonDataAssoc['up'] = iflychat_get_user_pic_url();
  
  $jsonDataAssoc['upl'] = iflychat_get_user_profile_url();
  
  exit(json_encode($jsonDataAssoc));
}
/*iFlyChat Mobile authentication
 *Params: void
 *Returns: Chat HTML page
 */
function iflychat_mob_auth()
{
  global $txt, $scripturl, $modSettings, $user_info;
  $_i = $modSettings['iflychat_ext_d_i'];
  
  $data = array('settings' => array());
  $data['settings']['authUrl'] = $scripturl .'?action=iflychat-get';
  $data['settings']['host'] = ((isSSL())?(SMFCHAT_EXTERNAL_A_HOST):(SMFCHAT_EXTERNAL_HOST));
  $data['settings']['port'] = ((isSSL())?(SMFCHAT_EXTERNAL_A_PORT):(SMFCHAT_EXTERNAL_PORT));
  
  $post_data = http_build_query($data, '', '&');
  
  $result = fetch_web_data(SMFCHAT_EXTERNAL_HOST . ':' . SMFCHAT_EXTERNAL_PORT .  '/m/v1/app/', $post_data, false, 3);
  
  if(!empty($result))
  {
    echo $result;
  }
  exit();
}

/*Initialises iFlyChat settings
 *Params: null
 *Returns: iFlyChat javascript variables
 */
function iflychat_init()
{
  global $modSettings, $context, $user_info, $settings, $scripturl, $txt;
  
  header('Content-type: application/javascript');
  
  if($modSettings['iflychat_theme'] == 1)
  {
    $iflychat_theme = 'light';
  }
  else
  {
    $iflychat_theme = 'dark';
  }
  
  if(isset($context['language']))
    $language = $context['language'];
  else
    $language = 'English';
  
  $iflychat_settings = array(
    'current_timestamp' => time(),
    'polling_method' => '3', //$polling_method
    'pollUrl' => '',
    'sendUrl' => '',
    'statusUrl' => '',
    'status' => '1',
    'goOnline' => $txt['MOD_GO_ONLINE'],
    'goIdle' => $txt['MOD_GO_IDLE'],
    'newMessage' => $txt['MOD_NEW_CHAT_MESSAGE'],
    'images' => $settings['default_theme_url'].'/iflychat_static/themes/' . $iflychat_theme . '/images/',
    'sound' => $settings['default_theme_url'].'/iflychat_static/swf/sound.swf',
    'soundFile' => $settings['default_theme_url'].'/iflychat_static/wav/notification.mp3',
    'noUsers' => '<div class="item-list"><ul><li class="drupalchatnousers even first last">No users online</li></ul></div>',
    'smileyURL' => $settings['default_theme_url'].'/iflychat_static/smileys/very_emotional_emoticons-png/png-32x32/',
    'addUrl' => ' ',
    'notificationSound' => $modSettings['iflychat_notification_sound'],
    'exurl' => $scripturl.'?action=iflychat-get',
    'mobileWebUrl' => $scripturl .'?action=iflychat-mob-auth',
    'chat_type' => $modSettings['iflychat_show_admin_list'],
    'guestPrefix' => $modSettings['iflychat_anon_prefix'],
    'allowSmileys' => $modSettings['iflychat_enable_smileys'],
    'admin' => iflychat_check_chat_admin()?'1':'0',
    'theme' => $iflychat_theme
  );
  
  if(iflychat_check_chat_admin())
  {
    $groups = list_getGroups(0, 1000, 'id');
    $iflychat_settings['arole'] = array();
    foreach($groups as $group)
    {
      $iflychat_settings['arole'][$group['id']] = $group['name'];
    }
  }
  $iflychat_settings['iup'] = $modSettings['iflychat_user_picture'];
  
  if($modSettings['iflychat_user_picture'] == '1')
  {
    $iflychat_settings['default_up'] = $settings['default_theme_url'].'/iflychat_static/themes/' . $iflychat_theme . '/images/default_avatar.png';
    $iflychat_settings['default_cr'] = $settings['default_theme_url'].'/iflychat_static/themes/' . $iflychat_theme . '/images/default_room.png';
    $iflychat_settings['default_team'] = $settings['default_theme_url'].'/iflychat_static/themes/' . $iflychat_theme . '/images/default_team.png';
  }

  //if SSL enabled use https else http
  if($iflychat_settings['polling_method'] == '3')
  {
    if(isSSL())
    {
      $iflychat_settings['external_host'] = SMFCHAT_EXTERNAL_A_HOST;
      $iflychat_settings['external_port'] = SMFCHAT_EXTERNAL_A_PORT;
      $iflychat_settings['external_a_host'] = SMFCHAT_EXTERNAL_A_HOST;
      $iflychat_settings['external_a_port'] = SMFCHAT_EXTERNAL_A_PORT;
    }
    else
    {
      $iflychat_settings['external_host'] = SMFCHAT_EXTERNAL_HOST;
      $iflychat_settings['external_port'] = SMFCHAT_EXTERNAL_PORT;
      $iflychat_settings['external_a_host'] = SMFCHAT_EXTERNAL_HOST;
      $iflychat_settings['external_a_port'] = SMFCHAT_EXTERNAL_PORT;
    }
  }

  $iflychat_settings['text_currently_offline'] = $txt['MOD_USER_CURRENTLY_OFFLINE'];
  $iflychat_settings['text_is_typing'] = $txt['MOD_USER_IS_TYPING'];
  $iflychat_settings['text_close'] = $txt['MOD_CLOSE'];
  $iflychat_settings['text_minimize'] = $txt['MOD_MINIMIZE'];
  $iflychat_settings['text_mute'] = $txt['MOD_CLICK_TO_MUTE'];
  $iflychat_settings['text_unmute'] = $txt['MOD_CLICK_TO_UNMUTE'];
  $iflychat_settings['text_available'] = $txt['MOD_AVAILABLE'];
  $iflychat_settings['text_idle'] = $txt['MOD_IDLE'];
  $iflychat_settings['text_busy'] = $txt['MOD_BUSY'];
  $iflychat_settings['text_offline'] = $txt['MOD_OFFLINE'];
  $iflychat_settings['text_lmm'] = $txt['MOD_LOAD_MORE_MESSAGES'];
  $iflychat_settings['text_nmm'] = $txt['MOD_NO_MORE_MESSAGES'];
  $iflychat_settings['text_clear_room'] = $txt['MOD_CLEAR_ALL_MESSAGES'];
  $iflychat_settings['msg_p'] = $txt['MOD_TYPE_AND_PRESS_ENTER'];
  $iflychat_settings['text_user_list_reconnect'] = $txt['MOD_USER_LIST_RECONNECT'];
  $iflychat_settings['text_user_list_loading'] = $txt['MOD_USER_LIST_LOADING'];
  
  //if chat admin show admin options
  if(iflychat_check_chat_admin())
  {
    $iflychat_settings['text_ban'] = $txt['MOD_BAN'];
    $iflychat_settings['text_ban_ip'] = $txt['MOD_BAN_IP'];
    $iflychat_settings['text_kick'] = $txt['MOD_KICK'];
    $iflychat_settings['text_ban_window_title'] = $txt['MOD_BANNED_USERS'];
    $iflychat_settings['text_ban_window_default'] = $txt['MOD_NO_BAN'];
    $iflychat_settings['text_ban_window_loading'] = $txt['MOD_LOADING'];
    $iflychat_settings['text_manage_rooms'] = $txt['MOD_MANAGE_ROOMS'];
    $iflychat_settings['text_unban'] = $txt['MOD_UNBAN'];
    $iflychat_settings['text_unban_ip'] = $txt['MOD_UNBAN_IP'];
  }
    
  //if support chat, show support options
  if($modSettings['iflychat_show_admin_list'] == '1')
  {
    $iflychat_settings['text_support_chat_init_label'] = $modSettings['iflychat_support_chat_init_label'];
    $iflychat_settings['text_support_chat_box_header'] = $modSettings['iflychat_support_chat_box_header'];
    $iflychat_settings['text_support_chat_box_company_name'] = $modSettings['iflychat_support_chat_box_company_name'];
    $iflychat_settings['text_support_chat_box_company_tagline'] = $modSettings['text_support_chat_box_company_tagline'];
    $iflychat_settings['text_support_chat_auto_greet_enable'] = $modSettings['text_support_chat_auto_greet_enable'];
    $iflychat_settings['text_support_chat_auto_greet_message'] = $modSettings['text_support_chat_auto_greet_message'];
    $iflychat_settings['text_support_chat_auto_greet_time'] = $modSettings['text_support_chat_auto_greet_time'];
    $iflychat_settings['text_support_chat_offline_message_label'] = $modSettings['text_support_chat_offline_message_label'];
    $iflychat_settings['text_support_chat_offline_message_contact'] = $modSettings['text_support_chat_offline_message_contact'];
    $iflychat_settings['text_support_chat_offline_message_send_button'] = $modSettings['text_support_chat_offline_message_send_button'];
    $iflychat_settings['text_support_chat_offline_message_desc'] = $modSettings['text_support_chat_offline_message_desc'];
    $iflychat_settings['text_support_chat_init_label_off'] = $modSettings['text_support_chat_init_label_off'];
  }
  
  $iflychat_settings['open_chatlist_default'] = ($modSettings['iflychat_minimize_chat_user_list'] == 2)?'1':'2';  
  $iflychat_settings['useStopWordList'] = $modSettings['iflychat_use_stop_word_list'];  
  $iflychat_settings['blockHL'] = $modSettings['iflychat_stop_links'];
  $iflychat_settings['allowAnonHL'] = $modSettings['iflychat_allow_anon_links'];
  $iflychat_settings['renderImageInline'] = ( $modSettings['iflychat_allow_render_images'] == '1' ) ? '1' : '2';
  $iflychat_settings['searchBar'] = ( $modSettings['iflychat_enable_search_bar']  == '1' ) ? '1' : '2';
  $iflychat_settings['text_search_bar'] = $txt['MOD_TYPE_HERE_TO_SEARCH'];
  
//$iflychat_protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
  $iflychat_settings['geturl'] = $scripturl;
    
  $iflychat_settings['soffurl'] = $scripturl;
    
  $iflychat_settings['changeurl'] = $scripturl;
  //code for rendering
  $output = "Drupal={};Drupal.settings={};Drupal.settings.drupalchat=".json_encode($iflychat_settings, TRUE).";";
  exit($output);
}

/*Initialises iFlyChat settings page
 *Params: null
 *Returns: iFlyChat settings page and updates settings
 */
function iflychat_settings()
{
  
  global $txt, $scripturl, $context, $settings, $sc, $modSettings;
  
  isAllowedTo('user_iflychatpage');
    
  $context['sub_template'] = 'show_settings';
  $context['page_title'] = $txt['iflychat'];
  $context['linktree'][] = array(
    'url' => $scripturl . '?action=iflychat',
    'name' => $txt['iflychat']
  );
  
  $config_vars = array(
    array('text', 'iflychat_external_api_key'),
//    array('select', 'iflychat_show_admin_list', array('1' => $txt['iflychat_support_chat'], '2' => $txt['iflychat_community_chat'])),
    array('select', 'iflychat_only_loggedin', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_theme', array('1' => $txt['iflychat_theme_light'], '2' => $txt['iflychat_theme_dark'])),
    array('select', 'iflychat_enable_friends', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_user_picture', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_notification_sound', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_enable_smileys', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_enable_mobile_browser_app', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_log_messages', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('text', 'iflychat_anon_prefix'),
    array('select', 'iflychat_anon_use_name', array('1' => $txt['iflychat_anon_name'], '2' => $txt['iflychat_anon_number'])),
    array('select', 'iflychat_anon_change_name', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_minimize_chat_user_list', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select', 'iflychat_enable_search_bar', array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select','iflychat_enable_chatroom',array('1' => $txt['iflychat_option_yes'], '2' => $txt['iflychat_option_no'])),
    array('select','iflychat_allow_user_font_color',array('1'=>$txt['iflychat_option_yes'],'2'=>$txt['iflychat_option_no'])),
    array('text','iflychat_chat_top_bar_color'),
    array('text','iflychat_chat_top_bar_text_color'),
    array('text','iflychat_chat_font_color'),
    array('text','iflychat_public_chatroom_header'),
    array('text','iflychat_chat_list_header')
  );

  if($modSettings['iflychat_show_admin_list'] == '1')
  {
    $support_config_vars = array(
      array('text', 'iflychat_support_chat_init_label'),
      array('text', 'iflychat_support_chat_box_header'),
      array('text', 'iflychat_support_chat_box_company_name'),
      array('text', 'iflychat_support_chat_box_company_tagline'),
      array('select', 'iflychat_support_chat_auto_greet_enable',array('1'=>$txt['iflychat_option_yes'],'2'=>$txt['iflychat_option_no'])),
      array('large_text', 'iflychat_support_chat_auto_greet_message'),
      array('text', 'iflychat_support_chat_auto_greet_time'),
      array('text', 'iflychat_support_chat_init_label_off'),
      array('large_text', 'iflychat_support_chat_offline_message_desc'),
      array('text', 'iflychat_support_chat_offline_message_label'),
      array('text', 'iflychat_support_chat_offline_message_contact'),
      array('text', 'iflychat_support_chat_offline_message_send_button'),
      array('text', 'iflychat_support_chat_offline_message_email')
    );
    
    $config_vars = array_merge($config_vars, $support_config_vars);
  }
  
  $config_vars_align = array(
    array('large_text','iflychat_stop_word_list'),
    array('select','iflychat_use_stop_word_list',array('1'=>$txt['use_stop_word_list_no_filter'],'2'=>$txt['use_stop_word_list_in_public'],'3'=>$txt['use_stop_word_list_in_private'],'4'=>$txt['use_stop_word_list_in_all'])),
    array('select','iflychat_stop_links',array('1'=>$txt['iflychat_stop_links_no_block'],'2'=>$txt['iflychat_stop_links_block_public'],'3'=>$txt['iflychat_stop_links_block_private'],'4'=>$txt['iflychat_stop_links_block_all'])),                     
    array('select','iflychat_allow_anon_links',array('1'=>$txt['iflychat_option_yes'],'2'=>$txt['iflychat_option_no'])),
    array('select','iflychat_allow_render_images',array('1'=>$txt['iflychat_option_yes'],'2'=>$txt['iflychat_option_no'])),
    array('select','iflychat_enable_file_attachment',array('1'=>$txt['iflychat_option_yes'],'2'=>$txt['iflychat_option_no'])),
    array('select','iflychat_allow_single_message_delete',array('1'=>$txt['iflychat_allow_all_users'],'2'=>$txt['iflychat_allow_moderators'],'3'=>$txt['iflychat_disable'])),
    array('select','iflychat_allow_clear_room_history',array('1'=>$txt['iflychat_allow_all_users'],'2'=>$txt['iflychat_allow_moderators'],'3'=>$txt['iflychat_disable'])),
    array('select', 'iflychat_path_visibility', array('1' => $txt['iflychat_all_pages_except_listed'], '2' => $txt['iflychat_only_listed_pages'])),
    array('large_text', 'iflychat_path_pages')
  );
  
  $config_vars = array_merge($config_vars, $config_vars_align);
  
  $context['post_url'] = $scripturl . '?action=iflychat;save';
  $context['settings_title'] = $txt['iflychat'];
  // Saving?
  if (isset($_GET['save']))
  {
    checkSession();
    
    if( ! empty($_POST['iflychat_external_api_key']))
    {
      $data = array(
        'api_key' => $_POST['iflychat_external_api_key'],
        'enable_chatroom' => $_POST['iflychat_enable_chatroom'],
        'theme' => ($_POST['iflychat_theme'] == 1)?'light':'dark',
        'smileys' => $_POST['iflychat_enable_smileys'],
        'log_chat' => $_POST['iflychat_log_messages'],
        'notify_sound' => $_POST['iflychat_notification_sound'],
        'chat_topbar_color' => $_POST['iflychat_chat_top_bar_color'],
        'chat_topbar_text_color' => $_POST['iflychat_chat_topbar_text_color'],
        'font_color' => $_POST['iflychat_font_color'],
        'chat_list_header' => $_POST['iflychat_chat_list_header'],
        'public_chatroom_header' => $_POST['iflychat_public_chatroom_header'],
        //'rel' => $_POST['iflychat_rel'],
        'version' => 'SMF-1.0.0',
        'show_admin_list' => $_POST['iflychat_show_admin_list'],
        'clear' => $_POST['iflychat_allow_single_message_delete'],
        'delmessage' => $_POST['iflychat_allow_clear_room_history'],
        'ufc' => $_POST['iflychat_allow_user_font_color'],
        'guest_prefix' => ($_POST['iflychat_anon_prefix'] . " "),
        'enable_guest_change_name' => $_POST['iflychat_anon_change_name'],
        'use_stop_word_list' => $_POST['iflychat_use_stop_word_list'],
        'stop_word_list' => $_POST['iflychat_stop_word_list'],
        'file_attachment' => ($_POST['iflychat_enable_file_attachment'] == "1")?'1':'2',
        'mobile_browser_app' => ($_POST['iflychat_enable_mobile_browser_app'] == "1")?'1':'2'
      );
      //query for saving settings on remote server
      $post_data = http_build_query($data, '', '&', PHP_QUERY_RFC1738);

      $result = fetch_web_data(SMFCHAT_EXTERNAL_HOST . ':' . SMFCHAT_EXTERNAL_PORT .  '/z/', $post_data, false, 3);
      
    }
    $save_vars = $config_vars;
    saveDBSettings($save_vars);
    redirectexit('action=iflychat');
  }
  prepareDBSettingContext($config_vars);
  loadTemplate('Admin');
}
