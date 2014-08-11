<?php 
/**
 * The sidebar for displaying sidebar widgets.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */
?>

<!-- Sidebar -->
<aside id="sidebar" class="fourcol last" role="sidebar">
	
	<?php do_action( 'thaim_before_ideastream_sidebar_widgets') ;?>
    		
	<div class="sidebar-widget">
		<?php if( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'widget-area-ideastream' ) ) ?>
	</div>
	
	<?php do_action( 'thaim_after_ideastream_sidebar_widgets') ;?>
		
</aside>
<!-- /Sidebar -->