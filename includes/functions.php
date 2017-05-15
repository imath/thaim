<?php
/**
 * Thaim functions
 *
 * @package Thaim
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
	wp_enqueue_style( 'thaim', get_stylesheet_uri(), array( 'dashicons' ), thaim()->version, 'all' );
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

		return sprintf( '<script src="%s"></script>', esc_url( $url ) );
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

/**
 * Use a specific comments template for the Page used as a contact one.
 *
 * @since  2.1.0
 *
 * @return string the relative path to the comments template.
 */
function thaim_get_comment_template() {
	$post = get_post();

	$return = '';
	if ( ! empty( $post->ID ) && (int) $post->ID === thaim()->contact_page_id ) {
		$return = '/questions.php';
	}

	return $return;
}

/**
 * Set a comment to be a question using the comment_type property.
 *
 * @since  2.1.0
 *
 * @param  array  $comment_data The array containing the comment data to be saved in DB.
 * @return array                The array containing the comment data to be saved in DB.
 */
function thaim_preprocess_comment( $comment_data = array() ) {
	if ( ! empty( $comment_data['comment_post_ID'] ) && (int) $comment_data['comment_post_ID'] === thaim()->contact_page_id ) {
		$comment_data['comment_type'] = 'question';
	}

	return $comment_data;
}
add_filter( 'preprocess_comment', 'thaim_preprocess_comment', 10, 1 );

/**
 * Add an option to the comment's types dropdown in the comments screen.
 *
 * @since  2.1.0
 *
 * @param  array  $types The available comment's types (ping and regular comment by default).
 * @return array         The available comment's types (default types + the 'question' one).
 */
function thaim_admin_comment_types_dropdown( $types = array() ) {
	$types['question'] = esc_attr__( 'Questions', 'thaim' );

	return $types;
}
add_filter( 'admin_comment_types_dropdown', 'thaim_admin_comment_types_dropdown', 10, 1 );

/**
 * Send a multipart email containing the question and the reply.
 *
 * @since  2.1.0
 *
 * @param  WP_Comment $reply    The reply object to the submitted question.
 * @param  WP_Comment $question The question object.
 * @return bool                 True if the email was sent. False otherwise.
 */
function thaim_mail( WP_Comment $reply, WP_Comment $question ) {
	if ( empty( $reply->comment_content ) || empty( $question->comment_content ) || empty( $question->comment_author_email ) ) {
		return false;
	}

	ob_start();
	get_template_part( 'email' );
	$email_template = ob_get_clean();

	if ( empty( $email_template ) ) {
		return false;
	}

	$charset        = get_option( 'blog_charset' );
	$boundary       = uniqid( '_Part_' );
	$plain_question = wp_kses( $question->comment_content, array() );
	$plain_reply    = wp_kses( $reply->comment_content, array() );

	$pagetitle     = esc_attr( get_bloginfo( 'name', 'display' ) );
	$subject       = esc_html__( 'Reply to your message', 'thaim' );
	$html_question = apply_filters( 'the_content', $question->comment_content );
	$html_reply    = apply_filters( 'the_content', $reply->comment_content );

	$email = str_replace( '{{pagetitle}}', $pagetitle,     $email_template );
	$email = str_replace( '{{question}}',  $html_question, $email          );
	$email = str_replace( '{{reply}}',     $html_reply,    $email          );

	// Sender
	$domain = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $domain, 0, 4 ) === 'www.' ) {
		$domain = substr( $domain, 4 );
	}

	$admin_email = get_option( 'admin_email', 'no-reply@' . $domain );

	// Plain text email
	thaim()->plain_text_email = sprintf( '
%1$s
--------------------
%2$s
____________________

%3$s
--------------------
%4$s
', esc_html__( 'Your message:', 'thaim' ), $plain_question, esc_html__( 'My reply:', 'thaim' ), $plain_reply );

	add_action( 'phpmailer_init', 'thaim_append_plain_text_email', 10, 1 );

	$sent = @wp_mail(
		$question->comment_author_email,
		$subject,
		$email,
		"MIME-Version: 1.0\n" . "From: [{$pagetitle}] <{$admin_email}>\n"
	);

	remove_action( 'phpmailer_init', 'thaim_append_plain_text_email', 10, 1 );

	if ( ! $sent ) {
		return false;
	}

	return true;
}

/**
 * Make sure there's a plain text alternative version of the HTML message
 * sent.
 *
 * NB: follow the progress made on
 * https://core.trac.wordpress.org/ticket/15448
 *
 * @since 2.1.0
 *
 * @param PHPMailer $phpmailer The Mailer class.
 */
function thaim_append_plain_text_email( PHPMailer $phpmailer ) {
	$thaim = thaim();

	if ( ! empty( $thaim->plain_text_email ) ) {
		$phpmailer->AltBody = $thaim->plain_text_email;

		unset( $thaim->plain_text_email );
	}
}

