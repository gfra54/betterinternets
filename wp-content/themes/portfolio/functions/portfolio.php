<?php
/*-----------------------------------------------------------------------------------*/
/*  Register Portfolio Post Type
/*-----------------------------------------------------------------------------------*/
function mts_portfolio_posttype_register(){
	$portfolio_labels = array(
		'name'               => __( 'Portfolio', 'mythemeshop' ),
		'singular_name'      => __( 'Portfolio Item', 'mythemeshop' ),
		'add_new'            => __( 'Add New Item', 'mythemeshop' ),
		'add_new_item'       => __( 'Add New Portfolio Item', 'mythemeshop' ),
		'edit_item'          => __( 'Edit Portfolio Item', 'mythemeshop' ),
		'new_item'           => __( 'Add New Portfolio Item', 'mythemeshop' ),
		'view_item'          => __( 'View Item', 'mythemeshop' ),
		'search_items'       => __( 'Search Portfolio', 'mythemeshop' ),
		'not_found'          => __( 'No portfolio items found', 'mythemeshop' ),
		'not_found_in_trash' => __( 'No portfolio items found in trash', 'mythemeshop' ),
	);
	$args = array(
		'labels'          => $portfolio_labels,
		'public'          => true,
		'supports'        => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'revisions',
		),
		'capability_type' => 'post',
		'rewrite'         => array( 'slug' => 'portfolio', ), // Permalinks format
		'menu_position'   => 5,
		'has_archive'     => true,
		'menu_icon'       => 'dashicons-screenoptions',
	);
	$args = apply_filters( 'portfolioposttype_args', $args );
	register_post_type( 'portfolio', $args );
	
	$labels = array(
		'name' => __( 'Portfolio Tags', 'mythemeshop' ),
		'singular_name' => __( 'Portfolio Tag', 'mythemeshop' ),
		'menu_name' => __( 'Portfolio Tags', 'mythemeshop' ),
		'edit_item' => __( 'Edit Portfolio Tag', 'mythemeshop' ),
		'update_item' => __( 'Update Portfolio Tag', 'mythemeshop' ),
		'add_new_item' => __( 'Add New Portfolio Tag', 'mythemeshop' ),
		'new_item_name' => __( 'New Portfolio Tag Name', 'mythemeshop' ),
		'parent_item' => __( 'Parent Portfolio Tag', 'mythemeshop' ),
		'parent_item_colon' => __( 'Parent Portfolio Tag:', 'mythemeshop' ),
		'all_items' => __( 'All Portfolio Tags', 'mythemeshop' ),
		'search_items' => __( 'Search Portfolio Tags', 'mythemeshop' ),
		'popular_items' => __( 'Popular Portfolio Tags', 'mythemeshop' ),
		'separate_items_with_commas' => __( 'Separate portfolio tags with commas', 'mythemeshop' ),
		'add_or_remove_items' => __( 'Add or remove portfolio tags', 'mythemeshop' ),
		'choose_from_most_used' => __( 'Choose from the most used portfolio tags', 'mythemeshop' ),
		'not_found' => __( 'No portfolio tags found.', 'mythemeshop' ),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'portfolio_tag' ),
		'show_admin_column' => true,
		'query_var' => true,
	);
	$args = apply_filters( 'portfolioposttype_tag_args', $args );
	register_taxonomy( 'portfolio_tags', array( 'portfolio' ), $args );
}
add_action( 'init', 'mts_portfolio_posttype_register' );

/*--------------------------------------------*
 * Ajax Portfolio Main Class
 *--------------------------------------------*/
class AjaxPortfolio {

	public $atts;
	private $entries;

	/**
	 * Constructor
	 */
	function __construct() {
		// add_action( 'init', array( &$this, 'init_ajax_portfolio' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'load_site_assets' ) );
		// Setup the event handler for generating html for the ajax
		add_action( 'wp_ajax_nopriv_get_portfolio', array( &$this, 'get_portfolio' ) );
		add_action( 'wp_ajax_get_portfolio', array( &$this, 'get_portfolio' ) );
		add_action( 'wp_ajax_nopriv_get_portfolio_items', array( &$this, 'get_portfolio_items' ) );
		add_action( 'wp_ajax_get_portfolio_items', array( &$this, 'get_portfolio_items' ) );
	}

	
	function init_ajax_portfolio (){
		
	}

