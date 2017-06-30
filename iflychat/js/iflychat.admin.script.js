jQuery(document).ready(function($) {
	//$('#message').hide();
  
  $("#iflychat_popup_chat").change(function() {
    if (($("#iflychat_popup_chat").val() == '3') || ($("#iflychat_popup_chat").val() == '4')) {
	  $("#iflychat_path_pages").show();
	  $('label[for="iflychat_path_pages"]').show();
	}
	else if(($("#iflychat_popup_chat").val() == '5'))
	{
		$("#iflychat_path_pages").hide();
	  	$('label[for="iflychat_path_pages"]').hide();
	}
	else {
		$("#iflychat_path_pages").empty();
	  $("#iflychat_path_pages").hide();
	  $('label[for="iflychat_path_pages"]').hide();
	}

  });

  $("#iflychat_popup_chat").change();


});