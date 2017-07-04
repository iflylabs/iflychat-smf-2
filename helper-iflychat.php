<?php

require_once('LogInOut.php');
include_once "../SSI.php";
function iflychat_get_option($optionName)
{
    global $modSettings;
    if (isset($modSettings[$optionName])) {
        return $modSettings[$optionName];
    } else {
        return;
    }
}

function iflychat_get_user_pic_url()
{
    global $user_info, $modSettings, $settings;

    $url = '';

    if ($modSettings['iflychat_theme'] == 1) {
        $iflychat_theme = 'light';
    } else {
        $iflychat_theme = 'dark';
    }
    $pUrl = $modSettings['avatar_url'] . '/' . $user_info['avatar']['url'];


    if (empty($user_info['avatar']['url']) && empty($user_info['avatar']['filename'])) {
        $url = "{$settings['default_theme_url']}/iflychat/themes/{$iflychat_theme}/images/default_avatar.png";
    } else if (!empty($user_info['avatar']['filename'])){
//        return ($modSettings['avatar_url'] . '/' . $user_info['avatar']['filename']);
        $idMember = $user_info['id'];
        $res = ssi_fetchMember([$idMember], null);
        return $res[$idMember]['avatar']['href'];
    } else if (substr($user_info['avatar']['url'], 0, 4) === "http") {
        return ($user_info['avatar']['url']);
    } else {
        return $pUrl;
    }
}

function iflychat_get_user_profile_url()
{
    global $user_info;
    $id = $user_info['id'];
    $upl = getUserUrl($id);
    if ($upl !== 'javascript:void(0);') {
        $pos = strpos($upl, ':');
        if ($pos !== false) {
            $upl = substr($upl, $pos + 1);
        }
    }
    return $upl;
}

function iflychat_compute_guest_session($id)
{
    global $modSettings;
    return md5(substr($modSettings['iflychat_external_api_key'], 0, 5) . $id);
}

function check_plain($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function iflychat_get_random_name()
{
    global $settings;
    $path = "{$settings['default_theme_url']}/iflychat/guest_names/iflychat_guest_random_names.txt";
    $f_contents = file($path);
    $line = trim($f_contents[rand(0, count($f_contents) - 1)]);
    return $line;
}

function getUserUrl($id)
{
    global $scripturl;
    if ($id != 0) {
        return $scripturl . '?action=profile;u=' . $id;
    } else {
        return 'javascript:void(0);';
    }
}

function iflychat_path_check()
{
    // /smftest/index.php?action=admin
    /*$page_match = FALSE;
    if (trim(iflychat_get_option('iflychat_path_pages')) !== '')
      {
      if(function_exists('mb_strtolower')) {
        $pages = mb_strtolower(iflychat_get_option('iflychat_path_pages'));
        $path = mb_strtolower($_SERVER['REQUEST_URI']);
      }
      else {
        $pages = strtolower(iflychat_get_option('iflychat_path_pages'));
        $path = strtolower($_SERVER['REQUEST_URI']);
      }
      $page_match = iflychat_match_path($path, $pages);
      $page_match = (iflychat_get_option('iflychat_path_visibility') === '1')?(!$page_match):$page_match;
    }
    else if(iflychat_get_option('iflychat_path_visibility') == 1){
      $page_match = TRUE;
    }
    return $page_match;*/

    $page_match = FALSE;
    if (trim(iflychat_get_option('iflychat_path_pages')) != '') {
        if (function_exists('mb_strtolower')) {
            $pages = mb_strtolower(iflychat_get_option('iflychat_path_pages'));
            $path = mb_strtolower($_SERVER['REQUEST_URI']);
        } else {
            $pages = strtolower(iflychat_get_option('iflychat_path_pages'));
            $path = strtolower($_SERVER['REQUEST_URI']);
        }
        $page_match = iflychat_match_path($path, $pages);
        if (iflychat_get_option('iflychat_popup_chat') == '3') $page_match = !$page_match;
        if (iflychat_get_option('ififlychat_match_pathlychat_popup_chat') == '4') $page_match = $page_match;
        //$page_match = (iflychat_get_option('iflychat_popup_chat') == '3') ? (!$page_match) : $page_match;
    } else if (iflychat_get_option('iflychat_popup_chat') == '1' || iflychat_get_option('iflychat_popup_chat') == '2') {
        $page_match = TRUE;
    }
    return $page_match;
}

function iflychat_match_path($path, $patterns)
{
    $to_replace = array(
        '/(\r\n?|\n)/',
        '/\\\\\*/',
    );
    $replacements = array(
        '|',
        '.*',
    );
    $patterns_quoted = preg_quote($patterns, '/');
    $regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';
    return (bool)preg_match($regexps[$patterns], $path);
}

function iflychat_is_user_loggedin()
{
    global $user_info;
    if ($user_info['is_guest'] != 1) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isSSL()
{
    global $scripturl;
    $ssl = explode(":", $scripturl);
    if ($ssl[0] === 'https') {
        return TRUE;
    }
}

function iflychat_check_chat_admin()
{
    global $user_info;
    if ($user_info['is_admin']) {
        return true;
    }
    $a = iflychat_get_option('iflychat_chat_admins_array');
    if (!empty($a) && ($user_info['id'])) {
        $a_names = explode(",", $a);
        foreach ($a_names as $an) {
            $aa = trim($an);
            if ($aa == $user_info['username']) {
                return TRUE;
                break;
            }
        }
    }
    return FALSE;
}

function friends_get_friend_user_ids($id)
{
    global $user_info;
    $a = $user_info['buddies'];

    if (!empty($a) && ($user_info['id'])) {
        $a_friendsNames = explode(",", $a);
        foreach ($a_friendsNames as $af) {
            $aa = trim($af);
            if ($aa == $user_info[$aa]) {
                return TRUE;
                break;
            }
        }
    }

}

function iflychat_get_user_name()
{
    global $user_info;
    if (iflychat_get_option('iflychat_use_name') == 1) {
        return $user_info['username'];
    }
    {
        return $user_info['name'];
    }
}

function iflychat_check_chat_moderator()
{
    global $user_info;
    $a = iflychat_get_option('iflychat_chat_moderators_array');

    if (!empty($a) && ($user_info['id'])) {
        $a_names = explode(",", $a);
        foreach ($a_names as $an) {
            $aa = trim($an);
            if ($aa == $user_info['username']) {
                return TRUE;
                break;
            }
        }
    }
    return FALSE;
}

function iflychat_token_destroy()
{
    $data = array(
        'api_key' => iflychat_get_option('iflychat_external_api_key')
    );
    $options = array(
        'method' => 'POST',
        'body' => $data,
        //'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
    );

    $options = json_encode($options);
    $result = extendedHttpRequest(SMFCHAT_EXTERNAL_A_HOST . ':' . SMFCHAT_EXTERNAL_A_PORT . '/api/1.1/token/' . $_SESSION['token'] . '/delete', $options);
//if(!is_wp_error($result) && $result['response']['code'] == 200) {
    //echo($_SESSION['token']." ".$result);exit();
    unset($_SESSION['token']);
    LogOut();
    //session_unset();

//}
}

function extendedHttpRequest($url, $data_json)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $res_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $result->code = $res_code;
    curl_close($ch);
    return $result;
}
