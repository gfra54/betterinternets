<?php get_header(); ?>
	<div class="content <?php echo mts_article_class(); ?>">
		<article>
			<header>
				<div class="title">
					<h1><?php _e('Error 404 Not Found', 'mythemeshop'); ?></h1>
				</div>
			</header>
			<div class="post-content">
				<p><?php _e('Oops! We couldn\'t find this Page.', 'mythemeshop'); ?></p>
				<p><?php _e('Please check your URL or use the search form below.', 'mythemeshop'); ?></p>
				<?php get_search_form();?>
			</div><!--.post-content--><!--#error404 .post-->
		</article>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>