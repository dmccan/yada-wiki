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
		if( $instance) {
			extract( $args );
			$title 		= apply_filters('widget_title', $instance['title']);
			$category 	= $instance['category'];
			$order 		= $instance['order'];
		} else {
			$title 		= '';
			$category 	= '';
			$order 		= '';
		}
		
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
			if ( isset($the_toc) ) {
				$toc_status = get_post_status( $the_toc );
				if( $toc_status == "publish" ) {
					$has_content = $the_toc->post_content;
					if ($has_content) {
						$yw_widget_content = apply_filters( 'the_content', $the_toc->post_content );
					} else {
						$yw_widget_content = __('The TOC has no content.', 'yada_wiki_domain');
					}					
				} else {
					$yw_widget_content = __('The TOC has not been published.', 'yada_wiki_domain');
				}
			} else {
				$yw_widget_content = __('A wiki article with the title of TOC was not found.', 'yada_wiki_domain');
			}
		}
		if( isset($before_widget) ) {
			echo $before_widget;
		}
		if( !isset($before_title) ) { $before_title = ''; }		
		if( !isset($after_title) ) { $after_title = ''; }
		echo '<div class="widget-text yadawiki_toc_widget_box">';
		if ( $title ) {
			echo '<div class="widget-title">';
			echo $before_title.$title.$after_title;
			echo '</div>';
		}
		echo '<div class="widget_links">';
		if( $category ) {
			$yw_widget_content = '<ul class="widget_links ul">';
			foreach ( $cat_list as $item ) {
				$yw_widget_content = $yw_widget_content.'<li class="widget_links li"><a href="'.get_post_permalink($item->ID).'">'.$item->post_title.'</a></li>';
			}
			$yw_widget_content = $yw_widget_content.'</ul>';
			echo $yw_widget_content;
		}
		else {
			echo $yw_widget_content;
		}
		echo '</div>';
		echo '</div>';
		if( isset($after_widget) ) {
			echo $after_widget;
		}
	}
}

/***************************************
* Create recent wiki activity widget 
* Contributed by Nathan Gagnon
* Included with permission
* Small modiciations David McCan
***************************************/
class yadawiki_activity_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
		  'yadawiki_activity_widget', 
		  'Yada Wiki Activity'
		);
	}

	function form( $instance ) {
		if( $instance) {
			$title 		= esc_attr($instance['title']);
			$num_posts 	= $instance['num_posts'];
			$show_date 	= isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		} else {
			$title 		= '';
			$num_posts 	= '';
			$show_date 	= false;
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'yada_wiki_domain'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Posts:', 'yada_wiki_domain'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $num_posts; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'yada_wiki_domain' ); ?></label>
			<input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" />			
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['num_posts'] 	= strip_tags($new_instance['num_posts']);
		$instance['show_date'] 	= isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}
  
	function widget( $args, $instance ) {
	    $num_posts = 8;
	    $title = 'Latest Changes';
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

	    if( $instance ){
			extract( $args );
			$title = trim($instance['title'])? $instance['title']: $title;
			$num_posts = is_numeric( $instance['num_posts'] ) && $instance['num_posts'] > 0 ? $instance['num_posts']: $num_posts;
			$show_date 	= isset( $instance['show_date'] ) ? $instance['show_date'] : false;      
	    }

	    $post_args = array( 
			'offset' => 0,
			'post_type' => 'yada_wiki', 
			'orderby' => 'post_modified',
			'order' => 'DESC',
			'posts_per_page' => $num_posts,
			'post_status' => 'publish'
	    );   
	    $list = get_posts( $post_args );
	    if( isset($args['before_widget']) ) {
			echo $args['before_widget'];
	    }
		if( !isset($before_title) ) { $before_title = ''; }		
		if( !isset($after_title) ) { $after_title = ''; }
		echo '<div class="widget-text yadawiki_recent_widget_box">';
		if ( $title ) {
			echo '<div class="widget-title">';
			echo $before_title.$title.$after_title;
			echo '</div>';
		}
	    echo '<div class="widget_links">';
	    $yw_widget_content = '<ul class="widget_links ul">';
	    foreach ( $list as $item ) {
			$date_a = new DateTime($item->post_modified_gmt);
			$date_b = new DateTime;
			$interval = date_diff($date_a,$date_b);
			$mins = $interval->format('%i');
			$hours = $interval->format('%h');
			$days = $interval->format('%d');
			$months = $interval->format('%m');
			$years = $interval->format('%y');
			$modified = "";
			$quantity = "";
			$interval = "";
			if( $years ){
				$quantity = $years;
				$interval = 'year';
			}
			else if( $months ){
				$quantity = $months;
				$interval = 'month';
			}
			else if( $days ){
				$quantity = $days;
				$interval = 'day';
			}
			else if( $hours ){
				$quantity = $hours;
				$interval = 'hour';
			}
			else if( $mins ){
				$quantity = $mins;
				$interval = 'minute';
			}
			else{
				$modified = "just now";
			}

			if( $interval ){
			if( $quantity>1 )
				$interval .= 's';
				$modified = "$quantity $interval ago";
			}

	      	$yw_widget_content = $yw_widget_content.'<li class="widget_links li"><a href="'.get_post_permalink($item->ID).'">'.$item->post_title.'</a>';
			if ( $show_date ) {
				$yw_widget_content = $yw_widget_content.' ('.$modified.')';
			}      
			$yw_widget_content = $yw_widget_content.'</li>';
	    }
	    $yw_widget_content = $yw_widget_content.'</ul>';
	    echo $yw_widget_content;
	    echo '</div>';
	    echo '</div>';
	    if( isset($args['after_widget']) ) {
	      echo $args['after_widget'];
	    }
	}

}


/************************************************
* Register wiki widgets
************************************************/
function yadawiki_toc_widget_register_widgets() {
	register_widget( 'yadawiki_toc_widget' );
}

function yadawiki_activity_widget_register_widgets() {
  register_widget( 'yadawiki_activity_widget' );
}