/**
 * Send an email when a reply to a question is added.
 *
 * @since  2.1.0
 *
 * @param  int           $comment_ID  The reply ID.
 * @param  int           $approved    The status of it (1 for approved, 0 for holded).
 * @param  array         $commentdata The reply's data used to save the reply in DB.
 * @return null|WP_Error              An error if sending the email failed.
 */
function thaim_comment_post( $comment_ID = 0, $approved = 0, $commentdata = array() ) {
	if ( 1 !== (int) $approved ) {
		return;
	}

	$comment = get_comment( $comment_ID );

	if ( 'question' !== $comment->comment_type || empty( $comment->comment_parent ) || empty( $comment->comment_content ) ) {
		return;
	}

	$parent = get_comment( $comment->comment_parent );

	if ( empty( $parent->comment_author_email ) || empty( $parent->comment_content ) ) {
		return;
	}

	if ( ! thaim_mail( $comment, $parent ) ) {
		return new WP_Error( 'email_error', __( 'The reply couldnot be sent', 'thaim' ) );
	}
}
add_action( 'comment_post', 'thaim_comment_post', 10, 3 );

/**
 * Style the specific "gravatar" to easily visually see the difference between regular comments and questions.
 *
 * @since  2.1.0
 *
 * @return string CSS Output.
 */
function thaim_admin_questions_inline_style() {
	wp_add_inline_style( 'common', '
		.thaim-question {
			display: inline-block;
			float: left;
			margin-right: 10px;
			margin-top: 1px;
			width: 32px;
			height: 32px;
			background-color: transparent;
			background-repeat: no-repeat;
			-webkit-background-size: 32px 32px;
			background-size: 32px;
			background-position: center;
			-webkit-transition: background .1s ease-in;
			transition: background .1s ease-in;
			background-image: url("data:image/svg+xml;charset=utf8,%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22utf-8%22%3F%3E%3C%21DOCTYPE%20svg%20PUBLIC%20%22-%2F%2FW3C%2F%2FDTD%20SVG%201.1%2F%2FEN%22%20%22http%3A%2F%2Fwww.w3.org%2FGraphics%2FSVG%2F1.1%2FDTD%2Fsvg11.dtd%22%3E%3Csvg%20version%3D%221.1%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20xmlns%3Axlink%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20d%3D%22M19%2014.5v-9c0-0.83-0.67-1.5-1.5-1.5h-14.010c-0.83%200-1.5%200.67-1.5%201.5v9c0%200.83%200.67%201.5%201.5%201.5h14.010c0.83%200%201.5-0.67%201.5-1.5zM17.69%205.39c0.33%200.33%200.15%200.67-0.030%200.84l-4.060%203.72%203.9%204.060c0.12%200.14%200.2%200.36%200.060%200.51-0.13%200.16-0.43%200.15-0.56%200.050l-4.37-3.73-2.14%201.95-2.13-1.95-4.37%203.73c-0.13%200.1-0.43%200.11-0.56-0.050-0.14-0.15-0.060-0.37%200.060-0.51l3.9-4.060-4.060-3.72c-0.18-0.17-0.36-0.51-0.030-0.84s0.67-0.17%200.95%200.070l6.24%205.040%206.25-5.040c0.28-0.24%200.62-0.4%200.95-0.070z%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E");
		}

		#dashboard-widgets .thaim-question {
			width: 50px;
			height: 50px;
			-webkit-background-size: 50px 50px;
			background-size: 50px;
		}
	' );
}
add_action( 'admin_enqueue_scripts', 'thaim_admin_questions_inline_style' );

/**
 * Use a specific "gravatar" for questions.
 *
 * @since  2.1.0
 *
 * @param  null $output                   A null value to not override the avatar.
 * @param  WP_Comment|int|string $comment A comment object, a user ID or a user email.
 * @return string                         HTML Output.
 */
function thaim_set_comment_author_avatar( $output = null, $comment = null ) {
	if ( empty( $comment ) || ! is_a( $comment, 'WP_Comment' ) ) {
		return $output;
	}

	if ( ! empty( $comment->comment_type ) && 'question' === $comment->comment_type ) {
		$output = '<span class="thaim-question"></span>';
	}

	return $output;
}
add_filter( 'pre_get_avatar', 'thaim_set_comment_author_avatar', 11, 2 );

/**
 * Use a specific location to redirect the user once a question is posted.
 *
 * @since  2.1.0
 *
 * @param  string     $location The regular redirect URL used by WordPress once a comment is posted.
 * @param  WP_Comment $comment  The comment object.
 * @return string               The regular redirect URL or the specific to questions one.
 */
function thaim_question_post_redirect( $location = '', $comment = null ) {
	if ( ! empty( $comment->comment_type ) && 'question' === $comment->comment_type ) {
		$location = esc_url_raw( add_query_arg( 'message', $comment->comment_ID, get_permalink( $comment->comment_post_ID ) ) );
	}

	return $location;
}
add_filter( 'comment_post_redirect', 'thaim_question_post_redirect', 10, 2 );

