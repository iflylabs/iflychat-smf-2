var iflychat_bundle = document.createElement("SCRIPT");
iflychat_bundle.src = "//"+Drupal.settings.drupalchat.iflychat_external_cdn_host+"/js/iflychat-v2.min.js?app_id="+Drupal.settings.drupalchat.app_id;
iflychat_bundle.async="async";
document.body.appendChild(iflychat_bundle);