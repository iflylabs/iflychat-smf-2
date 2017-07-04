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

require_once('SSI.php');


define('SMFCHAT_EXTERNAL_HOST', 'http://api.iflychat.com');
define('SMFCHAT_EXTERNAL_PORT', '80');
define('SMFCHAT_EXTERNAL_A_HOST', 'https://api.iflychat.com');
define('SMFCHAT_EXTERNAL_A_PORT', '443');

if(!defined('SMFCHAT_EXTERNAL_CDN_HOST')){
  define('SMFCHAT_EXTERNAL_CDN_HOST', 'cdn.iflychat.com');
}

loadLanguage('iflychat');

define('IFLYCHAT_PLUGIN_VERSION', 'SMF-2.0.0');

//adding action for iflychat auth and initiation
function iflychat_add_hook(&$actionArray)
{
    global $context;
    if (!$context['user']['is_guest']) {
        $actionArray['logout'] = array($sourcedir . '/Subs-iflychat.php', 'iflychat_token_destroy');
        $actionArray['iflychat-get'] = array($sourcedir . '/Subs-iflychat.php', 'iflychat_get_auth');
        if (iflychat_check_chat_admin()) {
            $actionArray['iflychat'] = array($sourcedir . '/Subs-iflychat.php', 'iflychat_settings');
        }
    }
    //$actionArray['iflychat-mob-auth'] = array($sourcedir .'/Subs-iflychat.php', 'iflychat_mob_auth');

    $actionArray['iflychat-init'] = array($sourcedir . '/Subs-iflychat.php', 'iflychat_init');
    $actionArray['iflychat-dashboard'] = array($sourcedir . '/Subs-iflychat.php', 'iflychat_dashboard');
}
function iflychat_dashboard(){
    $iflychat_host = SMFCHAT_EXTERNAL_A_HOST;
    $host = explode("/", $iflychat_host);
    $host_name = $host[2];
        if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
        $token = $_SESSION['token'];
    } else if (iflychat_check_chat_admin()) {
        $token = iflychat_get_auth()->key;
    }else{
        $token = "token_error";
    }
    redirectexit( $dashboardUrl = "https://cdn.iflychat.com/apps/dashboard/#/settings/app?sessid=" . $token . "&hostName=" . $host_name . "&hostPort=" . SMFCHAT_EXTERNAL_A_PORT);
}
function iflychatAddPermissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
    $permissionList['membergroup'] = array_merge(
        array(
            'user_iflychatpage' => array(false, 'iflychat', 'iflychat'),
            'user_iflychatshow' => array(false, 'iflychat', 'iflychat'),
        ),
        $permissionList['membergroup']
    );
}

function iflychat_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
    global $context;
    $permissionList['membergroup'] += array(
        'user_iflychatpage' => array(false, 'iflychat', 'iflychat'),
        'user_iflychatshow' => array(false, 'iflychat', 'iflychat'),
    );
}

function iflychat_template_init($buffer)
{
    global $context, $settings, $scripturl, $modSettings, $user_info;
    if (iflychat_check_chat_admin()) {
        $search_menu = '<li id="button_profile">';
        $replace_menu = '<li id="button_iflychat">
						<a class="firstlevel" href="' . $scripturl . '?action=iflychat">
							<span class="firstlevel">iFlyChat</span>
						</a>
						<ul>
							<li>
							<a class="secondlevel" href="' . $scripturl . '?action=iflychat" >
							<span class="firstlevel">Plugin Settings</span></a>
							</li>
							<li>
							<a class="secondlevel" href="' . $scripturl . '?action=iflychat-dashboard" target="_blank" >
							<span class="firstlevel">Open App Dashboard in new tab</span></a>
							</li>
							</ul>
					</li>
					<li id="button_profile">';

        $buffer = str_replace($search_menu, $replace_menu, $buffer);
    }
    if (iflychat_path_check()) {
        if (iflychat_get_option('iflychat_popup_chat') == '1') {
            $settings['iflychat_popup'] = '/iflychat/js/iflychat-popup.js';
        } else if (iflychat_get_option('iflychat_popup_chat') == '2') {
            if (!($_GET['action'] == 'admin')) {
                $settings['iflychat_popup'] = '/iflychat/js/iflychat-popup.js';
            }

        } else if ((iflychat_get_option('iflychat_popup_chat') == '3' || iflychat_get_option('iflychat_popup_chat') == '4') && iflychat_path_check()) {
            $settings['iflychat_popup'] = '/iflychat/js/iflychat-popup.js';
        }
        $search_footer = "</body></html>";
        $replace_footer = '
					<script type="text/javascript" src="' . $scripturl . '?action=iflychat-init"></script>
					<script type="text/javascript" src="' . $settings['default_theme_url'] . '/iflychat/js/jquery.min.js"></script>
					<script type="text/javascript" src="' . $settings['default_theme_url'] . '/iflychat/js/iflychat.admin.script.js"></script>
					<script type="text/javascript" src="' . $settings['default_theme_url'] . '/iflychat/js/load.js"></script>
					</body></html>;';
        if (isset($settings['iflychat_popup'])) {
            $replace_footer .= '<script type="text/javascript" src="' . $settings['default_theme_url'] . $settings['iflychat_popup'] . '"></script>';
        }
        $buffer = str_replace($search_footer, $replace_footer, $buffer);
    }
    return $buffer;
}

