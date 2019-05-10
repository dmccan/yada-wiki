<?php 
/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

function yada_wiki_scripts() {
	wp_enqueue_style( 'yada-wiki', plugins_url('../css/yadawiki.css', __FILE__) );
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
		$cat_output = '<ul class="wiki-cat-list">';
		foreach ( $cat_list as $item ) {
			$cat_output = $cat_output . '<li class="wiki-cat-item"><a class="wiki-cat-link" href="'.get_post_permalink($item->ID).'">'.$item->post_title.'</a></li>';
		}
		$cat_output = $cat_output . '</ul>';
		return $cat_output;
	}
	else if($show_toc == true) {
		$the_toc = get_page_by_title( html_entity_decode("toc"), OBJECT, 'yada_wiki');
		if (! isset($the_toc) ) {
		    return __('A wiki article with the title of TOC was not found.', 'yada_wiki_domain');
		} 
		else {
			$toc_status = get_post_status( $the_toc );
			
			if( $toc_status == "publish" ) {
				$has_content = $the_toc->post_content;
				if ($has_content) {
					$the_content = apply_filters( 'the_content', $the_toc->post_content );
					return $the_content;				
				} else {
					return __('The TOC has no content.', 'yada_wiki_domain');
				}				
			} else {
				return __('The TOC has not been published.', 'yada_wiki_domain');
			}	
		}
	}
}


function yada_wiki_index_shortcode($atts) {
	extract( shortcode_atts( array( 
		'type' => '',
		'category' => '',
		'columns' => '',
	), $atts ) ); 
	
	$type = sanitize_text_field($type);	
	$category = sanitize_text_field($category);	
	$columns = sanitize_text_field($columns);	
	
	return get_yada_wiki_index($type, $category, $columns);
}

function get_yada_wiki_index($type, $category, $columns) {
	$theOutput = "";
	if(is_numeric($columns) == false) {
		$columns = 1;
	}
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
			if ($counter!=1){
				for ($x=1; $x<=$columns-$counter; $x++) {
					$theOutput = $theOutput .  '<div class="ywcolumn" data-label="Wiki Article"></div>';
				}
				$theOutput = $theOutput . '</div>';
			}				
			$theOutput = $theOutput . '</div>';
		}
		$query = "";
		$wikiposts = "";
	}
	else {
 		if($type=="category-name" || $type=="all-categories-name") {
 			$order = "name";			
 		}
		else if($type=="category-slug" || $type=="all-categories-slug") {
			$order = "slug";
		}
		else {
			$order = "name";
		}
		
		if ($category == ""){
			$parent = "";
			if($type=="category-name") {
				$type = "all-categories-name";
			}
			else if ($type == "category-slug") {
				$type = "all-categories-slug";				
			}
		}
		else {
			$args = array(
				'type'                     => 'yada_wiki',
				'child_of'                 => 0,
				'name'					   => $category,
				'parent'                   => '',
				'taxonomy'                 => 'wiki_cats',
				'pad_counts'               => false 
			); 
			$parentcat = get_term_by('name',$category,'wiki_cats');
			if($parentcat) {
				$parent = $parentcat->term_id;			
			} 
			else {
				$parent = $category->term_id;				
			}
		}
		
		$args = array(
			'type'                     => 'yada_wiki',
			'child_of'                 => 0,
			'parent'                   => $parent,
			'orderby'                  => $order,
			'order'                    => 'ASC',
			'hide_empty'               => 1,
			'hierarchical'             => 1,
			'taxonomy'                 => 'wiki_cats',
			'pad_counts'               => false 
		); 
		$categories = get_categories( $args );
		if(!empty($categories)){
			$counter = 1;
			$theOutput = $theOutput . '<div class="ywtable">';
			
			// output for single category's sub-categories
			if($type=="category-name" || $type=="category-slug") {
				foreach($categories as $wikicat){
					$theCatlink = get_category_link($wikicat->term_id);
					if($counter==1) {
						$theOutput = $theOutput . '<div class="ywrow">';
					}
					$theOutput = $theOutput . '<div class="ywcolumn" data-label="Wiki Category"><a href="' . $theCatlink . '" class="wikicatlink">' . $wikicat->name . '</a></div>';
					$counter = $counter + 1;
					if($counter==$columns) {
						$theOutput = $theOutput . '</div>';		
						$counter = 1;		
					}
				}
			}
			// output for all categories
			else {
				$categoryHierarchy = array();
				sort_terms_hierarchically($categories, $categoryHierarchy);
				foreach ($categoryHierarchy as $category) {
					if($counter==1) {
						$theOutput = $theOutput . '<div class="ywrow">';
					}
					if($category->parent==0) {
						$catLink = esc_url(get_term_link($category->term_id,'wiki_cats'));
						$categoryChild = $category->children;
						$categoryName = $category->name;
						$theOutput = $theOutput . '<div class="ywcolumn" data-label="Wiki Category"><a href="' . $catLink . '" class="wikicatlink">' . $categoryName . '</a>';
						foreach ($categoryChild as $child) {
							$catLink = esc_url(get_term_link($child->term_id,'wiki_cats'));
							$theOutput = $theOutput . '<br>&nbsp;&nbsp;&nbsp;<a href="' . $catLink . '" class="wikicatlink">' . $child->name . '</a>';
						}
						$theOutput = $theOutput . '</div>';
						$counter = $counter + 1;
					}
					if($counter==$columns) {
						$theOutput = $theOutput . '</div>';
						$counter = 1;		
					}
				}
			}
			if ($counter!=1){
				for ($x=1; $x<=$columns-$counter; $x++) {
					$theOutput = $theOutput . '<div class="ywcolumn" data-label="Wiki Category"></div>';
				}
				$theOutput = $theOutput . '</div>';
			}				
			$theOutput = $theOutput . '</div>';
			
		}
		$categories = "";
	}
	
	return $theOutput;
}

/**
 * From: http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list - pospi
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
 */
function sort_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
    }
}

