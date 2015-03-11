<?php 

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
			$new_link = admin_url( 'post-new.php?post_type=yada_wiki&post_title='.$slug );
			return '<a href="'.$new_link.'" title="This wiki page does not yet exist. Create it (requires valid access/permissions)" style="color:red;">'.$link_text.'</a>';
		} 
		else{
			$just_text = '<span style="color:red;">'.$link_text.'</span>';
			return $just_text;
		}
	}
}

function yada_wiki_toc_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'show_toc' => '', 
	), $atts ) ); 
	
	$show_toc = sanitize_text_field($show_toc);
	
	return get_yada_wiki_toc( $show_toc );
}

function get_yada_wiki_toc( $show_toc ){

	$show_toc  = trim($show_toc);

	if($show_toc == true) {
		$the_toc = get_page_by_title( html_entity_decode("toc"), OBJECT, 'yada_wiki');
		$toc_status = get_post_status( $the_toc );
		
		if( $toc_status == "publish" ) {
			$the_content = apply_filters( 'the_content', $the_toc->post_content );
			return $the_content;
		}
	}
}