function iflychat_get_user_auth()
{
    global $user_info;

    $admin_check = FALSE;

    if (iflychat_check_chat_admin()) {
        $chat_role = 'admin';
    } else if (iflychat_check_chat_moderator()) {
        $chat_role = "moderator";
    } else {
        $chat_role = "participant";
    }
    $groups = list_getGroups(0);
    $role = array();
    $user_roles = array();

    foreach ($groups as $group) {
        if (in_array($group['id'], $user_info['groups'])) {
            $role[$group['id']] = $group['name'];
        }
    }


    foreach ($role as $rkey => $rvalue) {
        $user_roles[$rvalue] = $rvalue;
    }

    //if (iflychat_get_current_guest_name() && $user_info['username']) {
    $user_data = array(
        'user_id' => $user_info['id'],
        'user_name' => iflychat_get_user_name(),
        'user_roles' => $user_roles,
        'chat_role' => $chat_role,
        'user_list_filter' => 'all',
        'user_status' => TRUE,
    );
    $user_data['user_avatar_url'] = iflychat_get_user_pic_url();
    $user_data['user_profile_url'] = iflychat_get_user_profile_url();

    //Added allRoles if chat_role is admin or moderator
    if ($chat_role == 'admin' || $chat_role == 'moderator') {
        $user_data['user_site_roles'] = $role;
    }

    /////////////////////////---review---//////////////

    $user_data['user_groups'] = $user_info['groups'];

    if ((iflychat_get_option('iflychat_enable_friends') == '1')) { // filtering based on buddypress friends

        $user_data['user_list_filter'] = 'friend';
        //loadMemberData($user_info['buddies']);
        $final_list = array();
        $final_list['1']['name'] = 'friend';
        $final_list['1']['plural'] = 'friends';
        $i = 0;

        foreach ($user_info['buddies'] as $uid) {
            $final_list['1']['valid_uids'][$i] = $uid;
            $i++;
        }
        $user_data['user_relationships'] = $final_list;
    } else {
        $user_data['user_list_filter'] = 'all';
    }

    return $user_data;
}

function iflychat_get_auth()
{
    global $modSettings, $user_info;

    header("Content-Type: application/json");

    if (empty($modSettings['iflychat_external_api_key'])) {
        return null;
    }
    $data = array(
        'api_key' => $modSettings['iflychat_external_api_key'],
        'app_id' => iflychat_get_option('iflychat_external_app_id') ? iflychat_get_option('iflychat_external_app_id') : '',
        'version' => IFLYCHAT_PLUGIN_VERSION,
    );

    $user_data = iflychat_get_user_auth();

    $data = array_merge($data, $user_data);

    $_SESSION['user_data'] = json_encode($user_data);

    $post_data = http_build_query($data, '', '&');

    $result = fetch_web_data(SMFCHAT_EXTERNAL_A_HOST . ':' . SMFCHAT_EXTERNAL_A_PORT . '/api/1.1/token/generate', $post_data);


    if (empty($result) || !isset($result)) {
        $var = array(
            'user_name' => !($user_info['is_guest']) ? $user_info['username'] : iflychat_get_current_guest_name(),
            'user_id' => !($user_info['is_guest']) ? $user_info['id'] : iflychat_get_current_guest_id()
        );

        exit(json_encode($var));
    }

    $jsonDataObj = json_decode($result);

    $_SESSION['token'] = $jsonDataObj->key;

//    $jsonDataAssoc = json_decode($result, TRUE);

    exit($result);

}


