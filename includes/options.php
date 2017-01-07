<?php
/**
 * Thaim options
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register the form setting for our thaim options.
 *
 * @since thaim 1.0-beta1
 */
function thaim_theme_options_init() {

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'thaim_maintenance' // Menu slug, used to uniquely identify the page;
	);

	add_settings_field(
		'thaim_maintenance',
		__( 'Put the blog in maintenance mode', 'thaim' ),
		'thaim_settings_field_maintenance',
		'thaim_maintenance',
		'general'
	);

	register_setting(
		'thaim_options',
		'thaim_maintenance_mode',
		'thaim_maintenance_validate'
	);

	add_settings_field(
		'thaim_page_contact',
		__( 'ID of the contact page', 'thaim' ),
		'thaim_settings_page_contact',
		'thaim_maintenance',
		'general'
	);

	register_setting(
		'thaim_options',
		'thaim_contact_page',
		'thaim_contact_page_validate'
	);
}
add_action( 'admin_init', 'thaim_theme_options_init' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since thaim 1.0-beta1
 */
function thaim_options_add_page() {
	$thaim_page = add_theme_page(
		__( 'Maintenance mode', 'thaim' ),   // Name of page
		__( 'Maintenance mode', 'thaim' ),   // Label in menu
		'edit_theme_options',                // Capability required
		'thaim_maintenance',                 // Menu slug, used to uniquely identify the page
		'thaim_options_render_page'          // Function that renders the options page
	);

	if ( ! $thaim_page ) {
		return;
	}

	add_action( "load-$thaim_page", 'thaim_options_help' );
}
add_action( 'admin_menu', 'thaim_options_add_page' );

function thaim_options_help() {
	$help = sprintf( '<p><strong>%1$s</strong> %2$s</p><p>%3$s</p>',
		esc_html__( 'Put the blog in maintenance mode:', 'thaim' ),
		esc_html__( 'use this option if you want to work on your blog and hide its content to regular users', 'thaim' ),
		esc_html__( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'thaim' )
	);

	$screen = get_current_screen();

	$screen->add_help_tab( array(
		'title' => __( 'Overview', 'thaim' ),
		'id' => 'theme-options-help',
		'content' => $help,
	) );
}

function thaim_settings_field_maintenance() {
	$option = get_option( 'thaim_maintenance_mode', 0 );
	?>
	<label class="description">
		<input type="radio" name="thaim_theme_options[thaim_maintenance]" value="1" <?php checked( $option, 1 );?> /> <?php _e('Yes', 'thaim');?>
		<input type="radio" name="thaim_theme_options[thaim_maintenance]" value="0" <?php checked( $option, 0 );?> /> <?php _e('No', 'thaim');?>
	</label>
	<?php
}

function thaim_settings_page_contact() {
	$option = get_option( 'thaim_contact_page', 0 );
	?>
	<input name="thaim_theme_options[thaim_page_contact]" type="text" class="regular code" value="<?php echo (int) $option; ?>" />
	<?php
}

function thaim_options_render_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Maintenance mode', 'thaim' );?></h1>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'thaim_options' );
				do_settings_sections( 'thaim_maintenance' );
				submit_button();
			?>
		</form>

	</div>
	<?php
}

function thaim_maintenance_validate() {
	$input = $_POST['thaim_theme_options']['thaim_maintenance'];

	$output = intval( $input );

	return apply_filters( 'thaim_maintenance_validate', $output, $input );
}

function thaim_contact_page_validate() {
	$input = $_POST['thaim_theme_options']['thaim_page_contact'];

	$output = intval( $input );

	return apply_filters( 'thaim_maintenance_validate', $output, $input );
}
