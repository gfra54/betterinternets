<?php get_header(); ?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
	<div class="content ss-full-width post-single-content">
		<article id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?> itemscope itemtype="http://schema.org/BlogPosting">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<div class="single_post">
						<?php $post_id = get_the_ID(); $single_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
							$single_image = $single_image[0];
							$single_image_url = bfi_thumb( $single_image, array( 'width' => '1090', 'height' => '450', 'crop' => true ) );
						if ( $single_image_url ) echo '<img src="'.$single_image_url.'" width="1090" height="450" class="single_featured">';
						?>
						<header class="post-header">
							<h1 class="single-post-title" itemprop="headline"><?php the_title(); ?></h1>
						</header><!--.headline_area-->
						<div class="post-content" itemprop="articleBody">
							<?php the_content(); ?>
						</div>
						<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next','mythemeshop'), 'previouspagelink' => __('Previous','mythemeshop'), 'pagelink' => '%','echo' => 1 )); ?>
					</div><!--.post-content box mark-links-->
			<?php endwhile; /* end loop */ ?>
		</article>
	</div>
<?php get_footer(); ?>