	function load_site_assets() {
		wp_enqueue_script( 'waitforimages', get_template_directory_uri() .'/js/jquery.waitforimages.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'isotope', get_template_directory_uri() .'/js/isotope.min.js', array( 'jquery' ), '2.0.0', true );
    	wp_register_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js' , '1.0.0', true );
		wp_enqueue_script ( 'flexslider' );
		//wp_localize_script( 'frontend_js', 'ajp', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}


	function sort_buttons( $params ) {
		//get all categories that are actually listed on the page
		
		$tax_selected = explode(',', $this->atts['categories']);
		$categories = get_terms( $params['taxonomy'], 'orderby=name&hide_empty=0' );
		$count = count( $categories );
		$output  = "<div class='sort_width_container clearfix' ><div id='js_sort_items' >";
		$output .= "<div class='sort_by_cat clearfix'>";
		$output .= "<ul class='sort_list'>";
		$output .= "<li><a href='#' data-filter='*' class='all_sort_button active_sort'>".__( 'All', 'mythemeshop' )."</a></li>";
		if ( $count > 0 ) {
			foreach ( $categories as $category ) {
				//if(in_array($category->term_id, $tax_selected)) {
					$output .= "<li><a href='#' data-filter='.".$category->slug."_sort' class='".$category->slug."_sort_button' >".$category->name."</a></li>";
				//}
			}
		}
		$output .= "</ul>";
		$output .= "</div></div></div>";
		return $output;
	}

	//get the categories for each post and create a string that serves as classes so the javascript can sort by those classes
	function sort_cat_string( $the_id, $params ) {
		$sort_classes = "";
		$item_categories = get_the_terms( $the_id, $params['taxonomy'] );
		if ( is_object( $item_categories ) || is_array( $item_categories ) ) {
			foreach ( $item_categories as $cat ) {
				$sort_classes .= $cat->slug.'_sort ';
			}
		}
		return $sort_classes;
	}
	
	function query_entries($params = array()){
		$query = array();
		if ( empty( $params ) ) $params = $this->atts;

		if ( !empty( $params['categories'] ) ) {
			$terms  = explode( ',', $params['categories'] );
		}
		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		
		if ( !$page ) $page = 1;
		//if we find categories perform complex query, otherwise simple one
		if ( isset( $terms[0] ) && !empty( $terms[0] ) && !is_null( $terms[0] ) && $terms[0] != "null" ) {
			$query = array(
				'orderby'  => $params['orderby'],
				'order'  => $params['order'],
				'paged'  => $page,
				'posts_per_page' => $params['items'],
				'post_type' => $params['post_type'],
				'tax_query' => array(  
					array(  'taxonomy'  => $params['taxonomy'],
							'field'  => 'id',
							'terms'  => $terms,
							'operator'  => 'IN')
					)
			); // End query args array
		}
		else {
			$query = array( 'paged'=> $page, 'posts_per_page' => $params['items'], 'post_type' => $params['post_type'] );
		}
		
		$new_query = new WP_Query($query);
		
		return $new_query;
	}
	
	function create_grid_entry($the_id = false) {
		$mts_options = get_option( MTS_THEME_NAME );
		$output = '';
		$padding_adjust_style = $this->atts['padding'] ? 'style="padding: '.$this->atts['padding'].'px"' : '';
		$post_class = "portfolio-entry portfolio-overlay";
		$sort_class = $this->sort_cat_string( $the_id, $this->atts );
		if ( has_post_thumbnail( $the_id ) ) {
			$thumb = get_post_thumbnail_id( $the_id );
			if($this->atts['thumb_size'] == 'custom') {
				$img_url = wp_get_attachment_url( $thumb );
				$post_image = bfi_thumb( $img_url, array( 'width' => $mts_options['mts_portfolio_thumb_width'], 'height' => $mts_options['mts_portfolio_thumb_height'], 'crop' => true ) );
			} else {
				$img_url = wp_get_attachment_image_src( $thumb, $this->atts['thumb_size'] );
				$post_image = $img_url[0];var_dump($post_image);
			}
		}
		$link = get_permalink( $the_id );
		$title = get_the_title( $the_id );
		//$posttags = wp_get_post_terms( $the_id , $this->atts['taxonomy'] , 'fields=names' );
		
		$term = strip_tags( get_the_term_list( $the_id,  $this->atts['taxonomy'], '', ', ', '' ) );
		
		// img size
		if ( has_post_thumbnail( $the_id ) && $post_image) {
			$size = getimagesize($post_image);
		}

		$output .= "<div id='entry-{$the_id}' class='{$post_class} {$sort_class}' {$padding_adjust_style}>";
		$output .= "<div data-permalink='{$link}' data-post-id='{$the_id}' class='portfolio-image project-load'>";
		if ( has_post_thumbnail( $the_id ) && $post_image) {
			$output .= "<img class='entry-image' src='{$post_image}' alt='{$title}' width='{$size[0]}' height='{$size[1]}'>";
		} else {
			$w = $mts_options['mts_portfolio_thumb_width'];
	        $h = $mts_options['mts_portfolio_thumb_height'];

	        $ctw = $w/2-16;
	        $cth = $h/2-16;
			$output .= '<svg class="svg_placeholder entry-image" width="'.$w.'" height="'.$h.'" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin meet" viewBox="0 0 '.$w.' '.$h.'">
                    <g>
                        <rect class="svg_placeholder_bg" x="0" y="0" width="100%" height="100%" />
                    </g>
                    <g class="svg_placeholder_icon" transform="translate('.$ctw.','.$cth.')">
                        <path d="M29.996 4c0.001 0.001 0.003 0.002 0.004 0.004v23.993c-0.001 0.001-0.002 0.003-0.004 0.004h-27.993c-0.001-0.001-0.003-0.002-0.004-0.004v-23.993c0.001-0.001 0.002-0.003 0.004-0.004h27.993zM30 2h-28c-1.1 0-2 0.9-2 2v24c0 1.1 0.9 2 2 2h28c1.1 0 2-0.9 2-2v-24c0-1.1-0.9-2-2-2v0z" fill="currentColor"></path>
                        <path d="M26 9c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z" fill="currentColor"></path>
                        <path d="M28 26h-24v-4l7-12 8 10h2l7-6z" fill="currentColor"></path>
                    </g>
                </svg>';
		}
		$overlay_width = $mts_options['mts_portfolio_thumb_width'] - 30;
		$overlay_height = $mts_options['mts_portfolio_thumb_height'] - 30;
		if($this->atts['caption'] == 'yes') {
			$mts_options = get_option( MTS_THEME_NAME );
			$caption_text = get_post_meta( $the_id, 'custom_caption', true );
			
			if ( !empty( $caption_text ) )
				$output .= "<div class='img-overlay' style='width:{$overlay_width}px; height:{$overlay_height}px;'><div>{$caption_text}</div></div>";
			else {
				$output .= "<div class='img-overlay' style='width:{$overlay_width}px; height:{$overlay_height}px;'><div><h2 class='overlay-title'>{$title}</h2><h3 class='overlay-subtitle'>- {$term} -</h3></div></div>";
			}
		} else {
			$output .= "<div class='img-overlay' style='width:{$overlay_width}px; height:{$overlay_height}px;'><div class='dashicons dashicons-plus'></div></div>";
		}
		$output .= "</div>";
		$output .= "</div>";
		return $output;
	}
	
	function create_grid_html( $params = array() ) {
		global $post;

		if ( ! empty( $params ) ) $this->atts = $params;

		$entries = $this->query_entries();
		
		$output = '';
		$margin_adjust_style = $this->atts['padding'] ? 'style="margin: -'.$this->atts['padding'].'px"' : '';
		$pagination_class = 'pagination-'.$this->atts['paginate'];
		$animation = $this->atts['animation'];
		if ( $entries->have_posts() ) :
			$postCount = $entries->found_posts;
			
			$output .= "<div class='portfolio-grid {$pagination_class}'>";
			$output .= $this->atts['sort'] == "yes" ? $this->sort_buttons( $this->atts ) : "";
			$output .= "<div class='portfolio-loader'><div></div></div>";
			$output .= "<div class='ajax-container'>";
			$output .= "<div class='ajax-controls'>";
			$output .= $this->atts['prevnext'] == "yes" ? "<a href='#' class='prev-ajax-container'><i class='fa fa-angle-left'></i></a>" : "";
			$output .= "<a href='#' class='close-ajax-container'>X</a>";
			$output .= $this->atts['prevnext'] == "yes" ? "<a href='#' class='next-ajax-container'><i class='fa fa-angle-right'></i></a>" : "";
			$output .= "</div></div>";
			$output .= "<div id='portfolio-grid-frame' class='portfolio-grid-container isotope' data-effect='effect-{$animation}' data-post-count='{$postCount}'  {$margin_adjust_style} >";
			while ( $entries->have_posts() ) : $entries->the_post();
				$output.= $this->create_grid_entry($post->ID);
			endwhile;
			$output .= "</div>";
			if($this->atts['paginate'] != 'no') {
				if($pagination = $this->pagination($entries->max_num_pages)){
					$this->max_num_pages = $entries->max_num_pages;
					$output .= "<div class='portfolio-pagination'>{$pagination}</div>";	
				}
			}
			$output .= "</div>";
		endif;
		wp_reset_postdata();
		return $output;
	}

	function get_portfolio_items() {
		
	}

	function get_portfolio() {
		if ( isset( $_POST['id'] ) && !empty( $_POST['id'] ) ):
			$html = $this->project_html( $_POST['id'] );
		die( $html );
		else:
			die( 0 );
		endif;
	}

	function pagination( $pages = '', $wrapper = 'div' ) {
		global $paged;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		$output    = "";
		$prev      = $paged - 1;
		$next      = $paged + 1;
		$range     = 2; // only edit this if you want to show more page-links
		$showitems = ( $range * 2 ) + 1;
		if ( $pages == '' ) {
			global $wp_query;
			//$pages = ceil(wp_count_posts($post_type)->publish / $per_page);
			$pages = $wp_query->max_num_pages;
			if ( !$pages ) {
				$pages = 1;
			}
		}
		$method = "get_pagenum_link";
		if ( is_single() ) {
			$method = "$this->post_pagination_link";
		}
		if ( 1 != $pages ) {
			$output .= "<$wrapper class='pagination'>";
			$output .= ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) ? "<a href='" . $method( 1 ) . "'>&laquo;</a>" : "";
			$output .= ( $paged > 1 && $showitems < $pages ) ? "<a href='" . $method( $prev ) . "'>&lsaquo;</a>" : "";
			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					$output .= ( $paged == $i ) ? "<span class='current'>" . $i . "</span>" : "<a href='" . $method( $i ) . "' class='inactive' >" . $i . "</a>";
				}
			}
			$output .= ( $paged < $pages && $showitems < $pages ) ? "<a href='" . $method( $next ) . "'>&rsaquo;</a>" : "";
			$output .= ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) ? "<a href='" . $method( $pages ) . "'>&raquo;</a>" : "";
			$output .= "</$wrapper>\n";
		}
		return $output;
	}
	function post_pagination_link( $link ) {
		$url = preg_replace( '!">$!', '', _wp_link_page( $link ) );
		$url = preg_replace( '!^<a href="!', '', $url );
		return $url;
	}


	function project_html( $id = false ) {
		$query = array();
		global $wp_embed;
		if ( empty( $id ) )
			return false;
		$new_query = new WP_Query(array(
				'post_type' => 'portfolio',
				'p' => $id
			));
		/*query_posts( array(
				'post_type' => 'portfolio',
				'p' => $id
			) );*/
		$html = '';
		if ( $new_query->have_posts() ):
			while ( $new_query->have_posts() ):
				$new_query->the_post();
		$the_id                  = get_the_ID();
		$size                    = 'full';
		$current_post['title']   = get_the_title();
		$current_post['content'] = get_the_excerpt();
		// Apply the default wordpress filters to the content

		$content = get_the_content();
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		$current_post['content'] = $content;
		$rand                    = mt_rand();
		$slideshow               = '';
		
		extract($current_post);
		$full_image = wp_get_attachment_image_src( get_post_thumbnail_id( $the_id ), 'full' );
		$full_image = $full_image[0];
		$full_image_url = bfi_thumb( $full_image, array( 'width' => '710', 'height' => '450', 'crop' => true ) );
		if ( $full_image_url ) {
			$slideshow .= "<li class='project_slide'><div><img src='{$full_image_url}' alt='' /></div></li>";
		} else {
			$w = 710;
	        $h = 450;
			$slideshow .= '<li class="project_slide"><div><object type="image/svg+xml" data="'.get_template_directory_uri().'/images/placeholder.svg?width='.$w.'&height='.$h.'" width="'.$w.'" height="'.$h.'"></object></div></li>';
		}
		
		$project_position = get_post_meta( $the_id, 'desc_position', true ) ? get_post_meta( $the_id, 'desc_position', true ) : 'right';
		$termlist = get_the_term_list( $the_id, 'portfolio_tags', '', ' ', '' );
		
		if($termlist) {
			$termlist = '<div class="portfolio-tags"><span>'. __('Tags : ', 'mythemeshop') . '</span>'. $termlist . '<div>'; 
		}
		
		$html .= "<div id='ajax_project_{$the_id}' class='ajax_project clearfix project_position_{$project_position}' data-project_id='{$the_id}'>";
		$html .= "<div class='project_media'>";
		$html .= "<div class='project_flexslider'>";
		$html .= "<ul class='project_slides'>";
		$html .= $slideshow;
		$html .= "</ul>";
		$html .= "</div>";
		$html .= "</div>";
		$html .= "<div class='project_description'>";
		$html .= "<h2 class='title'>{$title}</h2>";
		$html .= $content;
		$html .= '<p></p>';
		$html .= $termlist;
		$html .= "</div>";
		$html .= "</div>";
		endwhile;
		endif;
		
		wp_reset_postdata();
		if ( $html )
			return $html;
	}

}
global $mts_portfolios;
$mts_portfolios = new AjaxPortfolio();

add_filter('pre_get_posts', 'fix_portfolio_pagination');
function fix_portfolio_pagination($query){
	if ( !$query->is_main_query() || is_admin() ) {
        return $query;
    }
	$mts_options = get_option( MTS_THEME_NAME );
	if ( $query->is_home() && 'posts' === get_option('show_on_front') ) {
        $query->set( 'posts_per_page', $mts_options['mts_portfolio_items'] );
        $query->set( 'post_type', 'portfolio' );
    }
    return $query;
}

?>