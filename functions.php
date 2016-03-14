<?php
/**
 *  Author: @imath
 *  URL: imathi.eu
 *
 * 	Requires WordPress 4.5
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

final class Thaim {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Required WordPress version for the theme
	 *
	 * @var string
	 */
	public static $required_wp_version = '4.5';

	/**
	 * Initialize the theme
	 */
	private function __construct() {
		if ( ! isset( $GLOBALS['wp_version'] ) || self::$required_wp_version < (float) $GLOBALS['wp_version'] ) {
			// Notice in admin
			return;
		}

		$this->setup_globals();
		$this->includes();
		$this->setup_supports();
	}

	/**
	 * Return an instance of this class.
	 */
	public static function start() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Sets some globals for the theme
	 */
	private function setup_globals() {
		$this->version = '2.0.0';

		if ( empty( $GLOBALS['content_width'] ) ) {
		    $GLOBALS['content_width'] = 600;
		}
	}

	/**
	 * Include required files
	 */
	private function includes() {
		require_once( get_template_directory() . '/includes/functions.php' );
		require_once( get_template_directory() . '/includes/tags.php' );
		require_once( get_template_directory() . '/includes/options.php' );
		require_once( get_template_directory() . '/includes/tax-meta.php' );
		require_once( get_template_directory() . '/includes/widgets.php' );

		if ( thaim_is_maintenance_mode() ) {
			require_once( get_template_directory() . '/includes/maintenance.php' );
		}

		if ( function_exists( 'buddypress' ) ) {
			require_once( get_template_directory() . '/includes/buddypress.php' );
		}

		if ( is_admin() ) {
			require_once( get_template_directory() . '/includes/upgrade.php' );
		}
	}

	private function setup_supports() {
		// Localisation Support
		load_theme_textdomain( 'thaim', get_template_directory() . '/languages' );

		// Enables post and comment RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 800, 400, true );

		// Title tag
		add_theme_support( 'title-tag' );

		// Custom Logo
		add_image_size( 'thaim-logo', 220, 60 );
		add_theme_support( 'custom-logo', array( 'size' => 'thaim-logo' ) );

		// Add Menu Support
		add_theme_support( 'menus' );

		// Header menu
		register_nav_menu( 'header-menu', __( 'Header Menu', 'thaim' ) );

		// Load Custom styles inside the wp editor
	    add_editor_style( array( 'css/editor-style.css', thaim_get_font_url() ) );

	    // Specific shortcode for Action buttons
	    add_shortcode( 'thaim_button', 'thaim_button_shortcode_handler' );

	    // Gist Support
	    wp_embed_register_handler( 'thaim_gist', '#(https://gist.github.com/imath/([a-zA-Z0-9]+)?)(\#file(\-|_)(.+))?$#i', 'thaim_gist_handler' );
	}
}

function thaim() {
	return Thaim::start();
}
add_action( 'after_setup_theme', 'thaim' );
