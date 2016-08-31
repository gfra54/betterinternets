<?php get_header(); ?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php if($mts_options['mts_homepage_heading'] || $mts_options['mts_homepage_subheading']) { ?>
	<div class="site-heading">
		<?php if($mts_options['mts_homepage_heading']) { ?>
		<h2 class="page-heading">- <?php echo $mts_options['mts_homepage_heading']; ?> -</h2>
		<?php } ?>
		<?php if($mts_options['mts_homepage_subheading']) { ?>
		<h3 class="page-subheading"><?php echo $mts_options['mts_homepage_subheading']; ?></h3>
		<?php } ?>
	</div>
<?php } ?>
<div class="content post-single-content <?php echo mts_article_class(); if ( !has_post_thumbnail() || $mts_options['mts_single_featured_img'] != '1' || empty($mts_options["mts_single_headline_meta_info"]['date']) || empty( $mts_options["mts_single_headline_meta"] ) ) { echo " has-no-thumb"; } ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?> itemscope itemtype="http://schema.org/BlogPosting">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div class="single_post">
				<?php if ($mts_options['mts_breadcrumb'] == '1') { ?>
					<div class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#"><?php mts_the_breadcrumb(); ?></div>
				<?php } ?>
				<?php if ( has_post_thumbnail() && $mts_options['mts_single_featured_img'] == '1' ) {
					$post_id = get_the_ID(); $blog_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
					$blog_image = $blog_image[0];
					if(mts_article_class() == 'ss-full-width') {
						$blog_image_url = bfi_thumb( $blog_image, array( 'width' => '1090', 'height' => '450', 'crop' => true ) );
					} else {
						$blog_image_url = bfi_thumb( $blog_image, array( 'width' => '715', 'height' => '250', 'crop' => true ) );
					}
				echo '<img src="'.$blog_image_url.'" class="single_featured">';
				}
				if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
				<header class="post-header">
					<?php mts_the_postinfo( 'single' ); ?>
					<h1 class="single-post-title" itemprop="headline"><?php the_title(); ?></h1>
				</header><!--.headline_area-->
				<div class="post-content" itemprop="articleBody">
					<?php if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'top') mts_social_buttons(); ?>
					<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
						<?php $toptime = $mts_options['mts_posttop_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$toptime day")), get_the_time("Y-m-d") ) >= 0) { ?>
							<div class="topad">
								<?php echo do_shortcode($mts_options['mts_posttop_adcode']); ?>
							</div>
						<?php } ?>
					<?php } ?>
						<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next','mythemeshop'), 'previouspagelink' => __('Previous','mythemeshop'), 'pagelink' => '%','echo' => 1 )); ?>
					<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
						<?php $endtime = $mts_options['mts_postend_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$endtime day")), get_the_time("Y-m-d") ) >= 0) { ?>
							<div class="bottomad">
								<?php echo do_shortcode($mts_options['mts_postend_adcode']); ?>
							</div>
						<?php } ?>
					<?php } ?> 
					<?php if($mts_options['mts_tags'] == '1') { ?>
						<div class="tags"><?php mts_the_tags('<span class="tagtext">'.__('Tags','mythemeshop').':</span>',', ') ?></div>
					<?php } ?>
				</div>
				<?php if (empty($mts_options['mts_social_button_position']) || $mts_options['mts_social_button_position'] == 'bottom' || $mts_options['mts_social_button_position'] == 'floating') mts_social_buttons(); ?>
			</div><!--.post-content box mark-links-->
			
			<?php mts_related_posts(); ?> 
							
			<?php if($mts_options['mts_author_box'] == '1') { ?>
				<div class="postauthor">
					<h4><?php _e('About The Author', 'mythemeshop'); ?></h4>
					<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '100' );  } ?>
					<h5 class="vcard"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="nofollow" class="fn"><?php the_author_meta( 'nickname' ); ?></a></h5>
					<p><?php the_author_meta('description') ?></p>
				</div>
			<?php }?>  

			<?php comments_template( '', true ); ?>
		<?php endwhile; /* end loop */ ?>
	</article>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>