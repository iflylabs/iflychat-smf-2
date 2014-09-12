<?php

if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$newSettings = array(
	'iflychat_external_api_key'   =>  '',
	'iflychat_show_admin_list'    =>  '2',
	'iflychat_theme'			  =>  '1',
	'iflychat_notification_sound' =>  '1',
	'iflychat_user_picture'			=> '1',
	'iflychat_enable_smileys'		=> '1',
	'iflychat_enable_mobile_browser_app' => '1',
	'iflychat_log_messages'			=> '1',
	'iflychat_anon_prefix'			=> 'Guest',
	'iflychat_anon_use_name'		=> 'Name',
	'iflychat_anon_change_name'		=> '1',
	'iflychat_ext_d_i'				=> '',
	'iflychat_support_chat_init_label'	=> 'Chat with us',
	'iflychat_support_chat_box_header'	=> 'Support',
	'iflychat_support_chat_box_company_name'	=>'Support Team',
	'iflychat_support_chat_box_company_tagline'	=> 'Ask us anything...',
	'iflychat_support_chat_auto_greet_enable'	=>'1',
	'iflychat_support_chat_auto_greet_message'	=>'Hi there! Welcome to our website. Let us know if you have any query!',
	'iflychat_support_chat_auto_greet_time'		=>'1',
	'iflychat_support_chat_init_label_off'	=>'Leave Message',
	'iflychat_support_chat_offline_message_desc'	=>'Hello there. We are currently offline. Please leave us a message. Thanks.',
	'iflychat_support_chat_offline_message_label'	=>'Message',
	'iflychat_support_chat_offline_message_contact'	=>'Contact Details',
	'iflychat_support_chat_offline_message_send_button'	=>'Send Message',
	'iflychat_support_chat_offline_message_email'	=>'',
	'iflychat_enable_chatroom'	=>'1',
	'iflychat_stop_word_list'	=>'asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk',
	'iflychat_use_stop_word_list'	=> '1',
	'iflychat_stop_links'	=> '1',
	'iflychat_allow_anon_links'	=>'1',
	'iflychat_allow_render_images'	=>'1',
	'iflychat_enable_file_attachment'	=>'1',
	'iflychat_allow_single_message_delete'	=>'1',
	'iflychat_allow_clear_room_history'	=>'1',
	'iflychat_allow_user_font_color'	=>'1',
	'iflychat_path_visibility'	=>'1',
	'iflychat_path_pages'	=>' ',
	'iflychat_chat_top_bar_color'		=> '#222222',
	'iflychat_chat_top_bar_text_color'		=> '#FFFFFF',
	'iflychat_chat_font_color'					=> '#222222',
	'iflychat_public_chatroom_header'		=> 'Public Chatroom',
	'iflychat_chat_list_header'				=>'Chat',
	'iflychat_minimize_chat_user_list'	=> '2',
	'iflychat_enable_search_bar'		=> '1',
	'iflychat_enable_friends'			=> '2',
);

updateSettings($newSettings);