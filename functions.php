<?php
/**
 *  Author: @imath
 *  URL: imathi.eu
 *
 * 	Requires WordPress 4.7
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
	public static $required_wp_version = 4.7;

	/**
	 * Initialize the theme
	 */
	private function __construct() {
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
		$this->version = '2.2.4';

		if ( empty( $GLOBALS['content_width'] ) ) {
		    $GLOBALS['content_width'] = 600;
		}

		$this->requires_wp_upgrade = ! isset( $GLOBALS['wp_version'] ) || (float) $GLOBALS['wp_version'] < self::$required_wp_version;
		$this->is_maintenance_mode = (bool) get_theme_mod( 'maintenance_mode', 0 );
		$this->contact_page_id     = (int)  get_theme_mod( 'contact_page', 0 );
		$this->entrepot_page_id     = (int)  get_theme_mod( 'entrepot_page', 0 );

		$this->inc_dir = trailingslashit( get_template_directory() ) . 'includes';
	}

	/**
	 * Include required files
	 *
	 * @since 2.1.0 Drop Specific BuddyPress functions.
	 */
	private function includes() {
		$inc_dir = trailingslashit( $this->inc_dir );

		if ( ! $this->requires_wp_upgrade ) {

			require_once( $inc_dir . 'functions.php'  );
			require_once( $inc_dir . 'tags.php'       );
			require_once( $inc_dir . 'customizer.php' );
			require_once( $inc_dir . 'tax-meta.php'   );
			require_once( $inc_dir . 'widgets.php'    );

			if ( is_admin() ) {
				require_once( $inc_dir . 'upgrade.php' );
			}
		}

		if ( $this->is_maintenance_mode || $this->requires_wp_upgrade ) {
			require_once(  $inc_dir . 'maintenance.php' );
		}
	}

	private function setup_supports() {
		// Localisation Support
		load_theme_textdomain( 'thaim', get_template_directory() . '/languages' );

		if ( $this->requires_wp_upgrade ) {
			add_action( 'admin_notices', array( $this, 'warning' ) );

			// No need to carry on.
			return;
		}

		// Enables post and comment RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 800, 400, true );

		// Title tag
		add_theme_support( 'title-tag' );

		// Custom Logo
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => false,
			'flex-width'  => false,
		) );

		// Add Menu Support
		add_theme_support( 'menus' );

		// Menus
		register_nav_menus( array(
			'header-menu' => __( 'Header Menu', 'thaim' ),
			'home-menu'   => __( 'Home Menu', 'thaim' ),
		) );

		// Load Custom styles inside the wp editor
		add_editor_style( array( sprintf( 'css/editor-style%s.css', thaim_min_suffix() ), thaim_get_font_url() ) );

		// Specific shortcode for Action buttons
		add_shortcode( 'thaim_button', 'thaim_button_shortcode_handler' );
	}

	public function warning() {
		if ( did_action( 'thaim_warning_displayed' ) ) {
			return;
		}

		printf(
			'<div id="message" class="error"><p>%s</p></div>',
			sprintf( __( 'Thaim requires WordPress %s, please upgrade.', 'thaim' ), self::$required_wp_version )
		);

		do_action( 'thaim_warning_displayed' );
	}
}

function thaim() {
	return Thaim::start();
}
add_action( 'after_setup_theme', 'thaim' );
