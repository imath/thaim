<?php
/**
 * Thaim functions
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Thaim fonts
 *
 * @since 2.0.0
 */
function thaim_get_font_url() {
	$fonts     = array();
	$fonts_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'thaim' ) ) {
		$fonts[] = 'Open Sans';
	}

	/* translators: If there are characters in your language that are not supported
	 * by Shadows, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Shadows Into Light: on or off', 'thaim' ) ) {
		$fonts[] = 'Shadows Into Light';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Do we have sticky posts ?
 *
 * @since 2.0.0
 */
function thaim_has_stickies() {
	$stickies         = get_option( 'sticky_posts' );
	$has_stickies     = false;
	thaim()->stickies = array();

	if ( ! empty( $stickies ) && is_array( $stickies ) ) {
		thaim()->stickies = $stickies;
		$has_stickies     = true;
	}

	return $has_stickies;
}

/**
 * By default, ignore stickies in WP_Query
 *
 * @since 2.0.0
 *
 * @param  WP_Query $qv The current WP_Query object.
 * @return WP_Query     The current WP_Query object.
 */
function thaim_parse_query( WP_Query $qv ) {
	if ( empty( $qv->query_vars['ignore_sticky_posts'] ) ) {
		$qv->query_vars['ignore_sticky_posts'] = true;
	}

	return $qv;
}
add_action( 'parse_query', 'thaim_parse_query', 10, 1 );

/**
 * Theme Stylesheets
 *
 * @since 1.0.0
 */
