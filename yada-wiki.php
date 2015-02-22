<?php
/*
Plugin Name: Yada Wiki
Plugin URI: http://www.webtng.com/yada-wiki
Description: This plugin provides a simple wiki for your WordPress site.
Version: 1.0
Author: David McCan
Author URI: http://www.webtng.com/author/
License: GPL2
*/

/***************************************
* Abort if called outside of WordPress
***************************************/
if ( ! defined( 'WPINC' ) ) die;

/***************************************
* Registers the wiki custom post type
***************************************/
include('includes/register-wiki-cpt.php'); 
add_action( 'init', 'register_yada_wiki', 0 ); 

/************************************************
* Functions to handle the wiki link shortcode
************************************************/
include('includes/handle-shortcode.php'); 
add_shortcode('yada-wiki', 'yada_wiki_shortcode');

/************************************************
* Functions to add shortcode button to editor
************************************************/
if ( is_admin() ) {
	include('includes/add-editor-button.php'); 
	add_action( 'plugins_loaded', 'yada_wiki_add_button', 10 ); 
}
