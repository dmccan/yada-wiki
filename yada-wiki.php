<?php
/*
Plugin Name: Yada Wiki
Plugin URI: http://www.davidmccan.com/yada-wiki
Description: This plugin provides a simple wiki for your WordPress site.
Version: 1.0
Author: David McCan
Author URI: http://www.davidmccan.com/author/
License: GPL2
*/

/***************************************
* Abort if called outside of WordPress
***************************************/
if ( ! defined( 'WPINC' ) ) die;

/************************************************
* Registers the wiki custom post type
* Registers the wiki taxonomy category and tags
************************************************/
include('includes/register-wiki-cpt.php'); 
add_action( 'init', 'register_yada_wiki', 0 ); 
add_action( 'init', 'register_yada_wiki_cats', 0 ); 
add_action( 'init', 'register_yada_wiki_tags', 0 ); 

/******************************
* On plugin activation
*******************************/
function yada_wiki_init() {
	
	// This is done so we can flush the permalink rules when the Yada Wiki plugin is first activated.
	register_yada_wiki();
	register_yada_wiki_cats();
	register_yada_wiki_tags();
	
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'yada_wiki_init' );


/************************************************
* Functions to handle the wiki link shortcodes
************************************************/
include('includes/handle-shortcodes.php'); 
add_shortcode('yadawiki', 'yada_wiki_shortcode');
add_shortcode('yadawikitoc', 'yada_wiki_toc_shortcode');

/************************************************
* Functions to add shortcode buttons to editor
************************************************/
if ( is_admin() ) {
	include('includes/add-editor-buttons.php'); 
	add_action( 'plugins_loaded', 'yada_wiki_add_buttons', 10 ); 
}