function thaim_github_release( $atts = array(), $content = '' ) {
	// Merge default with shortcode attributes
	$a = shortcode_atts( array(
		'name'  => '',
		'label' => '',
		'tag'   => '',
		'logo'  => '',
	), $atts, 'thaim_github_release' );

	if ( empty( $a['name'] ) ) {
		return;
	}

	$name = sanitize_key( $a['name'] );

	// Check dayly updated transient for the plugin
	$plugin_data = get_site_transient( 'github_plugin_data_' . $name );

	if ( ! is_array( $plugin_data ) ) {
		$response = wp_remote_get( "https://api.github.com/repos/imath/{$name}/releases" );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$releases = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $releases ) ) {
			return false;
		}

		$plugin_data = array();
		foreach ( $releases as $release ) {
			$package = array();

			if ( ! empty( $release['assets'] ) ) {
				$package = reset( $release['assets'] );
			}

			$plugin_data[ $release['id'] ] = (object) array(
				'id'       => $release['id'],
				'url'      => $release['html_url'],
				'name'     => $release['name'],
				'tag_name' => $release['tag_name'],
				'package'  => $package,
			);

			set_site_transient( 'github_plugin_data_' . $name, $plugin_data, DAY_IN_SECONDS );
		}
	}

	if ( ! is_array( $plugin_data ) ) {
		if ( empty( $a['tag'] ) ) {
			return;
		}

		$tag = esc_html( $a['tag'] );
		if ( empty( $a['label'] ) ) {
			$a['label'] = $a['name'];
		}

		$release_data = (object) apply_filters( 'thaim_github_get_default', array(
			'name' => esc_html( $a['label'] ),

			// 1 is the plugin name, 2 is tag name.
			'url' => sprintf( 'https://github.com/imath/%1$s/releases/tag/%2$s', $name, $tag ),

			/**
			 * 1 is the plugin name, 2 is tag name.
			 * NB: make sure to upload a zip named plugin_name.zip as an asset to the release
			 */
			'browser_download_url' => sprintf( 'https://github.com/imath/%1$s/releases/download/%2$s/%1$s.zip', $name, $tag ),
		) );
	} else {
		rsort( $plugin_data );
		$release_data = reset( $plugin_data );
		$release_data->download_count = 0;

		if ( ! empty( $a['label'] ) ) {
			$release_data->name = esc_html( $a['label'] );
		}

		if ( ! empty( $release_data->package['browser_download_url'] ) ) {
			$release_data->browser_download_url = $release_data->package['browser_download_url'];
		} else {
			$release_data->browser_download_url = $release_data->url;
		}

		foreach ( $plugin_data as $p ) {
			if ( ! isset( $p->package ) || empty( $p->package['download_count'] ) ) {
				continue;
			}

			if ( ! empty( $a['tag'] ) && $p->tag_name === $a['tag'] ) {
				$release_data->id                   = $p->id;
				$release_data->url                  = $p->url;
				$release_data->name                 = $p->name;
				$release_data->tag_name             = $p->tag_name;
				$release_data->package              = $p->package;
				$release_data->browser_download_url = $release_data->package['browser_download_url'];
				$release_data->download_count       = (int) $p->package['download_count'];
			} else {
				$release_data->download_count += (int) $p->package['download_count'];
			}
		}
	}

	$count = '';
	if ( ! empty( $release_data->download_count ) ) {
		$count = sprintf( __( '<p class="description">Number of downloads: %d</p>', 'thaim' ), $release_data->download_count );
	}

	$view_ongithub = esc_html__( 'View on Github', 'thaim' );

	$version = esc_html__( 'Download', 'thaim' );
	if ( ! empty( $release_data->tag_name ) ) {
		$version = sprintf( esc_html__( 'Download tag %s', 'thaim' ), $release_data->tag_name );
	}

	$thumbnail = '<span class="custom-dashicons custom-dashicons-github"></span>';
	if ( ! empty( $a['logo'] ) ) {
		$thumbnail = sprintf( '<img class="plugin-icon" src="%s">', esc_url( $a['logo'] ) );
	}

	if ( ! empty( $content ) ) {
		$count = sprintf( '<p class="description">%s</p>', esc_html( $content ) ) . "\n" . $count;
	}

	if ( ! empty( $a['label'] ) ) {
		$release_data->name = esc_html( $a['label'] );
	}

	return sprintf( '
		<div class="plugin-card">
			<div class="plugin-card-top">
				<div class="name column-name">
					<h3>
						<a href="%1$s">
							%2$s
							%3$s
						</a>
					</h3>
				</div>
				<div class="desc column-description">
					%4$s
					<p class="description"><a href="%5$s" target="_blank">%6$s</a></p>
				</div>
				<div class="download">
					<button class="button submit">
						<span class="dashicons dashicons-download"></span>
						<a href="%1$s">%7$s</a>
					</button>
				</div>
			</div>
		</div>',
		esc_url( $release_data->browser_download_url ),
		$thumbnail,
		$release_data->name,
		$count,
		esc_url( $release_data->url ),
		$view_ongithub,
		$version
	);
}
add_shortcode( 'github_release', 'thaim_github_release' );
