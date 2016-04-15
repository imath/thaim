<?php
/**
 * Thaim Maintenance mode
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function thaim_maintenance_info() {
	echo apply_filters( 'thaim_maintenance_info', '<h3><span class="dashicons dashicons-admin-tools"></span> '. __( 'will be up and running soon !', 'thaim' ) .'</h3>' );
}

function thaim_maintenance_upgrade() {
	return thaim()->requires_wp_upgrade;
}

/**
* thaim maintenance
* let's work with no pressure to finish the job..
*/
class Thaim_Maintenance {

	public function __construct() {
		$maintenance_page = thaim()->requires_wp_upgrade || ! current_user_can('manage_options' );

		if ( ! is_admin() && $maintenance_page ) {
			add_action( 'template_redirect',      array( $this, 'display_maintenance_page' ), 12    );
			add_action( 'thaim_maintenance_head', array( $this, 'enqueue_scripts' ),           1    );
			add_action( 'thaim_maintenance_head', 'wp_print_head_scripts',                    20    );
			add_action( 'thaim_maintenance_head', 'wp_print_styles',                          20    );
			add_filter( 'pre_get_document_title', array( $this, 'title_maintenance' ),        10, 1 );
			add_filter( 'body_class',             array( $this, 'body_maintenance' ),         10, 2 );

			if ( thaim()->requires_wp_upgrade ) {
				add_filter( 'thaim_maintenance_info', array( $this, 'please_upgrade' ), 10, 1 );
			}
		}
	}

	public function display_maintenance_page() {
		$maintenance_template = get_template_directory() . '/thaim-maintenance-page.php';

		status_header( 200 );

		load_template( apply_filters( 'thaim_maintenance_template', $maintenance_template ),  true );
		exit();
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css', array(), '1.0', 'all' );
		wp_enqueue_style( 'thaim-1140', get_template_directory_uri() . '/css/1140.css', array(), 'all' );
		wp_enqueue_style( 'thaim', get_stylesheet_uri(), array( 'dashicons' ), '2.0.0', 'all' );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array( 'jquery' ), '2.6.2' );

		/**
		 * wp_add_inline_script() was introduced in 4.5
		 */
		if ( ! thaim()->requires_wp_upgrade ) {
			wp_add_inline_script( 'modernizr', '
				jQuery(document).ready( function($) {
					var thaim_maintenance_height = $( "#thaim-info-maintenance" ).height();
					var margintop = Number( ( $(window).height() / 2 ) - ( thaim_maintenance_height * 1.5 ) );
					$( "#thaim-info-maintenance" ).css( "margin-top", margintop + "px" );
				});
			' );
		}
	}

	public function title_maintenance( $title = '' ) {
		return __( 'Under maintenance', 'thaim' );
	}

	public function body_maintenance( $classes ) {
		$classes[] = 'thaim-maintenance';
		return $classes;
	}

	public function please_upgrade( $message = '' ) {
		$required_version = Thaim::$required_wp_version;

		return sprintf(
			'<div class="dashicons dashicons-warning"></div><h3>%s</h3>',
			sprintf( __( 'Thaim requires WordPress %s, please upgrade.', 'thaim' ), $required_version )
		);
	}
}

new Thaim_Maintenance;