function thaim_styles() {
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css', array(), '2.6.2', 'all' );

	$font_url = thaim_get_font_url();
	if ( ! empty( $font_url ) ) {
		wp_enqueue_style( 'thaim-fonts', esc_url_raw( $font_url ), array(), null );
	}

	wp_enqueue_style( 'thaim-1140', get_template_directory_uri() . '/css/1140.css', array(), 'all' );
	wp_enqueue_style( 'thaim', get_stylesheet_uri(), array( 'dashicons' ), '2.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'thaim_styles' );

/**
 * Theme Scripts
 *
 * @since 1.0.0
 */
function thaim_scripts() {
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array( 'jquery' ), '2.6.2' );
	wp_enqueue_script( 'thaimscripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '2.0.0' );

	// Load the needed scripts for Thaim Slider
	if ( is_front_page() && thaim_has_stickies() && 1 >= get_query_var( 'paged' ) ) {
		wp_enqueue_style( 'thaim-cycle-style', get_template_directory_uri() .'/css/slider.css' );
		wp_enqueue_script( 'thaim-cycle-js', get_template_directory_uri() .'/js/jquery.cycle2.min.js', array( 'jquery' ), '2.1.6', true );

		wp_add_inline_script( 'thaim-cycle-js', '
			jQuery( document ).ready( function( $ ) {
				$( ".thaim-hero-slide-container" ).cycle( {
					fx:		"scrollHorz",
				    pager:  ".thaim-slide-nav",
				    timeout: 8000,
				    slides : ".thaim-hero-slide"
				} );
			} );
		' );
	}

	// Threaded comments
	if ( is_singular() && comments_open() && 1 === (int) get_option('thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'thaim_scripts' );

/**
 * Dynamic sidebars
 *
 * @since 1.0.0
 */
function thaim_widgets_init() {
	// Define Sidebar Widget Area 1
	register_sidebar( array(
		'name'          => __( 'Widget Area 1', 'thaim' ),
		'description'   => __( 'Top sidebar area', 'thaim' ),
		'id'            => 'widget-area-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	// Define Sidebar Widget Area 2
	register_sidebar( array(
		'name'          => __( 'Widget Area 2', 'thaim' ),
		'description'   => __( 'Bottom sidebar area', 'thaim' ),
		'id'            => 'widget-area-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	// Define Custom Home page widget
	register_sidebar( array(
		'name'          => __( 'Home Page Widget', 'thaim' ),
		'description'   => __( 'Widgetable area of the custom-home-page template.', 'thaim' ),
		'id'            => 'home-widgets',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title %s">',
		'after_title'   => '</h3>'
	) );

	// Define Footer Widget areas
	register_sidebar( array(
		'name'          => __( 'Footer Area 1', 'thaim' ),
		'description'   => __( 'Left footer area', 'thaim' ),
		'id'            => 'footer-area-1',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Area 2', 'thaim' ),
		'description'   => __( 'Middle footer area', 'thaim' ),
		'id'            => 'footer-area-2',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Area 3', 'thaim' ),
		'description'   => __( 'Right footer area', 'thaim' ),
		'id'            => 'footer-area-3',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'thaim_widgets_init' );

function thaim_get_thumbnail_credit( $attr, WP_Post $attachment ) {
	if ( ! empty( $attachment->post_content ) ) {
		thaim()->thumbnail_overlay = $attachment->post_content;
	}

	return $attr;
}

function thaim_page_menu_args( $args ) {
	if ( ! isset( $args['theme_location'] ) || 'header-menu' !== $args['theme_location'] || isset( $args['show_home'] ) ) {
		return $args;
	}

	return array_merge( $args, array( 'show_home' => true ) );
}
add_filter( 'wp_page_menu_args', 'thaim_page_menu_args' );

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function thaim_wp_nav_menu_args($args = '') {
	$args['container'] = false;
	return $args;
}
add_filter( 'wp_nav_menu_args', 'thaim_wp_nav_menu_args' );

function thaim_excerpt_was_trimed( $text ) {
	if ( false !== strpos( $text, 'class="view-article"' ) ) {
		thaim()->excerpt_was_trimed = true;
	}

	return $text;
}
add_filter( 'wp_trim_words', 'thaim_excerpt_was_trimed', 10, 1 );

// Custom Excerpt length
function thaim_excerpt_length( $length = 0 ) {
    return 30;
}

function thaim_excerpt_more() {
	$post_id = get_the_ID();

	$link = sprintf( '<a href="%1$s" class="view-article">%2$s &rarr;</a>',
		esc_url( get_permalink( $post_id ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'thaim' ), get_the_title( $post_id ) )
	);

	return apply_filters( 'thaim_excerpt_more', ' &hellip; ' . $link );
}

function thaim_title_parts( $parts = array() ) {
	return array_intersect_key( $parts, array( 'title' => true ) );
}

function thaim_get_term_thumbnail( $term = null ) {
	if ( empty( $term ) ) {
		$term = get_queried_object();
	}

	if ( empty( $term->term_id ) ) {
		return false;
	}

	$thumbnail = get_term_meta( $term->term_id, '_thaim_term_image', true );

	if ( ! empty( $thumbnail ) ) {
		return $thumbnail;
	}

	return false;
}

function thaim_get_dashicon_classes( $dashicon = '' ) {
	if ( empty( $dashicon ) ) {
		$dashicon = 'dashicons-tag';
	}

	$classes = array( $dashicon );

	if ( false !== strpos( $dashicon, 'custom-dashicons' ) ) {
		$classes[] = 'custom-dashicons';
	} else {
		$classes[] = 'dashicons';
	}

	return array_reverse( $classes );
}

function thaim_get_term_dashicon( $term_id = 0 ) {
	if ( empty( $term_id ) ) {
		return false;
	}

	$icon = get_term_meta( $term_id, '_thaim_term_icon', true );

	return sprintf( '<span class="%s"></span>',  join( ' ', array_map( 'sanitize_html_class', thaim_get_dashicon_classes( $icon ) ) ) );
}

function thaim_button_shortcode_handler( $atts = array(), $content = null ) {
	$a = shortcode_atts( array(
		'dashicon'     => false,
		'url'          => '#',
		'title'        => '',
		'text_color'   => '#23282d',
		'border_color' => '#23282d',
		'bg_color'     => '#FFF',
		'classes'      => '',
	), $atts );

	if ( empty( $a['title'] ) ) {
		return;
	}

	if ( empty( $content ) ) {
		$content = $a['title'];
	}

	if ( ! empty( $a['classes'] ) ) {
		$a['classes'] = array_map( 'sanitize_html_class', (array) explode( ' ', $a['classes'] ) );
	} else {
		$a['classes'] = array();
	}

	if ( ! empty( $a['dashicon'] ) ) {
		$a['dashicon'] = sprintf( '<span class="%s"></span> ',  join( ' ', array_map( 'sanitize_html_class', thaim_get_dashicon_classes( $a['dashicon'] ) ) ) );
	}

	return sprintf( '<div class="thaim-button %8$s" style="background-color:%2$s;border-color:%3$s"><a href="%5$s" title="%6$s" style="color:%1$s;">%4$s%7$s</a></div>',
		esc_attr( $a['text_color'] ),
		esc_attr( $a['bg_color'] ),
		esc_attr( $a['border_color'] ),
		$a['dashicon'],
		esc_url( $a['url'] ),
		esc_attr( $a['title'] ),
		esc_html( $content ),
		join( ' ', $a['classes'] )
	);
}

function thaim_gist_handler( $matches, $attr, $url, $rawattr ) {
	// Only display gists on single pages
	if ( ! is_single() ) {
		return;
	}

	if ( isset( $matches[2] ) ) {
		$url = $matches[1] . '.js';

		if ( isset( $matches[5] ) ) {
			$url = add_query_arg( 'file', preg_replace( '/[\-\.]([a-z]+)$/', '.\1', $matches[5] ), $url );
		}

		printf( '<script src="%s"></script>', esc_url( $url ) );
	}
}

// Remove Actions
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // Index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // Prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // Start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function thaim_body_class( $classes ) {
    global $post;
    if ( is_home() ) {
        $key = array_search( 'blog', $classes );
        if ( $key > -1) {
            unset($classes[$key]);
        }
    } elseif ( is_page() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    } elseif ( is_singular() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    }

    if ( has_custom_logo() ) {
    	$classes[] = 'custom-logo';
    }

    return $classes;
}
add_filter( 'body_class', 'thaim_body_class' );

// Allow shortcodes in Dynamic Sidebar
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_text', 'shortcode_unautop' );

// Make sure there's a version of the site icon for the login logo
function thaim_login_screen_icon_size( $icon_sizes = array() ) {
	return array_merge( $icon_sizes, array( 84 ) );
}
add_filter( 'site_icon_image_sizes', 'thaim_login_screen_icon_size', 10, 1 );

// Use the site icon as the login screen logo
function thaim_login_screen_logo() {
	$logo_icon_url = get_site_icon_url( 84 );

	if ( ! empty( $logo_icon_url ) ) {
		wp_add_inline_style( 'login', sprintf( '
			#login h1 a {
				background-image: none, url(%s);
			}

			#login p.submit .button-primary.button-large {
				color: #FFF;
				background-color: #23282d;
				border-color: #23282d;
				-webkit-box-shadow: none;
				box-shadow: none;
				text-shadow: none;
			}

			#login p.submit .button-primary.button-large:hover {
				color: #23282d;
				background-color: #FFF;
				border-color: #23282d;
			}

			a:focus {
				color: #23282d;
				-webkit-box-shadow: none;
				box-shadow: none;
			}

			#login input[type="text"]:focus,
			#login input[type="password"]:focus {
				border-color: #23282d;
				-webkit-box-shadow: none;
				box-shadow: none;
			}
		', esc_url_raw( $logo_icon_url ) ) );
	}
}
add_action( 'login_init', 'thaim_login_screen_logo' );
