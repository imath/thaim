<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Properly enqueue styles and scripts for our thaim options page.
 *
 * @since thaim 1.0-beta1
 *
 */
function thaim_admin_enqueue_style( $hook_suffix ) {
	wp_enqueue_style( 'thaim-options', get_template_directory_uri() . '/css/thaim-options.css', false, '2012-12-29' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'thaim_admin_enqueue_style' );

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
		'theme_options' // Menu slug, used to uniquely identify the page;
	);

	// Register our individual settings fields
	add_settings_field(
		'prettify_shortcode',  // Unique identifier for the field for this section
		__( 'Shortocode to prettify your snippets', 'thaim' ), // Setting field label
		'thaim_settings_field_prettify_shortcode', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page;
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);
	
	register_setting(
		'thaim_options',       // Options group
		'thaim_use_prettify', // Database option
		'thaim_use_prettify_validate' // The sanitization callback
	);

	add_settings_field( 
		'link_wp_org', 
		__( 'Link your WordPress.org plugins', 'thaim' ), 
		'thaim_settings_field_link_wp_org', 
		'theme_options', 
		'general' 
	);
	
	register_setting(
		'thaim_options',
		'thaim_link_wordpress_org',
		'thaim_link_wordpress_org_validate'
	);
	
	add_settings_field( 
		'list_github',
		__( 'List your github repos', 'thaim' ), 
		'thaim_settings_field_list_github',
		'theme_options',
		'general' 
	);
	
	register_setting(
		'thaim_options',
		'thaim_list_github_repos',
		'thaim_list_github_repos_validate'
	);
	
	add_settings_field( 
		'thaim_maintenance',
		__( 'Put the blog in maintenance mode', 'thaim' ), 
		'thaim_settings_field_maintenance',
		'theme_options',
		'general' 
	);
	
	register_setting(
		'thaim_options',
		'thaim_maintenance_mode',
		'thaim_maintenance_validate'
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
		__( 'Theme Options', 'thaim' ),   // Name of page
		__( 'Theme Options', 'thaim' ),   // Label in menu
		'edit_theme_options',             // Capability required
		'theme_options',                  // Menu slug, used to uniquely identify the page
		'thaim_options_render_page'       // Function that renders the options page
	);

	if ( ! $thaim_page )
		return;

	add_action( "load-$thaim_page", 'thaim_options_help' );
}
add_action( 'admin_menu', 'thaim_options_add_page' );

