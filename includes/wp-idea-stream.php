<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * ========================================================================
 *  Thaim specific functions for WP Idea Stream
 *  Demo of possible ways to customize the plugin
 * ========================================================================
 */

/**
 * Register a setting to use stickies in slider
 * 
 * @param  array  $setting_fields list of thaim settings fields
 * @return array                  the new list
 */
function thaim_ideastream_settings( $setting_fields =array() ) {
	return array_merge( $setting_fields, array( 
		'ideastream_in_home' => array(
			'label'    => __( 'Use IdeaStream content in home slider', 'thaim' ),
			'callback' => 'thaim_ideastream_settings_field_in_home',
			'sanitize' => 'absint',
			'option'   => 'thaim_use_ideastream_in_home',
		)
	) );
}
add_filter( 'thaim_setting_fields', 'thaim_ideastream_settings', 10, 1 );

/**
 * Gets the IdeaStream Option
 * 
 * @param  integer $default Defaults to activated
 * @return bool             true if enabled, false otherwise
 */
function thaim_ideastream_option( $default = 1 ) {
	return (bool) get_option( 'thaim_use_ideastream_in_home', $default );
}

/**
 * Callback function for the ideastream setting
 * 
 * @return string HTML Output
 */
function thaim_ideastream_settings_field_in_home() {
	$ideastream_in_home = thaim_ideastream_option();
	?>
	<label class="description">
		<input type="radio" name="thaim_use_ideastream_in_home" value="1" <?php checked( true, $ideastream_in_home );?> /> <?php _e('Yes', 'thaim');?>
		<input type="radio" name="thaim_use_ideastream_in_home" value="0" <?php checked( false, $ideastream_in_home );?> /> <?php _e('No', 'thaim');?>
	</label>
	<?php
}
/**
 * Can be used by any theme.
 * Just create a sidebar template named sidebar-ideastream.php
 * Use dynamic_sidebar( 'widget-area-ideastream' ) in it
 * call it in your main ideastream.php template
 * Then use the Widgets Administration to add IdeaStream Widgets in it
 *
 * @uses  register_sidebar()
 */
