<?php 

function yada_wiki_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'wiki_page' => '', 
		'link_text' => '', 
	), $atts ) ); 
	
	$wiki_page = sanitize_text_field($wiki_page);
	$link_text = sanitize_text_field($link_text);
	
	return get_yada_wiki_link( $wiki_page, $link_text );
}

function get_yada_wiki_link( $wiki_page, $link_text ){

	$wiki_page  = trim($wiki_page);
	$link_text = trim($link_text);

	$site = get_option('siteurl');

	$target = get_page_by_title( html_entity_decode($wiki_page), OBJECT, 'yada_wiki');

	if(!$link_text){ $link_text = $wiki_page; }

	if($target){
		$permalink = get_permalink($target->ID);
		return '<a href="'.$permalink.'">'.$link_text.'</a>';

	} else {

		if ( is_user_logged_in() ){

			$slug  = urlencode($wiki_page);
			$new_link = $site.'/wp-admin/post-new.php?post_type=yada_wiki&post_title='.$slug;

			return '<a href="'.$new_link.'" title="This wiki page does not yet exist. Create it (requires valid access/permissions)" style="color:red;">'.$link_text.'</a>';
		} 
		else{
			$just_text = '<span style="color:red;">'.$link_text.'</span>';
			return $just_text;
		}
	}
}