function thaim_options_help() {

	$help = '<p>' . __( 'You can customize the behavior of thaim within this theme options page.', 'thaim' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Shortcode to prettify your snippets</strong>: You can choose to activate this option in order to add a quicktag to the WordPress editor. You will be able to paste your code in the window or reference a github gists or a file from one of your github repo.', 'thaim' ) . '</li>' .
				'<li>' . __( '<strong>Link your WordPress.org plugins</strong>: by adding your WordPress.org user name, you will be able to create a page to list up to 20 of your WordPress plugins ordered by their latest activity. To finish the process, you will need to create a page and to choose the Plugins template in the available templates of this theme.', 'thaim' ) . '</li>' .
				'<li>' . __( '<strong>List your github repos</strong>: By adding your github user name, you will be able to list your github repos thanks to the same template. This list is refreshed each hour using the WordPress cron of this blog. Make sure your host provides the curl method to take benefit of this feature', 'thaim' ) . '</li>' .
				'<li>' . __( '<strong>Put the blog in maintenance mode</strong>: use this option if you want to work on your blog and hide its content to regular users', 'thaim' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'thaim' ) . '</p>';

	$sidebar = '<p><strong>' . __( 'For more information:', 'thaim' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://imathi.eu/tag/thaim" target="_blank">Blog post about Thaim</a>', 'thaim' ) . '</p>';

	$screen = get_current_screen();

	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3
		$screen->add_help_tab( array(
			'title' => __( 'Overview', 'thaim' ),
			'id' => 'theme-options-help',
			'content' => $help,
			)
		);

		$screen->set_help_sidebar( $sidebar );
	} else {
		// WordPress 3.2
		add_contextual_help( $screen, $help . $sidebar );
	}
}

function thaim_settings_field_prettify_shortcode() {
	$option = get_option( 'thaim_use_prettify', 0 );
	
	?>
	<label class="description">
		<input type="radio" name="thaim_theme_options[prettify_shortcode]" value="1" <?php checked( $option, 1 );?> /> <?php _e('Yes', 'thaim');?>
		<input type="radio" name="thaim_theme_options[prettify_shortcode]" value="0" <?php checked( $option, 0 );?> /> <?php _e('No', 'thaim');?>
	</label>
	<?php
}

function thaim_settings_field_link_wp_org() {
	$option = get_option( 'thaim_link_wordpress_org', array( 'username' => '', 'perpage' => 20 ) );
	?>
	<div class="link-wp-org">
		<label class="description">
			<span><?php _e('Fill with your WordPress.org user name', 'thaim');?></span>
			<input type="text" name="thaim_theme_options[link_wp_org][username]" value="<?php echo $option['username'];?>" />
		</label>
		<label class="description">
			<span><?php _e('Number of plugins to display (up to 20)', 'thaim');?></span>
			<input type="text" name="thaim_theme_options[link_wp_org][perpage]" value="<?php echo $option['perpage'];?>" />
		</label>
	</div>
	<?php
}

function thaim_settings_field_list_github() {
	$option = get_option( 'thaim_list_github_repos', '' );
	?>
	<div class="list-github">
		<label class="description">
			<span><?php _e('Fill with your github user name', 'thaim');?></span>
			<input type="text" name="thaim_theme_options[list_github]" value="<?php echo $option;?>" />
		</label>
	</div>
	<?php
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

function thaim_options_render_page() {
	do_action( 'thaim_options_displayed' );
	?>
	<div class="wrap">
		<?php screen_icon();?>
		<h2><?php _e('Thaim options', 'thaim');?></h2>
		<?php settings_errors(); ?>
		
		<form method="post" action="options.php">
			<?php
				settings_fields( 'thaim_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
		
	</div>
	<?php
}

function thaim_use_prettify_validate() {
	$input = $_POST['thaim_theme_options']['prettify_shortcode'];
	
	$output = intval( $input );
	
	return apply_filters( 'thaim_use_prettify_validate', $output, $input );
}

function thaim_link_wordpress_org_validate() {
	$input = $_POST['thaim_theme_options']['link_wp_org'];
	
	$username = wp_kses( $input['username'], array() );
	$perpage = intval( $input['perpage'] );
	
	if ( empty( $perpage ) || $perpage > 20 )
		$perpage = 20;
	
	$output = array( 'username' => $username, 'perpage' => $perpage );
	
	return apply_filters( 'thaim_link_wordpress_org_validate', $output, $input );
}

function thaim_list_github_repos_validate() {
	$input = $_POST['thaim_theme_options']['list_github'];
	
	$output = wp_kses( $input, array() );
	
	return apply_filters( 'thaim_list_github_repos_validate', $output, $input );
}

function thaim_maintenance_validate() {
	$input = $_POST['thaim_theme_options']['thaim_maintenance'];
	
	$output = intval( $input );
	
	return apply_filters( 'thaim_maintenance_validate', $output, $input );
}


function thaim_sets_wp_cron() {
	
	$github = get_option( 'thaim_list_github_repos' );
	
	if ( empty( $github ) ) {
		$cron_is_enable = false;
	} else {
		$cron_is_enable = true;
	}
		
	$cron_is_enable = apply_filters('thaim_sets_wp_cron', $cron_is_enable );
		
	if ( $cron_is_enable ) {
		if ( ! wp_next_scheduled( 'thaim_github_cron_job' ) ) { 
				//schedule the event to run hourly 
		        wp_schedule_event( time(), 'hourly', 'thaim_github_cron_job' ); 
		} 
	} else {
		$timestamp = wp_next_scheduled( 'thaim_github_cron_job' );
		//unschedule custom action hook 
		wp_unschedule_event( $timestamp, 'thaim_github_cron_job' );
	}
}
add_action( 'thaim_options_displayed', 'thaim_sets_wp_cron' );