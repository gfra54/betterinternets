<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Flickr Widget
	Description: A widget for displaying Flickr Photos.
	Version: 1.0

-----------------------------------------------------------------------------------*/
add_action('widgets_init', 'mts_flickr_load_widgets');

function mts_flickr_load_widgets()
{
	register_widget('mts_flickr_Widget');
}

class mts_flickr_Widget extends WP_Widget {
	
	var $prefix; 
	/**
	 * Set up the widget's unique name, ID, class, description, and other options
	 * @since 1.2.1
	**/			
	function __construct() {
	
		// Set default variable for the widget instances
		$this->prefix = 'mts_flickr';	
		$this->textdomain = 'mythemeshop';	
		
		// Set up the widget control options
		$control_options = array(
			'id_base' => $this->prefix
		);
		
		// Add some informations to the widget
		$widget_options = array('classname' => 'widget_flickr', 'description' => __( 'Displays a Flickr photo stream from an ID', $this->textdomain ) );
		
		// Create the widget
		$this->WP_Widget($this->prefix, __('MyThemeShop Flickr Widget', $this->textdomain), $widget_options, $control_options );
		
	}
	
			
	/**
	 * Push the widget stylesheet widget.css into widget admin page
	 * @since 1.2.1
	**/		
	function widget( $args, $instance ) {
		extract( $args );

		// Set up the arguments for wp_list_categories().
		$cur_arg = array(
			'title'			=> $instance['title'],
			'type'			=> empty( $instance['type'] ) ? 'user' : $instance['type'],
			'flickr_id'		=> $instance['flickr_id'],
			'count'			=> (int) $instance['count'],
			'display'		=> empty( $instance['display'] ) ? 'latest' : $instance['display'],
		);
		
		extract( $cur_arg );
	
		// print the before widget
		echo $before_widget;
		
		if ( $title )
			echo $before_title . $title . $after_title;

		echo "<div class='mts-flickr-badge clearfix'>";
	
		// If the widget have an ID, we can continue
		if ( ! empty( $instance['flickr_id'] ) )
			echo "<script type='text/javascript' src='http://www.flickr.com/badge_code_v2.gne?count=$count&amp;display=$display&amp;size=s&amp;layout=x&amp;source=$type&amp;$type=$flickr_id'></script>";
		else
			echo '<p>' . __('Please provide an Flickr ID', $this->textdomain) . '</p>';
		
		echo '</div>';
		
		if ( ! empty( $instance['outro_text'] ) )
			echo '<p>' . do_shortcode( $instance['outro_text'] ) . '</p>';
		
		// Print the after widget
		echo $after_widget;
	}

	

	/**
	 * Widget update functino
	 * @since 1.2.1
	**/		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['type'] 			= strip_tags($new_instance['type']);
		$instance['flickr_id'] 		= strip_tags($new_instance['flickr_id']);
		$instance['count'] 			= (int) $new_instance['count'];
		$instance['display'] 		= strip_tags($new_instance['display']);
		$instance['title']			= strip_tags($new_instance['title']);
		
		return $instance;
	}

	

	/**
	 * Widget form function
	 * @since 1.2.1
	**/		
	function form( $instance ) {
		// Set up the default form values.
		$defaults = array(
			'title'			=> esc_attr__( 'Flickr Widget', $this->textdomain ),
			'type'			=> 'user',
			'flickr_id'		=> '', // 71865026@N00
			'count'			=> 8,
			'display'		=> 'display',
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$types = array( 
			'user'  => esc_attr__( 'user', $this->textdomain ), 
			'group' => esc_attr__( 'group', $this->textdomain )
		);
		$displays = array( 
			'latest' => esc_attr__( 'latest', $this->textdomain ),
			'random' => esc_attr__( 'random', $this->textdomain )
		);
		
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', $this->textdomain); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>	
			<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( 'Type', $this->textdomain ); ?></label>
			<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<?php foreach ( $types as $k => $v ) { ?>
					<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $instance['type'], $k ); ?>><?php echo esc_html( $v ); ?></option>
				<?php } ?>
			</select>
			<br>			
			<span class="controlDesc"><?php _e( 'The type of images from user or group.', $this->textdomain ); ?></span>
		</p>
	
		<p>	
			<label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID', $this->textdomain); ?></label>							
			<input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
			<br>
			<span class="controlDesc"><?php _e( 'Put the flickr ID here, go to <a href="http://goo.gl/PM6rZ" target="_blank">Flickr NSID Lookup</a> if you don\'t know your ID. Example: 71865026@N00', $this->textdomain ); ?></span>
		</p>
	
		<p>	
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number', $this->textdomain); ?></label>
			<input class="column-last" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr( $instance['count'] ); ?>" size="3" />
			<br>
			<span class="controlDesc"><?php _e( 'Number of images shown from 1 to 10', $this->textdomain ); ?></span>
		</p>
	
		<p>	
			<label for="<?php echo $this->get_field_id('display'); ?>"><?php _e('Display Method', $this->textdomain); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
				<?php foreach ( $displays as $k => $v ) { ?>
					<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $instance['display'], $k ); ?>><?php echo esc_html( $v ); ?></option>
				<?php } ?>
			</select>	
			<br>
			<span class="controlDesc"><?php _e( 'Get the image from recent or use random function.', $this->textdomain ); ?></span>
		</p>	

		<?php
	}
}