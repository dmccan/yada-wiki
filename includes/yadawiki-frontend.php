<?php 
/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

function yada_wiki_scripts() {
	wp_enqueue_style( 'yada-wiki', plugins_url('yadawiki.css', __FILE__) );
}

function yada_wiki_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'link' => '', 
		'show' => '', 
	), $atts ) ); 
	
	$link = sanitize_text_field($link);
	$show = sanitize_text_field($show);
	
	return get_yada_wiki_link( $link, $show );
}

function get_yada_wiki_link( $wiki_page, $link_text ){
	$wiki_page  = trim($wiki_page);
	$link_text 	= trim($link_text);
	$site 		= get_option('siteurl');
	$target 	= get_page_by_title( html_entity_decode($wiki_page), OBJECT, 'yada_wiki');

	if(!$link_text){ $link_text = $wiki_page; }

	if($target && current_user_can('edit_posts')){
        if ($target->post_status == 'trash') {
            $just_text = '<span class="wikilink-trash">'.$link_text.'</span>';
            return $just_text;
        }
        elseif ($target->post_status == 'draft' || $target->post_status == 'auto-draft' || $target->post_status == 'pending' || $target->post_status == 'future') {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-pending">'.$link_text.'</a>';
        }
        elseif ($target->post_status == 'private') {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-private">'.$link_text.'</a>';
        }
        elseif ($target->post_status == 'publish') {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-published">'.$link_text.'</a>';
        }
        else {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-other">'.$link_text.'</a>';
        }
	} elseif ($target) {
        if ($target->post_status == 'trash' || $target->post_status == 'draft' || $target->post_status == 'auto-draft' || $target->post_status == 'pending' || $target->post_status == 'future') {
			$just_text = '<span class="wikilink-no-edit">'.$link_text.'</span>';
			return $just_text;
        }
        elseif ($target->post_status == 'private') {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-private">'.$link_text.'</a>';
        }
        elseif ($target->post_status == 'publish') {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-published">'.$link_text.'</a>';
        }		
        else {
            $permalink = get_permalink($target->ID);
            return '<a href="'.$permalink.'" class="wikilink-other">'.$link_text.'</a>';
        }
	} else {
		if ( current_user_can('edit_posts') ){
			$slug  = urlencode($wiki_page);
			$new_link = admin_url( 'post-new.php?post_type=yada_wiki&post_title='.$slug );
			return '<a href="'.$new_link.'" title="This wiki page does not yet exist. Create it (requires valid access/permissions)" class="wikilink-new">'.$link_text.'</a>';
		} 
		else{
			$just_text = '<span class="wikilink-no-edit">'.$link_text.'</span>';
			return $just_text;
		}
	}
}

function yada_wiki_toc_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'show_toc' => '', 
		'category' => '', 
		'order' => '', 
	), $atts ) ); 
	
	$show_toc 	= sanitize_text_field($show_toc);
	$category 	= sanitize_text_field($category);
	$order 		= sanitize_text_field($order);
	
	return get_yada_wiki_toc( $show_toc, $category, $order );
}

function get_yada_wiki_toc( $show_toc, $category, $order ){
	$show_toc  	= trim($show_toc);
	$category  	= trim($category);
	$order  	= trim($order);
	
	if($category != "") {
		if($order == "") {
			$order = "title";
		}
		$args = array( 
			'posts_per_page' 	=> -1, 
			'offset'			=> 0,
			'post_type' 		=> 'yada_wiki',
			'tax_query'			=> array(
									array(
										'taxonomy' => 'wiki_cats', 
										'field' => 'name', 
										'terms' => $category, 
									),
								   ),
			'orderby' 			=> $order,
			'order' 			=> 'ASC',
			'post_status' 		=> 'publish'
		); 	
		$cat_list = get_posts( $args );
		$cat_output = '<ul>';
		foreach ( $cat_list as $item ) {
			$cat_output = $cat_output . '<li><a href="'.get_page_link($item->ID).'">'.$item->post_title.'</a></li>';
		}
		$cat_output = $cat_output . '</ul>';
		return $cat_output;
	}
	else if($show_toc == true) {
		$the_toc = get_page_by_title( html_entity_decode("toc"), OBJECT, 'yada_wiki');
		$toc_status = get_post_status( $the_toc );
		
		if( $toc_status == "publish" ) {
			$the_content = apply_filters( 'the_content', $the_toc->post_content );
			return $the_content;
		}
	}
}

function yada_wiki_index_shortcode($atts) {
	extract( shortcode_atts( array( 
		'type' => '',
		'columns' => '',
	), $atts ) ); 
	
	$type = sanitize_text_field($type);	
	$columns = sanitize_text_field($columns);	
	
	return get_yada_wiki_index($type, $columns);
}

function get_yada_wiki_index($type, $columns){
	$theOutput = "";
	$columns = $columns + 1;

	if($type=="pages") {	
		global $wpdb;
		$query = "
			SELECT 
				$wpdb->posts.post_title, $wpdb->posts.id
			FROM 
				$wpdb->posts
			WHERE 
				$wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.post_type = 'yada_wiki'
				AND $wpdb->posts.post_title <> 'TOC'
			ORDER BY 
				$wpdb->posts.post_title ASC
		";
		$wikiposts = $wpdb->get_results($query, OBJECT);
		if(!empty($wikiposts)){
			$counter = 1;
			$theOutput = $theOutput . '<div class="ywtable">';
			foreach($wikiposts as $wiki_post){
				$thePermalink = get_post_permalink($wiki_post->id);
				if($counter==1) {
					$theOutput = $theOutput . '<div class="ywrow">';
				}
				$theOutput = $theOutput . '<div class="ywcolumn" data-label="Wiki Article"><a href="' . $thePermalink . '" class="wikicatlink">' . $wiki_post->post_title . '</a></div>';
				$counter = $counter + 1;
				if($counter==$columns) {
					$theOutput = $theOutput . '</div>';		
					$counter = 1;		
				}
			}
			$theOutput = $theOutput . '</div>';
		}
		$query = "";
		$wikiposts = "";
	}
	
	return $theOutput;
}
