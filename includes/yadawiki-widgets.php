<?php 
/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

/***************************************
* Create the TOC widget 
***************************************/
class yadawiki_toc_widget extends WP_Widget {

	function __construct() {
		parent::__construct(false, $name = __('Yada Wiki TOC', 'yada_wiki_domain') );
	}

	function form( $instance ) {
		if( $instance) {
			$title 		= esc_attr($instance['title']);
			$category 	= $instance['category'];
			$order 		= $instance['order'];
		} else {
			$title 		= '';
			$category 	= '';
			$order 		= '';
		}

		$args = array(
			'type'                     => 'yada_wiki',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 1,
			'hierarchical'             => 1,
			'taxonomy'                 => 'wiki_cats',
			'pad_counts'               => false 
		); 
		$categories = get_categories( $args );
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'yada_wiki_domain'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category (optional):', 'yada_wiki_domain'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
			<?php 
				if( $category != '' ) {
					echo '<option value="'.$category.'">'.$category.'</option>';
				}
				else {
					echo '<option value="">'._e('Select Category', 'yada_wiki_domain').'</option>';
				}
				foreach ( $categories as $category ) {
					$option = '<option value="'.$category->cat_name.'">'.$category->cat_name.' ('.$category->category_count.')</option>';
					echo $option;
				}
			 ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order (used only if category selected):', 'yada_wiki_domain'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<?php 
				if( $order != '' ) {
					echo '<option value="'.$order.'">'.$order.'</option>';
				}
				else {
					echo '<option value="">'._e('Select Order', 'yada_wiki_domain').'</option>';
				}
				?>
				<option value="<?php _e('title', 'yada_wiki_domain'); ?>"><?php _e('Title A-Z', 'yada_wiki_domain'); ?></option>
				<option value="<?php _e('date', 'yada_wiki_domain'); ?>"><?php _e('Creation Date', 'yada_wiki_domain'); ?></option>
			</select>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['category'] 	= strip_tags($new_instance['category']);
		$instance['order'] 		= strip_tags($new_instance['order']);
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']);
		$category 	= $instance['category'];
		$order 		= $instance['order'];
		$yw_widget_content = "";

		if ( $category != "" ) {
			if ( $order == "" ) {
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
		}
		else {
			$the_toc = get_page_by_title( html_entity_decode("toc"), OBJECT, 'yada_wiki');
			if ( $the_toc != "") {
				$toc_status = get_post_status( $the_toc );
				if( $toc_status == "publish" ) {
					$yw_widget_content = apply_filters( 'the_content', $the_toc->post_content );
				}
			}
		}
		echo $before_widget;
		echo '<div class="widget-text yadawiki_toc_widget_box">';
		echo '<div class="widget-title">';
		if ( $title ) {
			echo $before_title.$title.$after_title ;
		}
		echo '</div>';
		echo '<div class="widget_links">';
		if( $category ) {
			$yw_widget_content = '<ul class="widget_links ul">';
			foreach ( $cat_list as $item ) {
				$yw_widget_content = $yw_widget_content.'<li class="widget_links li"><a href="'.get_page_link($item->ID).'">'.$item->post_title.'</a></li>';
			}
			$yw_widget_content = $yw_widget_content.'</ul>';
			echo $yw_widget_content;
		}
		else {
			echo $yw_widget_content;
		}
		echo '</div>';
		echo '</div>';
		echo $after_widget;
	}
}

/************************************************
* Registers the wiki toc widget
************************************************/
function yadawiki_toc_widget_register_widgets() {
	register_widget( 'yadawiki_toc_widget' );
}


