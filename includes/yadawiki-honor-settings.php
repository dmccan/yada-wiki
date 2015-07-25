<?php
/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

/******************************
* Honor / load settings
*******************************/
function yadawiki_load_settings(  ) { 
	
	$options = get_option( 'yada_wiki_settings' );
	$commentOptionsEnabled = false;
	//$commentsChecked = false;
	//$trackbacksChecked = false;

	if ( isset($options['yada_wiki_checkbox_comment_options']) ) {
		$commentOptionsEnabled = true;
		//echo __( '<div class="updated">Comment options enabled.</div>', 'yada_wiki_domain' );
	}
	/*
	if ( isset($options['yada_wiki_checkbox_field_1']) ) {
		$commentsChecked = true;
	}
	if ( isset($options['yada_wiki_checkbox_field_2']) ) {
		$trackbacksChecked = true;
	}
	*/
	if ( $commentOptionsEnabled == true ) {
		add_post_type_support( 'yada_wiki', 'comments' );
	}
	else {
		remove_post_type_support( 'yada_wiki', 'comments' );
		//echo __( '<div class="updated">Comments Disabled for Wiki Pages.</div>', 'yada_wiki_domain' );
	}
}

function yada_wiki_set_comment_defaults( $data ) {

	if( $data['post_type'] == 'yada_wiki' && $data['post_status'] == 'auto-draft' ) {
		
		$options = get_option( 'yada_wiki_settings' );
		$commentOptionsEnabled = false;
		$commentsChecked = false;
		$trackbacksChecked = false;
	
		if ( isset($options['yada_wiki_checkbox_comment_options']) ) {
			$commentOptionsEnabled = true;
		}
		if ( isset($options['yada_wiki_checkbox_comments_setting']) ) {
			$commentsChecked = true;
		}
		if ( isset($options['yada_wiki_checkbox_trackbacks_setting']) ) {
			$trackbacksChecked = true;
		}
	
		if ( $commentOptionsEnabled == true ) {
			if ( $commentsChecked == true ) {
				$data['comment_status'] = "open";
				//echo __( '<div class="updated">Comments checked.</div>', 'yada_wiki_domain' );
			}
			else {
				$data['comment_status'] = "closed";
			}
			if ( $trackbacksChecked == true ) {
				$data['ping_status'] = "open";
				//echo __( '<div class="updated">Trackbacks checked.</div>', 'yada_wiki_domain' );
			}
			else {
				$data['ping_status'] = "closed";
			}
		}
		else {
	        $data['comment_status'] = "closed";
	        $data['ping_status'] = "closed";			
		}
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'yada_wiki_set_comment_defaults' );
?>