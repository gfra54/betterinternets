<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php 
    // default = 3
    $top_footer_num = (!empty($mts_options['mts_top_footer_num']) && $mts_options['mts_top_footer_num'] == 4) ? 4 : 3;
?>
	</div><!--#page-->
	<footer id="site-footer" class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
            <?php if (is_front_page() && $mts_options['mts_top_footer'] == '1' && $mts_options['mts_home_footer'] == '1') : ?>
    			<div class="footer-widgets top-footer-widgets widgets-num-<?php echo $top_footer_num; ?>">
    				<div class="f-widget f-widget-1">
    					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top') ) : ?><?php endif; ?>
    				</div>
    				<div class="f-widget f-widget-2">
    					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-2') ) : ?><?php endif; ?>
    				</div>
    				<div class="f-widget f-widget-3 <?php echo ($top_footer_num == 3) ? 'last' : ''; ?>">
    					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-3') ) : ?><?php endif; ?>
    				</div>
                    <?php if ($top_footer_num == 4) : ?>
                    <div class="f-widget f-widget-4 last">
    					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-4') ) : ?><?php endif; ?>
    				</div>
                    <?php endif; ?>
    			</div><!--.top-footer-widgets-->
            <?php endif; ?>
            <?php if (!is_front_page() && $mts_options['mts_top_footer'] == '1') : ?>
                <div class="footer-widgets top-footer-widgets widgets-num-<?php echo $top_footer_num; ?>">
                    <div class="f-widget f-widget-1">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top') ) : ?><?php endif; ?>
                    </div>
                    <div class="f-widget f-widget-2">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-2') ) : ?><?php endif; ?>
                    </div>
                    <div class="f-widget f-widget-3 <?php echo ($top_footer_num == 3) ? 'last' : ''; ?>">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-3') ) : ?><?php endif; ?>
                    </div>
                    <?php if ($top_footer_num == 4) : ?>
                    <div class="f-widget f-widget-4 last">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-4') ) : ?><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div><!--.top-footer-widgets-->
            <?php endif; ?>
            <div class="copyrights">
				<?php mts_copyrights_credit(); ?>
			</div> 
		</div><!--.container-->
	</footer><!--footer-->
</div><!--.main-container-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>