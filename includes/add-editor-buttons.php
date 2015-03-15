<?php 

if ( is_admin() ) {

/******************************
* Add shortcode buttons
*******************************/
function yada_wiki_add_buttons() {  
	
	// Only add buttons if editing a Yada Wiki post
	if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {  

		// Snippet adapted from Ross McKay - http://snippets.webaware.com.au/snippets/wordpress-admin_init-hook-and-the-elusive-typenow/
		global $typenow;
		if (empty($typenow)) {
            // try to pick it up from the query string
            if (!empty($_GET['post'])) {
                $post = get_post($_GET['post']);
                $typenow = $post->post_type;
                $typenow = sanitize_text_field( $typenow );
            }
            // try to pick it up from the quick edit AJAX post
            elseif (!empty($_POST['post_ID'])) {
                $post = get_post($_POST['post_ID']);
                $typenow = $post->post_type;
                $typenow = sanitize_text_field( $typenow );
            }
            // added by David McCan
            elseif ( is_edit_page('new') ) {
            	if(empty($_GET['post_type'])) {
            		$typenow = "post";	
            	}
            	else {
            		$type_test = sanitize_text_field( $_GET['post_type'] );
            		$typenow = $type_test;
            	}
            }
        }		
		
		if (is_edit_page() && "yada_wiki" == $typenow){
			add_filter('mce_external_plugins', 'yada_wiki_add_plugin');  
			add_filter('mce_external_plugins', 'yada_wiki_toc_add_plugin');  
			add_filter('mce_buttons', 'yada_wiki_register_button');  
			add_filter('mce_buttons', 'yada_wiki_toc_register_button');  
		}		
 
   }  
} 

/******************************
* Register buttons
*******************************/
function yada_wiki_register_button($buttons) {  

	array_push($buttons, "yada_wiki");  
	return $buttons;  
} 
function yada_wiki_toc_register_button($buttons) {  

	array_push($buttons, "yada_wiki_toc");  
	return $buttons;  
} 

/******************************
* Load JS and graphic
*******************************/
function yada_wiki_add_plugin($plugin_array) {  

	$plugin_array['yada_wiki'] = plugins_url( '../js/editorAddWikiButton.js' , __FILE__ );
	return $plugin_array;  
}  
function yada_wiki_toc_add_plugin($plugin_array) {  

	$plugin_array['yada_wiki_toc'] = plugins_url( '../js/editorAddWikiTocButton.js' , __FILE__ );
	return $plugin_array;  	
}  

/********************************************************
* Funciton from Ohad Raz - https://en.bainternet.info/
********************************************************/
function is_edit_page($new_edit = null){
    global $pagenow;
    
    if (!is_admin()) return false;

    if($new_edit == "edit")
        return in_array( $pagenow, array( 'post.php',  ) );
    elseif($new_edit == "new") //check for new post page
        return in_array( $pagenow, array( 'post-new.php' ) );
    else //check for either new or edit
        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

}

