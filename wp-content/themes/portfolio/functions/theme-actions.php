<?php
$mts_options = get_option(MTS_THEME_NAME);
/*------------[ Meta ]-------------*/
if ( ! function_exists( 'mts_meta' ) ) {
	function mts_meta(){
	global $mts_options, $post;
?>
<?php if ($mts_options['mts_favicon'] != ''){ ?>
	<link rel="icon" href="<?php echo $mts_options['mts_favicon']; ?>" type="image/x-icon" />
<?php } ?>
<!--iOS/android/handheld specific -->
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-icon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php if($mts_options['mts_prefetching'] == '1') { ?>
<?php if (is_front_page()) { ?>
	<?php $my_query = new WP_Query('posts_per_page=1'); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<link rel="prefetch" href="<?php the_permalink(); ?>">
	<link rel="prerender" href="<?php the_permalink(); ?>">
	<?php endwhile; wp_reset_query(); ?>
<?php } elseif (is_singular()) { ?>
	<link rel="prefetch" href="<?php echo home_url(); ?>">
	<link rel="prerender" href="<?php echo home_url(); ?>">
<?php } ?>
<?php } ?>
    <meta itemprop="name" content="<?php bloginfo( 'name' ); ?>" />
    <meta itemprop="url" content="<?php echo site_url(); ?>" />
    <?php if ( is_singular() ) { ?>
    <meta itemprop="creator accountablePerson" content="<?php $user_info = get_userdata($post->post_author); echo $user_info->first_name.' '.$user_info->last_name; ?>" />
    <?php } ?>
<?php }
}

/*------------[ Head ]-------------*/
if ( ! function_exists( 'mts_head' ) ){
	function mts_head() {
	global $mts_options
?>
<?php echo $mts_options['mts_header_code']; ?>
<?php }
}
add_action('wp_head', 'mts_head');

/*------------[ Copyrights ]-------------*/
if ( ! function_exists( 'mts_copyrights_credit' ) ) {
	function mts_copyrights_credit() { 
	global $mts_options;
    if ( !empty($mts_options['mts_footer_social']) && is_array($mts_options['mts_footer_social'])) {
?>
<!--start copyrights-->
<div class="row" id="copyright-note">
	<span>&copy; <?php echo date("Y") ?> <a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>" rel="nofollow"><span><?php bloginfo('name'); ?></span></a>. <?php echo $mts_options['mts_copyrights']; ?></span>
    <span class="footer-social">
        <?php foreach( $mts_options['mts_footer_social'] as $footer_icons ) : ?>
            <?php if( ! empty( $footer_icons['mts_footer_icon'] ) && isset( $footer_icons['mts_footer_icon'] ) ) : ?>
                    <a href="<?php print $footer_icons['mts_footer_icon_link'] ?>" class="footer-<?php print $footer_icons['mts_footer_icon'] ?>"><span class="fa fa-<?php print $footer_icons['mts_footer_icon'] ?>"></span></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </span>
</div>
<!--end copyrights-->
<?php }
}
}

/*------------[ footer ]-------------*/
if ( ! function_exists( 'mts_footer' ) ) {
	function mts_footer() { 
	global $mts_options
?>
<?php if ($mts_options['mts_analytics_code'] != '') { ?>
<!--start footer code-->
<?php echo $mts_options['mts_analytics_code']; ?>
<!--end footer code-->
<?php } ?>
<?php }
}

/*------------[ breadcrumb ]-------------*/
if (!function_exists('mts_the_breadcrumb')) {
    function mts_the_breadcrumb() {
    	echo '<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="';
    	echo home_url();
    	echo '" rel="nofollow"><i class="fa fa-home"></i>&nbsp;'.__('Home','mythemeshop');
    	echo "</a></span>&nbsp;>&nbsp;";
    	if (is_category() || is_single()) {
    		$categories = get_the_category();
    		$output = '';
    		if($categories){
    			foreach($categories as $category) {
    				echo '<span typeof="v:Breadcrumb"><a href="'.get_category_link( $category->term_id ).'" rel="v:url" property="v:title">'.$category->cat_name.'</a></span>&nbsp;>&nbsp;';
    			}
    		}
    		if (is_single()) {
    			echo "<span typeof='v:Breadcrumb'><span property='v:title'>";
    			the_title();
    			echo "</span></span>";
    		}
    	} elseif (is_page()) {
    		echo "<span typeof='v:Breadcrumb'><span property='v:title'>";
    		the_title();
    		echo "</span></span>";
    	}
    }
}

