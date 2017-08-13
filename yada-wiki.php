<?php
/*
Plugin Name: Yada Wiki
Plugin URI: https://www.webtng.com/yada-wiki-documentation/
Description: This plugin provides a simple wiki for your WordPress site.
Version: 3.0
Author: David McCan
Author URI: https://www.webtng.com/author/
License: GPL2
*/

/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

/************************************************
* Registers the wiki custom post type
* Registers the wiki taxonomy category and tags
************************************************/
include('includes/register-wiki-cpt.php'); 

/******************************
* On plugin activation
*******************************/
function yada_wiki_on_activation() {
	register_yada_wiki();
	register_yada_wiki_cats();
	register_yada_wiki_tags();
	
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'yada_wiki_on_activation');

/************************************************
* Functions to handle the wiki link shortcodes
************************************************/
if ( ! is_admin() ) {
    include('includes/yadawiki-frontend.php'); 
	add_action( 'wp_enqueue_scripts', 'yada_wiki_scripts' );
    add_shortcode('yadawiki', 'yada_wiki_shortcode');
    add_shortcode('yadawikitoc', 'yada_wiki_toc_shortcode');
    add_shortcode('yadawiki-index', 'yada_wiki_index_shortcode');
}

/************************************************
* Honor wiki settings and link to settings page
************************************************/
include('includes/yadawiki-honor-settings.php'); 
add_action('init', 'yadawiki_load_settings');
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'yada_wiki_add_settings_link' );

/************************************************
* Registers the wiki widget
************************************************/
include('includes/yadawiki-widgets.php'); 
add_action('widgets_init', 'yadawiki_toc_widget_register_widgets');

/****************************************************************
* Functions to add shortcode buttons to editor and settings page
****************************************************************/
if ( is_admin() ) {
    include('includes/yadawiki-backend.php'); 
    include('includes/yadawiki-settings.php'); 
	add_action( 'admin_enqueue_scripts', 'yada_wiki_admin', 10 ); 
    add_action( 'wp_ajax_yada_wiki_suggest', 'yada_wiki_suggest_callback' );
	add_action( 'admin_menu', 'yada_wiki_add_admin_menu' );
	add_action( 'admin_init', 'yada_wiki_settings_init' );
}