function iflychat_init()
{
    global $context, $scripturl;

    header('Content-type: application/javascript');
    $output = '';
    if (!$context['user']['is_guest']) {
        $user_data = json_encode(iflychat_get_user_auth());
        if (iflychat_get_option('iflychat_session_caching') == '1' && isset($_SESSION['user_data']) && $_SESSION['user_data'] == $user_data) {
            if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
                $iflychat_settings['iflychat_auth_token'] = $_SESSION['token'];
                $output .= 'var iflychat_auth_token = "' . $_SESSION['token'] . '";';
            }
        }
        $iflychat_auth_url = $scripturl . '?action=iflychat-get';
        $output .= 'var iflychat_auth_url = "' . $iflychat_auth_url . '";';
    }


    $output .= 'var iflychat_external_cdn_host = "' . SMFCHAT_EXTERNAL_CDN_HOST . '";';
    $output .= 'var app_id = "' . iflychat_get_option('iflychat_external_app_id') . '";';
    exit($output);
}

function iflychat_settings()
{
    global $txt, $scripturl, $context, $settings, $sc, $modSettings;

    $context['sub_template'] = 'show_settings';
    $context['page_title'] = $txt['iflychat'];
    $context['button'] = $txt['new_button'];
    $context['linktree'][] = array(
        'url' => $scripturl . '?action=iflychat',
        'name' => $txt['iflychat']
    );

    $config_vars = array(

        array('text', 'iflychat_external_api_key'),

        array('text', 'iflychat_external_app_id'),

        array('select', 'iflychat_enable_friends', array('1' => $txt['iflychat_option_buddyPress'],'2' => $txt['iflychat_option_no'])),

        array('select', 'iflychat_popup_chat', array('1' => $txt['iflychat_popup_chat_everywhere'],
            '2' => $txt['iflychat_popup_chat_frontend_only'],
            '3' => $txt['iflychat_popup_chat_everywhere_except_those_listed'],
            '4' => $txt['iflychat_popup_chat_only_the_listed_pages'],
            '5' => $txt['iflychat_popup_chat_disable'])),

        array('large_text', 'iflychat_path_pages'),

        array('large_text', 'iflychat_chat_moderators_array'),

        array('large_text', 'iflychat_chat_admins_array'),

        array('select', 'iflychat_session_caching', array('2' => $txt['iflychat_session_caching_no'],'1' => $txt['iflychat_session_caching_yes']))
    );

    //$config_vars = array_merge($config_vars, $config_vars_align);

    $context['post_url'] = $scripturl . '?action=iflychat;save';
    $context['settings_title'] = $txt['iflychat'];
    if (isset($_GET['save'])) {
        checkSession();
        if (!empty($_POST['iflychat_external_api_key']) && !empty($_POST['iflychat_external_app_id'])) {

            checkSettings($_POST['iflychat_external_api_key'], $_POST['iflychat_external_app_id'], $config_vars);

        } else{
            echo("<script>console.log('App id not available');</script>");
            fatal_error('Please enter Api key', false);
        }

        redirectexit('action=iflychat');
    }

    prepareDBSettingContext($config_vars);
    loadTemplate('Admin');
}

function checkSettings($key, $appid, $config_vars)
{
    global $user_info;

    $data = array(
        'api_key' => $key,
        'app_id' => $appid,
        'user_id' => $user_info['id'],
        'user_name' => iflychat_get_user_name(),
    );

    $data = json_encode($data);
    $result = extendedHttpRequest('https://api.iflychat.com/api/1.1/token/generate', $data);

    //echo($key." ".$appid." ".$user_info['id']." ".iflychat_get_user_name());

    $save_vars = $config_vars;
    saveDBSettings($save_vars);

    if ($result->code == 200) {
        echo('200 ok');
    } else if ($result->code == 403) {
        fatal_error('Invalid API key', false);
        exit();
    } else if ($result->code == 503)
        fatal_error('503 Error. Service Unavailable.', false);
    else
        fatal_error('Something Went Wrong. Please Try Again', false);

}
