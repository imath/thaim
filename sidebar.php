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
	
	<?php do_action( 'thaim_before_sidebar_widgets') ;?>
    		
	<div class="sidebar-widget">
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
	</div>
	
	<div class="sidebar-widget">
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-2')) ?>
	</div>
	
	<?php do_action( 'thaim_after_sidebar_widgets') ;?>
		
</aside>
<!-- /Sidebar -->