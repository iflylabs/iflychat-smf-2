<?php

if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
  require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
  die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/Subs-iflychat.php', TRUE);
add_integration_function('integrate_actions', 'iflychat_add_hook', TRUE);
add_integration_function('integrate_buffer', 'iflychat_template_init', TRUE);
add_integration_function('inetgrate_load_permissions', 'iflychat_load_permissions', TRUE);
  
?>