/*------------[ schema.org-enabled the_category() and the_tags() ]-------------*/
function mts_the_category( $separator = ', ' ) {
    $categories = get_the_category();
    $count = count($categories);
    foreach ( $categories as $i => $category ) {
        echo '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'mythemeshop' ), $category->name ) . '" ' . ' itemprop="articleSection">' . $category->name.'</a>';
        if ( $i < $count - 1 )
            echo $separator;
    }
}
function mts_the_tags($before = null, $sep = ', ', $after = '') {
    if ( null === $before ) 
        $before = __('Tags: ', 'mythemeshop');
    
    $tags = get_the_tags();
    if (empty( $tags ) || is_wp_error( $tags ) ) {
        return;
    }
    $tag_links = array();
    foreach ($tags as $tag) {
        $link = get_tag_link($tag->term_id);
        $tag_links[] = '<a href="' . esc_url( $link ) . '" rel="tag" itemprop="keywords">' . $tag->name . '</a>';
    }
    echo $before.join($sep, $tag_links).$after;
}

/*------------[ pagination ]-------------*/
if (!function_exists('mts_pagination')) {
    function mts_pagination($pages = '', $range = 3) { 
    	$showitems = ($range * 3)+1;
    	global $paged; if(empty($paged)) $paged = 1;
    	if($pages == '') {
    		global $wp_query; $pages = $wp_query->max_num_pages; 
    		if(!$pages){ $pages = 1; } 
    	}
    	if(1 != $pages) { 
    		echo "<div class='pagination'><ul>";
    		if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
    			echo "<li><a rel='nofollow' href='".get_pagenum_link(1)."'>&laquo; ".__('First','mythemeshop')."</a></li>";
    		if($paged > 1 && $showitems < $pages) 
    			echo "<li><a rel='nofollow' href='".get_pagenum_link($paged - 1)."' class='inactive'>&lsaquo; ".__('Previous','mythemeshop')."</a></li>";
    		for ($i=1; $i <= $pages; $i++){ 
    			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) { 
    				echo ($paged == $i)? "<li class='current'><span class='currenttext'>".$i."</span></li>":"<li><a rel='nofollow' href='".get_pagenum_link($i)."' class='inactive'>".$i."</a></li>";
    			} 
    		} 
    		if ($paged < $pages && $showitems < $pages) 
    			echo "<li><a rel='nofollow' href='".get_pagenum_link($paged + 1)."' class='inactive'>".__('Next','mythemeshop')." &rsaquo;</a></li>";
    		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
    			echo "<li><a rel='nofollow' class='inactive' href='".get_pagenum_link($pages)."'>".__('Last','mythemeshop')." &raquo;</a></li>";
    			echo "</ul></div>"; 
    	}
    }
}

/*------------[ Related Posts ]-------------*/
if (!function_exists('mts_related_posts')) {
    function mts_related_posts() {
        global $post;
        $mts_options = get_option(MTS_THEME_NAME);
        if(!empty($mts_options['mts_related_posts'])) { ?>	
    		<!-- Start Related Posts -->
    		<?php 
            $empty_taxonomy = false;
            if (empty($mts_options['mts_related_posts_taxonomy']) || $mts_options['mts_related_posts_taxonomy'] == 'tags') {
                // related posts based on tags
                $tags = get_the_tags($post->ID); 
                if (empty($tags)) { 
                    $empty_taxonomy = true;
                } else {
                    $tag_ids = array(); 
                    foreach($tags as $individual_tag) {
                        $tag_ids[] = $individual_tag->term_id; 
                    }
                    $args = array( 'tag__in' => $tag_ids, 
                        'post__not_in' => array($post->ID), 
                        'posts_per_page' => $mts_options['mts_related_postsnum'], 
                        'ignore_sticky_posts' => 1, 
                        'orderby' => 'rand' 
                    );
                }
             } else {
                // related posts based on categories
                $categories = get_the_category($post->ID); 
                if (empty($categories)) { 
                    $empty_taxonomy = true;
                } else {
                    $category_ids = array(); 
                    foreach($categories as $individual_category) 
                        $category_ids[] = $individual_category->term_id; 
                    $args = array( 'category__in' => $category_ids, 
                        'post__not_in' => array($post->ID), 
                        'posts_per_page' => $mts_options['mts_related_postsnum'],  
                        'ignore_sticky_posts' => 1, 
                        'orderby' => 'rand' 
                    );
                }
             }
            if (!$empty_taxonomy) {
    		$my_query = new wp_query( $args ); if( $my_query->have_posts() ) {
    			echo '<div class="related-posts">';
                echo '<h4>'.__('Related Posts','mythemeshop').'</h4>';
                echo '<div class="clear">';
                $posts_per_row = 2;
                $j = 0;
    			while( $my_query->have_posts() ) { $my_query->the_post(); ?>
    			<article class="latestPost excerpt  <?php echo (++$j % $posts_per_row == 0) ? 'last' : ''; ?>">					
			        <?php if (has_post_thumbnail()) { 
                        $post_id = get_post_thumbnail_id();
                        $related_image = wp_get_attachment_image_src( $post_id, 'full' );
                        $related_image = $related_image[0];
                        $thumbnail = bfi_thumb( $related_image, array( 'width' => '100', 'height' => '100', 'crop' => true ) );
                    } else {
                        $thumbnail = get_template_directory_uri().'/images/nothumb-100x100.jpg';
                    }
                    echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'" rel="nofollow" id="featured-thumbnail"><img src="'.$thumbnail.'" class="wp-post-image"></a>'; ?>
                    <header>
                        <h3 class="title front-view-title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="nofollow"><?php the_title(); ?></a></h3>
                    </header>
                </article><!--.post.excerpt-->
    			<?php } echo '</div></div>'; }} wp_reset_query(); ?>
    		<!-- .related-posts -->
    	<?php }
    }
}


