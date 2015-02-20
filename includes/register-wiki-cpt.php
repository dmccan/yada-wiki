<?php

if ( ! function_exists('yada_wiki') ) {

/***************************************
* Registers the wiki custom post type
***************************************/
function register_yada_wiki() {

	$labels = array(
		'name'                => _x( 'Wiki Pages', 'Post Type General Name', 'yada_wiki_domain' ),
		'singular_name'       => _x( 'Wiki', 'Post Type Singular Name', 'yada_wiki_domain' ),
		'menu_name'           => __( 'Wiki Pages', 'yada_wiki_domain' ),
		'parent_item_colon'   => __( 'Parent:', 'yada_wiki_domain' ),
		'all_items'           => __( 'All wiki pages', 'yada_wiki_domain' ),
		'view_item'           => __( 'View', 'yada_wiki_domain' ),
		'add_new_item'        => __( 'Add New Wiki Page', 'yada_wiki_domain' ),
		'add_new'             => __( 'Add New', 'yada_wiki_domain' ),
		'edit_item'           => __( 'Edit', 'yada_wiki_domain' ),
		'update_item'         => __( 'Update', 'yada_wiki_domain' ),
		'search_items'        => __( 'Search wiki', 'yada_wiki_domain' ),
		'not_found'           => __( 'Not found', 'yada_wiki_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'yada_wiki_domain' ),
	);
	$rewrite = array(
		'slug'                => 'wiki',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'yada_wiki', 'yada_wiki_domain' ),
		'description'         => __( 'A wiki custom post type', 'yada_wiki_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'page-attributes', 'publicize', 'wpcom-markdown' ),
		'taxonomies'          => array( 'category', 'wiki' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-lightbulb',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'yada_wiki', $args );

	}

}

