<?php 

if ( is_admin() ) {

/******************************
* Add shortcode button
*******************************/
function yada_wiki_add_button() {  

   if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {  

		add_filter('mce_external_plugins', 'yada_wiki_add_plugin');  
		add_filter('mce_buttons_2', 'yada_wiki_register_button');  
   }  
} 

/******************************
* Register button
*******************************/
function yada_wiki_register_button($buttons) {  

	array_push($buttons, "yada_wiki");  
	return $buttons;  
} 

/******************************
* Load JS and graphic
*******************************/
function yada_wiki_add_plugin($plugin_array) {  

	$plugin_array['yada_wiki'] = plugins_url( '../js/editorAddButton.js' , __FILE__ );
	return $plugin_array;  
}  

}

