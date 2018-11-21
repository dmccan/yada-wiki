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

	if ( isset($options['yada_wiki_checkbox_comment_options']) ) {
		$commentOptionsEnabled = true;
	}
	if ( $commentOptionsEnabled == true ) {
		add_post_type_support( 'yada_wiki', 'comments' );
	}
	else {
		remove_post_type_support( 'yada_wiki', 'comments' );
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
			}
			else {
				$data['comment_status'] = "closed";
			}
			if ( $trackbacksChecked == true ) {
				$data['ping_status'] = "open";
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

function yada_wiki_use_gutenberg_block ( $can_edit, $post_type ) { 

	if ($post_type == 'yada_wiki') {
		$options = get_option( 'yada_wiki_settings' );
		$useGutenberg = isset($options['yada_wiki_checkbox_use_gutenberg_setting']) && !empty($options['yada_wiki_checkbox_use_gutenberg_setting']) ? true : false;
		if ( $useGutenberg ) {
			$can_edit = true;
		} else {
			$can_edit = false;
		}		
	} 
	return $can_edit;
}

function yada_wiki_use_classic_editor ( $can_edit, $post_type ) { 

	if ($post_type == 'yada_wiki') {
		$options = get_option( 'yada_wiki_settings' );
		$useGutenberg = isset($options['yada_wiki_checkbox_use_gutenberg_setting']) && !empty($options['yada_wiki_checkbox_use_gutenberg_setting']) ? true : false;
		if ( $useGutenberg == false) {
			//remove_filter( 'replace_editor', 'gutenberg_init' );
			$can_edit = false;
		} else {
			$can_edit = true;
		}		
	} 
	return $can_edit;
}
?>
