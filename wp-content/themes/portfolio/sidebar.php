<?php
	$sidebar = mts_custom_sidebar();
    if ( $sidebar != 'mts_nosidebar' ) {
?>
<aside id="site-sidebar" class="sidebar" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
	<?php if (!dynamic_sidebar($sidebar)) : ?>
		<div id="sidebar-search" class="widget clearfix">
			<h3><?php _e('Search', 'mythemeshop'); ?></h3>
			<?php get_search_form(); ?>
		</div>
		<div id="sidebar-archives" class="widget clearfix">
			<h3><?php _e('Archives', 'mythemeshop') ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>
		<div id="sidebar-meta" class="widget clearfix">
			<h3><?php _e('Meta', 'mythemeshop') ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div>
	<?php endif; ?>
</aside>
<?php } ?>