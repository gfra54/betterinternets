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
	<div class="content <?php echo mts_article_class(); $full_post = mts_article_class(); ?>">
		<h1 class="postsby">
			<span><?php _e("Search Results for:", "mythemeshop"); ?></span> <?php the_search_query(); ?>
		</h1>
        <?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article <?php post_class('latestPost'); ?>>
				<div class="post-media">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="nofollow">
						<?php if ( has_post_thumbnail() ) { ?>
							<?php $post_id = get_the_ID(); $blog_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
							$blog_image = $blog_image[0];
							if($full_post == 'ss-full-width') {
								$blog_image_url = bfi_thumb( $blog_image, array( 'width' => '1090', 'height' => '450', 'crop' => true ) );
							} else {
								$blog_image_url = bfi_thumb( $blog_image, array( 'width' => '715', 'height' => '250', 'crop' => true ) );
							}
						echo '<img src="'.$blog_image_url.'" class="single_featured">';
						?>
						<?php } ?>
					</a>
				</div>
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
		<?php } else: ?>
			<div class="no-results">
				<h2><?php _e('We apologize for any inconvenience, please hit back on your browser or use the search form below.', 'mythemeshop'); ?></h2>
				<?php get_search_form(); ?>
			</div><!--noResults-->
		<?php endif; ?>
		<!--End Pagination-->
		
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>