if ( ! function_exists('mts_the_postinfo' ) ) {
    function mts_the_postinfo( $section = 'home' ) {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( ! empty( $mts_options["mts_{$section}_headline_meta"] ) ) { ?>
			<div class="post-info">
				<?php if( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['date']) ) { ?>
						<span class="the-time"> <span><?php the_time('j'); ?></span><?php the_time('M Y'); ?></span> 
				<?php } ?>
				<?php if( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['category']) ) { ?>
						<span class="thecategory"><strong><?php echo __('Category: ','mythemeshop'); ?></strong> <?php mts_the_category(', ') ?></span>
				<?php } ?>
				<?php if ( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['author']) ) { ?>
						<span class="the-author"><strong><?php echo __('Author: ','mythemeshop'); ?></strong> <span itemprop="author"><?php the_author_posts_link(); ?></span></span>
				<?php } ?>
				<?php if( ! empty( $mts_options["mts_{$section}_headline_meta_info"]['comment']) ) { ?>
						<span class="the-comment"><strong><?php echo __('Comments: ','mythemeshop'); ?></strong> <a rel="nofollow" href="<?php comments_link(); ?>" itemprop="interactionCount"><?php comments_number(__('0','mythemeshop'), __('1','mythemeshop'),  __('%','mythemeshop') ); ?></a></span>
				<?php } ?>
			</div>
		<?php }
    }
}

if (!function_exists('mts_social_buttons')) {
    function mts_social_buttons() {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( $mts_options['mts_social_buttons'] == '1' ) { ?>
    		<!-- Start Share Buttons -->
    		<div class="shareit <?php echo $mts_options['mts_social_button_position']; ?>">
    			<?php if($mts_options['mts_twitter'] == '1') { ?>
    				<!-- Twitter -->
    				<span class="share-item twitterbtn">
    					<a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo $mts_options['mts_twitter_username']; ?>">Tweet</a>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_gplus'] == '1') { ?>
    				<!-- GPlus -->
    				<span class="share-item gplusbtn">
    					<g:plusone size="medium"></g:plusone>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_facebook'] == '1') { ?>
    				<!-- Facebook -->
    				<span class="share-item facebookbtn">
    					<div id="fb-root"></div>
    					<div class="fb-like" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_linkedin'] == '1') { ?>
    				<!--Linkedin -->
    				<span class="share-item linkedinbtn">
    					<script type="IN/Share" data-url="<?php get_permalink(); ?>"></script>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_stumble'] == '1') { ?>
    				<!-- Stumble -->
    				<span class="share-item stumblebtn">
    					<su:badge layout="1"></su:badge>
    				</span>
    			<?php } ?>
    			<?php if($mts_options['mts_pinterest'] == '1') { ?>
    				<!-- Pinterest -->
    				<span class="share-item pinbtn">
    					<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal">Pin It</a>
    				</span>
    			<?php } ?>
    		</div>
    		<!-- end Share Buttons -->
    	<?php }
    }
}

/*------------[ Class attribute for <article> element ]-------------*/
if ( ! function_exists( 'mts_article_class' ) ) {
    function mts_article_class() {
        $mts_options = get_option( MTS_THEME_NAME );
        $class = '';
        
        // sidebar or full width
        if ( mts_custom_sidebar() == 'mts_nosidebar' ) {
            $class = 'ss-full-width';
        } else {
            $class = 'article';
        }
        
        return $class;
    }
}
?>