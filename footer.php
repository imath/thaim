<?php
/**
 * The template for displaying the footer.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */
?>	
			</div>
			<!-- /row-->
		</div>
		<!-- /Wrapper -->
		
	</div>
	<!-- /thaim-content -->
	
	<!-- Footer -->
	<footer id="thaim-footer">
		<div class="wrapper">
			<div class="row">
				
				<div class="fourcol">
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-area-1')) ?>
				</div>
				
				<div class="fourcol">
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-area-2')) ?>
				</div>
				
				<div class="fourcol last">
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-area-3')) ?>
				</div>
				
				<br class="clear"/>
				
				<div class="twelvecol">
					<!-- Copyright -->
					<p class="copyright">
						&copy; <?php echo date("Y"); ?> Copyright <?php bloginfo('name'); ?>. <?php _e('Proudly Powered by', 'thaim'); ?> 
						<a href="//wordpress.org" title="WordPress">WordPress</a> &amp; <a href="http://imathi.eu/tag/thaim/" title="thaim">thaim</a>.
					</p>
					<!-- /Copyright -->
				</div>
				
			</div>
		</div>
		<!-- /Wrapper -->
	</footer>
	<!-- /Footer -->
	
	<?php wp_footer(); ?>

</body>
</html>