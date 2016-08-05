<?php
/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

if ( is_admin() ) {

/******************************
* Add link to settings page
*******************************/	
function yada_wiki_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=yada_wiki">'. __( 'Settings' ).'</a>';
    array_push( $links, $settings_link );
  	return $links;
}
	
/***************************************
* Settings template code generated from 
* http://wpsettingsapi.jeroensormani.com
***************************************/

/******************************
* Add settings menu item
*******************************/
function yada_wiki_add_admin_menu() { 
	add_submenu_page( 'options-general.php', 'Yada Wiki', 'Yada Wiki', 'manage_options', 'yada_wiki', 'yada_wiki_options_page' );
}

/******************************
* Add settings fields
*******************************/
function yada_wiki_settings_init() { 

	register_setting( 'pluginPage', 'yada_wiki_settings' );

	add_settings_section(
		'yada_wiki_pluginPage_section', 
		__( 'Wiki Options', 'yada_wiki_domain' ), 
		'yada_wiki_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'yada_wiki_checkbox_comment_options', 
		__( 'Enable Comment Options', 'yada_wiki_domain' ), 
		'yada_wiki_checkbox_comment_options_render', 
		'pluginPage', 
		'yada_wiki_pluginPage_section' 
	);

	add_settings_field( 
		'yada_wiki_checkbox_comments_setting', 
		__( 'On new wiki page: comments checked by default', 'yada_wiki_domain' ), 
		'yada_wiki_checkbox_comments_setting_render', 
		'pluginPage', 
		'yada_wiki_pluginPage_section' 
	);

	add_settings_field( 
		'yada_wiki_checkbox_trackbacks_setting', 
		__( 'On new wiki page: trackbacks and pingbacks checked by default', 'yada_wiki_domain' ), 
		'yada_wiki_checkbox_trackbacks_setting_render', 
		'pluginPage', 
		'yada_wiki_pluginPage_section' 
	);

	add_settings_field( 
		'yada_wiki_checkbox_editor_buttons_setting', 
		__( 'Show Wiki Shortcode Buttons for Regular Posts and Pages', 'yada_wiki_domain' ), 
		'yada_wiki_checkbox_editor_buttons_setting_render', 
		'pluginPage', 
		'yada_wiki_pluginPage_section' 
	);

}

/************************************
* Setting to enable comment options
************************************/
function yada_wiki_checkbox_comment_options_render() { 

	$options = get_option( 'yada_wiki_settings' );
	?>
	<input type='checkbox' name='yada_wiki_settings[yada_wiki_checkbox_comment_options]' <?php checked( isset($options['yada_wiki_checkbox_comment_options']), 1 ); ?> value='1'>
	<?php

}

/************************************
* Comments checked by default
************************************/
function yada_wiki_checkbox_comments_setting_render() { 

	$options = get_option( 'yada_wiki_settings' );
	?>
	<input type='checkbox' name='yada_wiki_settings[yada_wiki_checkbox_comments_setting]' <?php checked( isset($options['yada_wiki_checkbox_comments_setting']), 1 ); ?> value='1'>
	<?php

}

/***************************************************
* Trackbacks and pingbacks checked by default
***************************************************/
function yada_wiki_checkbox_trackbacks_setting_render() { 

	$options = get_option( 'yada_wiki_settings' );
	?>
	<input type='checkbox' name='yada_wiki_settings[yada_wiki_checkbox_trackbacks_setting]' <?php checked( isset($options['yada_wiki_checkbox_trackbacks_setting']), 1 ); ?> value='1'>
	<?php

}

/***************************************************
* Show editor buttons on posts and pages
***************************************************/
function yada_wiki_checkbox_editor_buttons_setting_render() { 

	$options = get_option( 'yada_wiki_settings' );
	?>
	<input type='checkbox' name='yada_wiki_settings[yada_wiki_checkbox_editor_buttons_setting]' <?php checked( isset($options['yada_wiki_checkbox_editor_buttons_setting']), 1 ); ?> value='1'>
	<?php

}

/******************************
* Process settings
*******************************/
function yada_wiki_settings_section_callback() { 
}

/******************************
* Rengder settings form
*******************************/
function yada_wiki_options_page() { 

	?>
	<div style="padding:10px;">
		<h1>Yada Wiki</h1>
		<div style="background-color:#ffffff; padding:30px; width:325px; margin-top:25px;">
		<form action="options.php" id="yadaWikiForm" method="post" onsubmit="doOptionsCheck()">
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>		
		</form>
		<div style="text-align: center; width:75%;">
			<?php _e('Comment options must be enabled<br />for defauts to be set.<br /><br />Remember, only wiki links can be<br />used with the wiki shortcodes.', 'yada_wiki_domain') ?>
		</div>
		</div>			
	</div>
	<script type="text/javascript">
		function doOptionsCheck() {
			if ( document.getElementsByName('yada_wiki_settings[yada_wiki_checkbox_comment_options]')[0].checked == false ) {
				document.getElementsByName('yada_wiki_settings[yada_wiki_checkbox_comments_setting]')[0].checked = false;
				document.getElementsByName('yada_wiki_settings[yada_wiki_checkbox_trackbacks_setting]')[0].checked = false;
			}
		}
	</script>
	<?php

}

}
?>