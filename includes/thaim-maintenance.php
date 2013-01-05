<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
* thaim maintenance
* let's work with no pressure to finish the job..
*/
class Thaim_Maintenance
{
	
	function __construct()
	{
		
		if( !is_admin() && !current_user_can('manage_options') ) {
			
			add_action( 'template_redirect', array( &$this, 'display_maintenance_page'), 12 );
			add_action( 'wp', array( &$this, 'register_styles'), 10 );
			add_filter( 'wp_title', array( &$this, 'title_maintenance'), 10, 3 );
			add_filter( 'body_class', array( &$this, 'body_maintenance'), 10, 2 );
			
		}
		
	}
	
	function display_maintenance_page()
	{
			
		$maintenance_templace = get_template_directory() . '/thaim-maintenance-page.php';
			
		status_header( 200 );

		load_template( apply_filters( 'thaim_maintenance_template', $maintenance_templace ),  true );
		die();
		
	}
	
	function register_styles() {
		wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
		wp_register_style('thaim', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
		wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array('jquery'), '2.6.2'); // Modernizr with version Number at the end
	}
	
	function title_maintenance( $title, $sep, $seplocation )
	{
		return __('Under maintenance', 'thaim') . $seplocation;
	}
	
	function body_maintenance( $classes )
	{
		$classes[] = 'thaim-maintenance';
		return $classes;
	}
}


function thaim_maintenance_head() {
	
	wp_print_styles('normalize');
	wp_print_styles('thaim');
	wp_print_scripts('modernizr');
	
	?>
	<script type="text/javascript">
	
		jQuery(document).ready(function($) {
			var thaim_maintenance_height = $('#thaim-info-maintenance').height();
			var margintop = Number( ( $(window).height() / 2 ) - ( thaim_maintenance_height * 1.5 ) );
			$('#thaim-info-maintenance').css('margin-top', margintop+'px');
		});
		
	</script>
	<?php
}

function thaim_maintenance_info() {
	
	$info = apply_filters('thaim_maintenance_info', '<h3><span aria-hidden="true" data-icon="&#xe054;"> '. __('will be up and running soon !', 'thaim') .'</h3>');
	
	echo $info;
}