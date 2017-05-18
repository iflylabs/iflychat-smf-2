
jQuery(document).ready(function(){
    jQuery('[name = "iflychat_show_admin_list"]').change(function() {
        if (jQuery('[name = "iflychat_show_admin_list"]').val() == '1') {
            jQuery('.support').fadeIn();
        }
        else {
            jQuery('.support').hide();
        }
    });jQuery('[name = "iflychat_show_admin_list"]').change();
});