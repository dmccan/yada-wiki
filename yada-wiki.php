<?php
/**
 * Plugin Name: Yada Wiki
 * Plugin URI:  https://www.webtng.com/yada-wiki-documentation
 * Description: This plugin provides a simple wiki for your WordPress site.
 * Version:     3.1
 * Author:      David McCan
 * Author URI:  https://www.webtng.com
 * Text Domain: yada-wiki
 * Domain Path: /languages
 *
 * Yada Wiki provides a wiki post type, custom tags and categories, an index, and a table of contents option.
 * The plugin allows you to link your wiki pages together using the wiki page titles.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   YadaWiki
 * @version   3.1
 * @author    David McCan <dcmccan@gmail.com>
 * @copyright Copyright (c) 2015-2018, David McCan
 * @link      https://www.webtng.com/yada-wiki-documentation
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Singleton class that sets up and initializes the plugin.
 *
 * @since  3.1
 * @access public
 * @return void
 */
final class YadaWikiPlugin {

	/**
	 * Directory path to the plugin folder.
	 *
	 * @since  3.1
	 * @access public
	 * @var    string
	 */
	public $dir_path = '';

	/**
	 * Directory URI to the plugin folder.
	 *
	 * @since  3.1
	 * @access public
	 * @var    string
	 */
	public $dir_uri = '';

	/**
	 * JavaScript directory URI.
	 *
	 * @since  3.1
	 * @access public
	 * @var    string
	 */
	public $js_uri = '';

	/**
	 * CSS directory URI.
	 *
	 * @since  3.1
	 * @access public
	 * @var    string
	 */
	public $css_uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  3.1
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  3.1
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Magic method to output a string if trying to use the object as a string.
	 *
	 * @since  3.1
	 * @access public
	 * @return void
	 */
	public function __toString() {
		return 'yada-wiki';
	}

	/**
	 * Magic method to keep the object from being cloned.
	 *
	 * @since  3.1
	 * @access public
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Whoah, partner!', 'yada_wiki_domain' ), '3.1' );
	}

	/**
	 * Magic method to keep the object from being unserialized.
	 *
	 * @since  3.1
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Whoah, partner!', 'yada_wiki_domain' ), '3.1' );
	}

	/**
	 * Magic method to prevent a fatal error when calling a method that doesn't exist.
	 *
	 * @since  3.1
	 * @access public
	 * @return void
	 */
	public function __call( $method = '', $args = array() ) {
		_doing_it_wrong( "Yada_Wiki::{$method}", __( 'Method does not exist.', 'yada_wiki_domain' ), '3.1' );
		unset( $method, $args );
		return null;
	}

	/**
	 * Initial plugin setup.
	 *
	 * @since  3.1
	 * @access private
	 * @return void
	 */
	private function setup() {

		$this->dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->dir_uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );

		$this->js_uri  = trailingslashit( $this->dir_uri . 'js'  );
		$this->css_uri = trailingslashit( $this->dir_uri . 'css' );
	}

	/**
	 * Loads include and admin files for the plugin.
	 *
	 * @since  3.1
	 * @access private
	 * @return void
	 */
	private function includes() {

		// Load functions files.
		require_once( $this->dir_path . 'inc/functions-register-cpt.php' );
		require_once( $this->dir_path . 'inc/functions-settings-load.php' );
		require_once( $this->dir_path . 'inc/functions-widgets.php' );

		// Load public files.
		if ( ! is_admin() ) {
			require_once( $this->dir_path . 'inc/functions-public.php' );
		}

		// Load admin files.
		if ( is_admin() ) {
			require_once( $this->dir_path . 'inc/functions-admin.php' );
			require_once( $this->dir_path . 'inc/functions-settings.php'       );
		}
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  3.1
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Internationalize the text strings used.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );
		add_action( 'init', 'yadawiki_load_settings' );
		
		// public facing
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', 'yada_wiki_scripts' );
			add_shortcode('yadawiki', 'yada_wiki_shortcode');
			add_shortcode('yadawikitoc', 'yada_wiki_toc_shortcode');
			add_shortcode('yadawiki-index', 'yada_wiki_index_shortcode');
		}

		// admin facing
		if ( is_admin() ) {
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'yada_wiki_add_settings_link' );
			add_action( 'admin_enqueue_scripts', 'yada_wiki_admin', 10 ); 
			add_action( 'wp_ajax_yada_wiki_suggest', 'yada_wiki_suggest_callback' );
			add_action( 'admin_menu', 'yada_wiki_add_admin_menu' );
			add_action( 'admin_init', 'yada_wiki_settings_init' );
			// Handle Gutenberg
			if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
				// Gutenberg is default
				add_filter( 'use_block_editor_for_post_type', 'yada_wiki_use_gutenberg_block', 10, 2 );
			} else {
				// Is the Gutenberg plugin installed?
				if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
					// Gutenberg is installed and activated.
					add_filter( 'gutenberg_can_edit_post_type', 'yada_wiki_use_classic_editor', 10, 2 );
				}				
			}
			add_filter( 'wp_insert_post_data', 'yada_wiki_set_comment_defaults' );
		}		
		
		add_action('widgets_init', 'yadawiki_toc_widget_register_widgets');		

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  3.1
	 * @access public
	 * @return void
	 */
	public function i18n() {

		load_plugin_textdomain( 'yada-wiki', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'lang' );
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  3.1
	 * @access public
	 * @global $wpdb
	 * @return void
	 */
	public function activation() {

		register_yada_wiki();
		register_yada_wiki_cats();
		register_yada_wiki_tags();
		
		flush_rewrite_rules();
	}
}

/**
 * Gets the instance of the `Yada_Wiki_Plugin` class.  This function is useful for quickly grabbing data
 * used throughout the plugin.
 *
 * @since  3.1
 * @access public
 * @return object
 */
function yada_wiki_plugin() {
	return YadaWikiPlugin::get_instance();
}

// Rock 'n Roll
yada_wiki_plugin();
