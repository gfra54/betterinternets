<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
	<?php if($mts_options['mts_portfoliopage_heading'] || $mts_options['mts_portfoliopage_subheading']) { ?>
	<div class="site-heading">
		<?php if($mts_options['mts_portfoliopage_heading']) { ?>
		<h1 class="page-heading">- <?php echo $mts_options['mts_portfoliopage_heading']; ?> -</h1>
		<?php } ?>
		<?php if($mts_options['mts_portfoliopage_subheading']) { ?>
		<h2 class="page-subheading"><?php echo $mts_options['mts_portfoliopage_subheading']; ?></h2>
		<?php } ?>
	    <div class="header-social">
	        <?php foreach( $mts_options['mts_footer_social'] as $footer_icons ) : ?>
	            <?php if( ! empty( $footer_icons['mts_footer_icon'] ) && isset( $footer_icons['mts_footer_icon'] ) ) : ?>
	                    <a href="<?php print $footer_icons['mts_footer_icon_link'] ?>" class="footer-<?php print $footer_icons['mts_footer_icon'] ?>"><span class="fa fa-<?php print $footer_icons['mts_footer_icon'] ?>"></span></a>
	            <?php endif; ?>
	        <?php endforeach; ?>
	    </div>
	</div>
	<?php } ?>


		
	<div class="content ss-full-width">
		<article>
			<?php 
				$categories = get_terms( 'portfolio_tags' );
				$id_array = array();
				foreach($categories as $category) {
					$id_array[] = $category->term_id;
				}
				$ids = implode(",", $id_array);
				$sorting = (!empty($mts_options['mts_portfolio_sorting']) ? 'yes' : 'no');
				$items_num = $mts_options['mts_portfolio_items'];
				//$columns = $mts_options['mts_portfolio_columns'];
				$params = array(
					'categories'=> '0',
					//'columns'  => $columns,
					'thumb_size' => 'custom',
					'thumb_width' => $mts_options['mts_portfolio_thumb_width'],
					'thumb_height' => $mts_options['mts_portfolio_thumb_height'],
					'padding' => $mts_options['mts_portfolio_thumb_spacing'],
					'items'  => $items_num,
					'sort'   => $sorting,
					'orderby' => 'date',
					'order'  => 'DESC',
					'caption' => 'yes',
					'prevnext' => 'yes',
					'paginate' => 'yes',
					'post_type' => 'portfolio',
					'taxonomy'  => 'portfolio_tags',
					'animation' => '3'
				);
				echo $mts_portfolios->create_grid_html($params);

			?>
		</article>
	</div>
<?php get_footer(); ?>