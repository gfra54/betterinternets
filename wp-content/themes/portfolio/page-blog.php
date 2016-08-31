<?php
/*
 * Template Name: Blog 
 */
?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
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
	<?php if (!is_single()) { ?>
		<?php if($mts_options['mts_featured_slider'] == '1') { ?>
			<div class="slider-container">
				<div class="flex-container loading">
					<div id="slider" class="flexslider">
						<ul class="slides">
							<?php if (empty($mts_options['mts_custom_slider'])) { ?>
								<?php 
									// prevent implode error
									if (empty($mts_options['mts_featured_slider_cat']) || !is_array($mts_options['mts_featured_slider_cat'])) {
										$mts_options['mts_featured_slider_cat'] = array('0');
									}
									
									$slider_cat = implode(",", $mts_options['mts_featured_slider_cat']); $my_query = new WP_Query('cat='.$slider_cat.'&posts_per_page='.$mts_options['mts_featured_slider_num']);
									while ($my_query->have_posts()) : $my_query->the_post();
								?>
								<li> 
									<a href="<?php the_permalink() ?>">
										<?php $post_id = get_the_ID(); $slider_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
											$slider_image = $slider_image[0];
											$slider_image_url = bfi_thumb( $slider_image, array( 'width' => '1090', 'height' => '450', 'crop' => true ) );
										echo '<img src="'.$slider_image_url.'" width="1090" height="450" class="single_featured">';
										?>
										<div class="flex-caption">
											<h2 class="slidertitle"><?php the_title(); ?></h2>
										</div>
									</a> 
								</li>
								<?php endwhile; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php foreach($mts_options['mts_custom_slider'] as $slide) : ?>
									<li>
										<a href="<?php echo $slide['mts_custom_slider_link']; ?>">
											<?php $slider_image = wp_get_attachment_image_src( $slide['mts_custom_slider_image'], 'full' );
												$slider_image = $slider_image[0];
												$slider_image_url = bfi_thumb( $slider_image, array( 'width' => '1090', 'height' => '450', 'crop' => true ) );
											echo '<img src="'.$slider_image_url.'" width="1090" height="450" class="single_featured">';
											?>
											<div class="flex-caption">
												<h2 class="slidertitle"><?php echo $slide['mts_custom_slider_title']; ?></h2>
											</div>
										</a> 
									</li>
								<?php endforeach; ?>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<!-- slider-container -->
		<?php } ?>
	<?php } ?>
	<div class="content <?php echo mts_article_class(); $full_post = mts_article_class(); ?>">
		<?php if (get_query_var('page') > 1) {
		    $paged = get_query_var('page');
		} elseif (get_query_var('paged')) {
		    $paged = get_query_var('paged');
		} else {
		    $paged = 1;
		}
		$args= array('paged' => $paged, 'post_type' => 'post');
		query_posts($args); ?>
        <?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article <?php post_class('latestPost'); ?>>
				<?php if ( has_post_thumbnail() ) { ?>
					<div class="post-media">
						<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="nofollow">
							<?php $post_id = get_the_ID(); $blog_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
							$blog_image = $blog_image[0];
							if($full_post == 'ss-full-width') {
								$blog_image_url = bfi_thumb( $blog_image, array( 'width' => '1090', 'height' => '450', 'crop' => true ) );
							} else {
								$blog_image_url = bfi_thumb( $blog_image, array( 'width' => '715', 'height' => '250', 'crop' => true ) );
							}
							echo '<img src="'.$blog_image_url.'" class="single_featured">';
							if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
						</a>
					</div>
				<?php } ?>
				<div class="post-main <?php if ( !has_post_thumbnail() || empty( $mts_options["mts_home_headline_meta"] ) || empty( $mts_options["mts_home_headline_meta_info"]['date']) ) { echo "has-no-thumb"; } ?>">
					<header class="post-header">
						<?php mts_the_postinfo(); ?>
						<h2 class="single-post-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					</header>
					<?php if ($mts_options['mts_full_posts'] == '0') : ?>
    					<div class="post-content excerpt">
                            <?php echo mts_excerpt(50); ?>
    					</div>
					    <?php mts_readmore(); ?>
				    <?php else : ?>
                        <div class="post-content post-single-content">
                            <?php the_content(); ?>
                        </div>
                        <?php if (mts_post_has_moretag()) : ?>
                            <?php mts_readmore(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
				</div>
			</article><!--.post excerpt-->
		<?php endwhile; ?>
		
		<!--Start Pagination-->
		<?php if (isset($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] == '1' ) { ?>
			<?php mts_pagination(); ?> 
		<?php } else { ?>
			<div class="pagination">
				<ul>
					<li class="nav-previous"><?php next_posts_link( __( '&larr; '.'Older posts', 'mythemeshop' ) ); ?></li>
					<li class="nav-next"><?php previous_posts_link( __( 'Newer posts'.' &rarr;', 'mythemeshop' ) ); ?></li>
				</ul>
			</div>
		<?php } wp_reset_query(); endif; ?>
		<!--End Pagination-->
		
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>