function thaim_ideastream_widgets_sidebar() {
	register_sidebar( array(
        'name'          => __( 'IdeaStream Sidebar', 'thaim' ),
        'description'   => __( 'Show widgets only in IdeaStream pages', 'thaim' ),
        'id'            => 'widget-area-ideastream',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ) );

	// This second sidebare is to be used in thaim's home page
    register_sidebar( array(
        'name'          => __( 'IdeaStream Home', 'thaim' ),
        'description'   => __( 'Show widgets only on Home page', 'thaim' ),
        'id'            => 'widget-home-ideastream',
        'before_widget' => '<div id="%1$s" class="widget home-ideastream %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ) );
}
add_action( 'widgets_init', 'thaim_ideastream_widgets_sidebar', 11 );

/**
 * Check if any image can be used as a thumbnail in the slider
 * 
 * @param  string $content the content of the idea
 * @return mixed          url to the found thumbnail, false otherwise
 */
function thaim_ideastream_thumbnail_image( $content = '' ) {
	preg_match_all( '/\<img.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/', $content, $matches );

	if ( ! empty( $matches[1][0] ) ) {
		return $matches[1][0];
	}

	return false;
}

/**
 * Specific Slider to replace thaim's default one
 *
 * @uses   wp_idea_stream_ideas_get_stickies() to get the sticky ideas
 * @uses   IdeaStream loop
 * @return string HTML output
 */
function thaim_ideastream_slider() {
	$args = array( 
		'include' => wp_idea_stream_ideas_get_stickies(),
	);

	if ( ! empty( $args['include'] ) && wp_idea_stream_ideas_has_ideas( $args ) ) :
	?>
	<div class="thaim-hero-slide-container">

		<?php while ( wp_idea_stream_ideas_the_ideas() ) : wp_idea_stream_ideas_the_idea(); ?>

		<div class="thaim-hero-slide">

			<?php 
			// Try to get an image
			$thumbnail = false;
			$thumbnail = thaim_ideastream_thumbnail_image( wp_idea_stream_ideas_get_content() );

			if ( ! empty( $thumbnail ) ): ?>
				<div class="fivecol">
					<img src="<?php echo esc_url( $thumbnail );?>" alt="Image slider">
				</div>
				<div class="sevencol last">
					<div class="thaim-slide-article">

						<h2><a href="<?php wp_idea_stream_ideas_the_permalink();?>" title="<?php wp_idea_stream_ideas_the_title_attribute(); ?>"><?php wp_idea_stream_ideas_the_title(); ?></a></h2>

						<p class="desc"><?php wp_idea_stream_ideas_the_excerpt();?></p>

						<p class="readmore"><a class="view-article" href="<?php wp_idea_stream_ideas_the_permalink();?>" title="<?php wp_idea_stream_ideas_the_title_attribute();?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>
					</div>
				</div>
			<?php else:?>
				<div class="twelvecol">
					<div class="thaim-slide-article">
						<h2><a href="<?php wp_idea_stream_ideas_the_permalink();?>" title="<?php wp_idea_stream_ideas_the_title_attribute(); ?>"><?php wp_idea_stream_ideas_the_title(); ?></a></h2>

						<p class="desc"><?php wp_idea_stream_ideas_the_excerpt();?></p>

						<p class="readmore"><a class="view-article" href="<?php wp_idea_stream_ideas_the_permalink();?>" title="<?php wp_idea_stream_ideas_the_title_attribute(); ?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>
					</div>
				</div>
			<?php endif;?>
		</div>

		<?php endwhile; ?>

		<?php wp_idea_stream_maybe_reset_postdata(); ?>

	</div>
	<div class="thaim-slide-nav">

	</div>

	<?php else: ?>

		<h1><?php _e('Home', 'thaim');?></h1>

	<?php endif;

	wp_reset_query();
}

/**
 * Specific hooks to thaim
 *
 * @uses   is_front_page() to check if on blog's home page
 * @uses   wp_idea_stream_is_ideastream() to check we're in IdeaStream's territory
 */
function thaim_allow_ideastream_editor() {
	// remove this filter to allow the WP Editor to load
	remove_filter( 'user_can_richedit', 'thaim_is_for_coder' );

	$ideastream_in_home = thaim_ideastream_option();

	if ( ! empty( $ideastream_in_home ) ) {
		// Slider will be used for ideas
		remove_action( 'thaim_headline_slider', 'thaim_slider_handle' );
		add_action( 'thaim_headline_slider', 'thaim_ideastream_slider' );
	}

	if ( ( ! empty( $ideastream_in_home ) && is_front_page() ) || wp_idea_stream_is_ideastream() ) {
		remove_filter( 'excerpt_more', 'thaim_wp_view_article' );
	}
}
add_action( 'wp_idea_stream_actions', 'thaim_allow_ideastream_editor', 11 );

/**
 * Specific to thaim: make sure headline will be filled with IdeaStream's data
 *
 * @see  header-ideastream.php & ideastream.php thaim's templates
 *
 * @uses    wp_idea_stream_is_single_idea() to check if on a single Idea
 * @uses    esc_url() to sanitize the url
 * @uses    wp_idea_stream_get_root_url() to get IdeaStream Archive permalink
 * @uses    esc_html() to sanitize output
 * @uses    wp_idea_stream_archive_title() to get IdeaStream archive page title
 * @return  String HTML Output
 */
function thaim_ideastream_headline() {

	if ( wp_idea_stream_is_single_idea() ) {
		?>
		<h1><a href="<?php echo esc_url( wp_idea_stream_get_root_url() );?>"><?php echo esc_html( wp_idea_stream_archive_title() ) ;?></a></h1>
		<?php
	} else {
		?>
		<h1><?php the_title(); ?></h1>
		<?php
	}
}

/**
 * Adds a sidebar of widgets to home ( if template no-sidebar-page.php is used )
 *
 * @uses   dynamic_sidebar()
 * @return string HTML Output
 */
function thaim_ideastream_home_widgets() {
	?>
	<div class="home-widget">
		<?php if( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'widget-home-ideastream' ) ) ?>
	</div>
	<?php
}
add_action( 'thaim_no_sidebar_after_content', 'thaim_ideastream_home_widgets' );

/**
 * Checks if the Home sidebar is used to eventually catch the widget IDs for a later use
 * 
 * @param  array $sidebars_widgets
 * @uses   wp_idea_stream_set_idea_var() to catch the widget ids added in the home sidebar
 * @return array list of sidebars unchanged
 */
function thaim_ideastream_catch_home_sidebar( $sidebars_widgets = array() ) {
	if ( ! empty( $sidebars_widgets['widget-home-ideastream'] ) ) {
		wp_idea_stream_set_idea_var( 'home_widgets', $sidebars_widgets['widget-home-ideastream'] );
	}
	return $sidebars_widgets;
}
add_filter( 'sidebars_widgets', 'thaim_ideastream_catch_home_sidebar', 10 );

/**
 * Adds specific classes to home widgets
 * 
 * @param  array $params
 * @param  wp_idea_stream_get_idea_var() to get the list of widget ids catched
 * @return array  widget params with a new before_widget argument if needed
 */
function thaim_ideastream_sidebar_home( $params = array() ) {
	$home_widgets = wp_idea_stream_get_idea_var( 'home_widgets' );

	if ( ! empty( $home_widgets ) )  {
		$number   = count( $home_widgets );
		$last_key = $number - 1;

		// I want 3 widgets!
		if ( $number == 3 ) {

			$class  = 'fourcol';

			if ( $last_key == array_search( $params[0]['widget_id'], $home_widgets ) ) {
				$class  .= ' last';
			}
		}

		$params[0]['before_widget'] = str_replace( 'home-ideastream', $class, $params[0]['before_widget'] );
	}

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'thaim_ideastream_sidebar_home', 10, 1 );
