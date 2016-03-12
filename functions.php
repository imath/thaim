<?php
/*
 *  Author: @imath
 *  URL: imathi.eu
 *  Credits: html5blank.com, _s & twentytwelve themes
 */


/*
 * ========================================================================
 * Theme Support
 * ========================================================================
 */
if ( ! isset( $content_width ) ) {
    $content_width = 600;
}


function thaim_setup() {
	// Localisation Support
	load_theme_textdomain( 'thaim', get_template_directory() . '/languages' );

	if ( ! isset( $GLOBALS['wp_version'] ) || 4.5 < (float) $GLOBALS['wp_version'] ) {
		// Notice in admin
		return;
	}

	if ( is_admin() ) {
		require_once( get_template_directory() . '/includes/thaim-upgrade.php' );
	}

	// Add Menu Support
	add_theme_support( 'menus' );

	// Enables post and comment RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 800, 400, true );

	// Title tag
	add_theme_support( 'title-tag' );

	// Custom Logo
	add_image_size( 'thaim-logo', 220, 60 );
	add_theme_support( 'custom-logo', array( 'size' => 'thaim-logo' ) );

	/*
	 * =====================================================================
	 * Thaim options
	 * =====================================================================
	 */
	require_once( get_template_directory() . '/includes/thaim-options.php' );

	require_once( get_template_directory() . '/includes/thaim-tax-meta.php' );

	/*
	 * ========================================================================
	 *  Shortcodes
	 * ========================================================================
	 */
	if ( 1 == get_option( 'thaim_use_prettify' ) ) {
		require_once( get_template_directory() . '/includes/thaim-code-shortcode.php' );
	}

	/*
	 * ========================================================================
	 *  Checking for maitenance mode..
	 * ========================================================================
	 */
	if ( thaim_is_maintenance_mode() ) {
		require_once( get_template_directory() . '/includes/thaim-maintenance.php' );

		$maintenance = new Thaim_Maintenance;
	}

	/*
	 * ========================================================================
	 *  Checking for BuddyPress
	 * ========================================================================
	 */
	if ( function_exists( 'buddypress' ) ) {
		require_once( get_template_directory() . '/includes/buddypress.php' );
	}

	// nav menus
	register_nav_menus( array( // Using array to specify more menus if needed
        'header-menu'  => __( 'Header Menu', 'thaim' ), // Main Navigation
        'sidebar-menu' => __( 'Sidebar Menu', 'thaim' ), // Sidebar Navigation
        'extra-menu'   => __( 'Extra Menu', 'thaim' ) // Extra Navigation if needed (duplicate as many as you need!)
    ) );

    add_editor_style( array( 'css/editor-style.css', thaim_get_font_url() ) );
}
add_action( 'after_setup_theme', 'thaim_setup' );

/*
 * ========================================================================
 * Widget in dynamic sidbar areas
 * ========================================================================
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
    ));

    // Define Sidebar Widget Area 2
    register_sidebar( array(
        'name'          => __( 'Widget Area 2', 'thaim' ),
        'description'   => __( 'Bottom sidebar area', 'thaim' ),
        'id'            => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

	// Define Footer Widget areas
    register_sidebar( array(
        'name'          => __( 'Footer Area 1', 'thaim' ),
        'description'   => __( 'Left footer area', 'thaim' ),
        'id'            => 'footer-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

	register_sidebar( array(
        'name'          => __( 'Footer Area 2', 'thaim' ),
        'description'   => __( 'Middle footer area', 'thaim' ),
        'id'            => 'footer-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

	register_sidebar( array(
        'name'          => __( 'Footer Area 3', 'thaim' ),
        'description'   => __( 'Right footer area', 'thaim' ),
        'id'            => 'footer-area-3',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));
}
add_action( 'widgets_init', 'thaim_widgets_init' );

/*
 * ========================================================================
 * Functions
 * ========================================================================
 */

function thaim_is_maintenance_mode() {
	return (bool) get_option( 'thaim_maintenance_mode', 0 );
}

/**
* this is specific to me
* you should edit line 33 of header..
*/
function thaim_blogname() {
	$blog_name = get_bloginfo('name');

	$blog_name_left = substr( $blog_name, 0, 5 );
	$blog_name = str_replace( $blog_name_left, '<span>'.$blog_name_left.'</span>', $blog_name);

	echo $blog_name;
}

// Thaim navigation
function thaim_nav() {
	wp_nav_menu( array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
	) );
}

if ( ! function_exists( 'thaim_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * @since Thaim 2.0
 */
function thaim_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
	</a>

	<?php endif; // End is_singular()
}
endif;

function thaim_page_menu_args( $args ) {
	if ( ! isset( $args['theme_location'] ) || 'header-menu' !== $args['theme_location'] || isset( $args['show_home'] ) ) {
		return $args;
	}

	return array_merge( $args, array( 'show_home' => true ) );
}
add_filter( 'wp_page_menu_args', 'thaim_page_menu_args' );


/* adds a navigation to adjacent post and a scroll top! */
function thaim_single_post_nav() {
	?>
	<nav class="nav-single">
		<div class="nav-top">
			<a href="#top" class="backtotop single" title="<?php _e('Jump to the top of the page', 'thaim');?>"><span aria-hidden="true" data-icon="&#xe09d;"></span> <?php _e('Back to top', 'thaim');?></a>
		</div>
		<br class="clear"/>
		<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '<span aria-hidden="true" data-icon="&#xe0b0;"></span>', 'Previous post link', 'thaim' ) . '</span> %title' ); ?></span>
		<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '<span aria-hidden="true" data-icon="&#xe09b;"></span>', 'Next post link', 'thaim' ) . '</span>' ); ?></span>
		<br class="clear"/>
	</nav><!-- .nav-single -->
	<?php
}


if ( ! function_exists( 'thaim_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Credit : Twenty Twelve 1.0
 */
function thaim_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'thaim' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'thaim' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'thaim' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'thaim' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'thaim' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'thaim' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'thaim' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;



if ( ! function_exists( 'thaim_entry_meta' ) ) :
/**
 * Credit : Twenty Twelve 1.0
 */
function thaim_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'thaim' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'thaim' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'thaim' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'thaim' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'thaim' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'thaim' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;


// Custom Excerpts
// Create 20 Word Callback for Index page Excerpts, call using thaim_wp_excerpt('thaim_wp_index');
function thaim_wp_index( $length = 0 ) {
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using thaim_wp_excerpt('thaim_wp_custom_post');
function thaim_wp_custom_post( $length = 0 ) {
    return 40;
}

// Create the Custom Excerpts callback
function thaim_wp_excerpt( $length_callback = '', $more_callback = '' ) {
    global $post;
    if ( function_exists( $length_callback ) ) {
        add_filter( 'excerpt_length', $length_callback );
    }
    if ( function_exists( $more_callback ) ) {
        add_filter( 'excerpt_more', $more_callback );
    }
    $output = get_the_excerpt();
    $output = apply_filters( 'wptexturize', $output );
    $output = apply_filters( 'convert_chars', $output );
    $output = '<p>' . $output . '</p>';
    echo $output;
}

function thaim_has_stickies() {
	$stickies = get_option( 'sticky_posts' );

	return ! empty( $stickies ) && is_array( $stickies );
}

/**
 * By default, ignore stickies in WP_Query
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

/*
 * ========================================================================
 * Headline and slider
 * ========================================================================
 */

function thaim_headline() {
	// if On home and
	if ( is_front_page() && thaim_has_stickies() ) {

		thaim_slider_handle();

	} else {

		if ( is_search() ):
			global $wp_query;
		?>
			<h2><?php echo sprintf( __( '%s Search Results for ', 'thaim' ), $wp_query->found_posts ); echo get_search_query(); ?></h2>

		<?php elseif( is_category() || is_tag() ):

			thaim_headline_term();

		elseif ( is_single() ):

			thaim_headline_single();

		else : ?>

			<h2><?php thaim_headline_h2(); ?></h2>

		<?php
		endif;
	}

	do_action( 'thaim_headline' );
}

function thaim_headline_h2() {
	echo thaim_headline_get_h2();
}

	function thaim_title_parts( $parts = array() ) {
		return array_intersect_key( $parts, array( 'title' => true ) );
	}

	function thaim_headline_get_h2() {
		add_filter( 'document_title_parts', 'thaim_title_parts', 10, 1 );

		$headline = wp_get_document_title();

		if ( $headline === get_bloginfo( 'sitename' ) ) {
			$headline = '';
		}

		remove_filter( 'document_title_parts', 'thaim_title_parts', 10, 1 );

		return apply_filters( 'thaim_headline_get_h2', $headline );
	}

function thaim_headline_term() {
	$term = get_queried_object();

	thaim_headline_html_for_cat_tags( $term->term_id, $term->name, $term->description );
}

function thaim_headline_html_for_cat_tags( $term_id, $term_name, $term_desc, $type = 'tag' ) {
	$icon = get_term_meta( $term_id, '_thaim_term_icon', true );

	$classes = array( 'dashicons-tag' );
	if ( ! empty( $icon ) ) {
		$classes = array( $icon );
	}

	$class = reset( $classes );
	if ( false !== strpos( $class, 'custom-dashicons' ) ) {
		$classes[] = 'custom-dashicons';
	} else {
		$classes[] = 'dashicons';
	}

	$output = join( ' ', array_map( 'sanitize_html_class', array_reverse( $classes ) ) );
	?>
	<h2><span class="<?php echo $output ;?>"></span> <?php echo esc_html( $term_name );?></h2>
	<?php
}

function thaim_get_term_thumbnail() {
	$term = get_queried_object();

	if ( empty( $term->term_id ) ) {
		return false;
	}

	$thumbnail = get_term_meta( $term->term_id, '_thaim_term_image', true );

	if ( ! empty( $thumbnail ) ) {
		return $thumbnail;
	}

	return false;
}

function thaim_post_term_description() {
	$term = get_queried_object();

	if ( empty( $term->term_id ) || empty( $term->description ) ) {
		return;
	}

	$term_thumbnail = thaim_get_term_thumbnail();
	?>
	<div class="term-description">

		<?php if ( $term_thumbnail ) : ?>
			<img class="header-image thaim-image" src="<?php echo esc_url( $term_thumbnail ); ?>">
		<?php endif ; ?>

		<blockquote>
			<?php echo esc_html( $term->description ) ;?>
		</blockquote>
	</div>
	<?php
}

function thaim_headline_single() {
	$post_title = get_the_title();

	if ( empty( $post_title ) ) {
		$post_title = thaim_headline_get_h2();
	}

	printf( '<h2>%s</h2>', $post_title );
}

function thaim_cycle() {
	if ( ! is_front_page() || ! thaim_has_stickies() ) {
		return;
	}

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
add_action( 'wp_enqueue_scripts', 'thaim_cycle' );

function thaim_cycle_settings() {
	?>
	<script>
		jQuery(document).ready(function($) {
			$( ".thaim-hero-slide-container" ).cycle( {
				fx:		'scrollHorz',
			    pager:  '.thaim-slide-nav',
			    timeout: 8000
			} );
		});
	</script>
	<?php
}
add_action( 'thaim_slider', 'thaim_slider_handle' );

function thaim_slider_handle() {
	if ( ! thaim_has_stickies() ) {
		return;
	}

	query_posts( array(
		'post__in' => get_option( 'sticky_posts' )
	) );

	if ( have_posts() ) :
	?>
	<div class="thaim-hero-slide-container">

		<?php while ( have_posts() ) : the_post(); ?>

		<div class="thaim-hero-slide">

			<?php if ( has_post_thumbnail() ):?>
				<div class="post-thumbnail fivecol">
					<?php the_post_thumbnail( 'medium' ); ?>
					<div class="cycle-overlay">
						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>
					</div>
				</div>
				<div class="sevencol last">
					<div class="thaim-slide-article">

						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>

						<p class="desc"><?php the_excerpt()?></p>

						<p class="readmore"><a class="view-article" href="<?php the_permalink()?>" title="<?php the_title();?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>
					</div>
				</div>
			<?php else: ?>
				<div class="twelvecol">
					<div class="thaim-slide-article">
						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>

						<p class="desc"><?php the_excerpt()?></p>

						<p class="readmore"><a class="view-article" href="<?php the_permalink()?>" title="<?php the_title();?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php endwhile; ?>

	</div>

	<div class="thaim-slide-nav"></div>

	<?php else: ?>

		<h2><?php esc_html_e( 'Home', 'thaim' );?></h2>

	<?php endif;

	wp_reset_query();
}

/*
 * ========================================================================
 * Actions + Filters
 * ========================================================================
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

// Theme Stylesheets using Enqueue
function thaim_styles() {
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css', array(), '2.6.2', 'all' );

	$font_url = thaim_get_font_url();
	if ( ! empty( $font_url ) ) {
		wp_enqueue_style( 'thaim-fonts', esc_url_raw( $font_url ), array(), null );
	}

	wp_enqueue_style( 'thaim-1140', get_template_directory_uri() . '/css/1140.css', array(), 'all' );
	wp_enqueue_style( 'thaim', get_stylesheet_uri(), array( 'dashicons' ), '2.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'thaim_styles' ); // Add Theme Stylesheet


// Load Custom Theme Scripts using Enqueue
function thaim_scripts() {
	if ( ! is_admin() ) {
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array( 'jquery' ), '2.6.2' );
		wp_enqueue_script( 'thaimscripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '2.0.0' );
    }
}
add_action( 'wp_enqueue_scripts', 'thaim_scripts' ); // Add Custom Scripts


// prettify the snippets if the post meta is set so.
function thaim_load_prettify() {

	global $post;

	if ( empty( $post->ID ) )
		return;

	if ( get_post_meta( $post->ID, 'prettifyed', true ) == 1 ) {

		wp_enqueue_style( 'prettify-css', get_template_directory_uri() . '/css/prettify.css');

		add_action( 'wp_footer', 'thaim_do_pretiffy');

		wp_enqueue_script('prettify-js', get_template_directory_uri() . '/js/prettify.js', array('jquery'), '1.0.0' );
	}

}
add_action( 'wp_print_styles', 'thaim_load_prettify' );

function thaim_do_pretiffy() {
	?>
	<script>
	jQuery(document).ready(function($){
		prettyPrint();

		// fixing it for WordPress..
		$('.prettyprint li').each( function(){

			if ($(this).html() == '<p></p>' )
				$(this).html( $(this).html().replace( '<p></p>', '') );

		});
	});
	</script>
	<?php
}


// Load Optimised Google Analytics in the footer
function add_google_analytics() {
    $google = "<!-- Optimised Asynchronous Google Analytics -->";
    $google .= "<script>"; // Change the UA-XXXXXXXX-X to your Account ID
    $google .= "var _gaq=[['_setAccount','UA-XXXXXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));";
    $google .= "</script>";
    echo $google;
}

//add_action('wp_footer', 'add_google_analytics'); // Google Analytics optimised in footer

// Threaded Comments
function enable_threaded_comments() {
    if ( ! is_admin() ) {
        if ( is_singular() AND comments_open() AND ( get_option('thread_comments' ) == 1 ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
}
add_action( 'get_header', 'enable_threaded_comments' ); // Enable Threaded Comments


// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action( 'wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ) );
}
add_action( 'widgets_init', 'my_remove_recent_comments_style' ); // Remove inline Recent Comment Styles from wp_head()

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function thaim_wp_pagination() {
    global $wp_query;
    $big = 999999999;
    echo paginate_links( array(
        'base'    => str_replace($big, '%#%', get_pagenum_link($big)),
        'format'  => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total'   => $wp_query->max_num_pages
    ) );
}
add_action( 'init', 'thaim_wp_pagination' ); // Add our Thaim Pagination

/**
* this is specific to me you can comment or customize with your twitter account
* and your paypal link
*/
function thaim_single_reader_add_actions() {
	?>
	&nbsp;&nbsp;
	<span class="twitter-share">
		<span class="dashicons dashicons-twitter"></span>
		<a href="https://twitter.com/intent/tweet?original_referer=<?php echo urlencode( get_permalink());?>&amp;source=tweetbutton&amp;text=<?php echo urlencode( get_the_title());?>&amp;url=<?php echo urlencode( get_permalink());?>&amp;via=imath" class="share-on-twitter single" title="<?php esc_attr_e( 'Share', 'thaim' )?>" target="_blank"><?php esc_html_e( 'Share', 'thaim' )?></a>
	</span>
	&nbsp;&nbsp;
	<span class="paypal-support">
		<span class="custom-dashicons custom-dashicons-paypal"></span>
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=2QSLY676C6HKE" title="<?php esc_attr_e( 'buy me a coffee ;)', 'thaim' );?>" target="_blank"><?php esc_html_e( 'Support', 'thaim' );?></a>
	</span>
	<?php
}
add_action( 'thaim_single_reader_actions', 'thaim_single_reader_add_actions' );

function thaim_add_feed_link() {

	$feed_link = get_feed_link();
	$title = __( 'blog', 'thaim' );

	if ( is_category() || is_tag() ) {
		$term = get_queried_object();

		$feed_link = is_tag() ? get_term_feed_link( $term->term_id, 'post_tag' ) : get_term_feed_link( $term->term_id, 'category' );
		$title = esc_html( $term->name );
	} elseif ( is_single() ) {
		$feed_link = get_post_comments_feed_link();
		$title = __( 'comment', 'thaim' );
	}

	$title = sprintf( __( 'Subscribe to %s feed', 'thaim' ), $title );
	?>
	<div class="sidebar-widget">
		<div class="widget">
			<h3 class="widget-title rss-feed"><a href="<?php echo $feed_link;?>" title="<?php echo $title;?>"><span aria-hidden="true" data-icon="&#xe0e9;"></span> <?php echo $title;?></a></h3>
		</div>
	</div>
	<?php
}
add_action( 'thaim_before_sidebar_widgets', 'thaim_add_feed_link');

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

// Add Filters

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
add_filter( 'body_class', 'thaim_body_class' ); // Add slug to body class (Starkers build)


add_filter( 'widget_text', 'do_shortcode' ); // Allow shortcodes in Dynamic Sidebar
add_filter( 'widget_text', 'shortcode_unautop' ); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter( 'the_excerpt', 'shortcode_unautop' ); // Remove auto <p> tags in Excerpt (Manual Excerpts only)

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function thaim_wp_nav_menu_args($args = '') {
    $args['container'] = false;
    return $args;
}
add_filter( 'wp_nav_menu_args', 'thaim_wp_nav_menu_args' ); // Remove surrounding <div> from WP Navigation


// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list( $thelist ) {
    return str_replace( 'rel="category tag"', 'rel="tag"', $thelist );
}
add_filter( 'the_category', 'remove_category_rel_from_category_list' ); // Remove invalid rel attribute


// thaim doesn't need a read more link as we only use excerpt !
function thaim_wp_view_article( $more ) {
    return '';
}
add_filter( 'excerpt_more', 'thaim_wp_view_article' ); // Well i dont need as i always use the excerpt field..

function thaim_add_caption( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {

	if ( empty($caption) || apply_filters( 'disable_captions', '' ) )
		return $html;

	$id = ( 0 < (int) $id ) ? 'attachment_' . $id : '';

	$caption = str_replace( array("\r\n", "\r"), "\n", $caption);
	$caption = preg_replace_callback( '/<[a-zA-Z0-9]+(?: [^<>]+>)*/', '_cleanup_image_add_caption', $caption );
	// convert any remaining line breaks to <br>
	$caption = preg_replace( '/[ \n\t]*\n[ \t]*/', '<br />', $caption );

	$html = preg_replace( '/(class=["\'][^\'"]*)align(none|left|right|center)\s?/', '$1', $html );
	if ( empty($align) )
		$align = 'none';

	$shcode = '[caption id="' . $id . '" align="align' . $align	. '" width="590px"]' . $html . ' ' . $caption . '[/caption]';

	return apply_filters( 'thaim_add_caption', $shcode, $html );
}
add_filter( 'image_send_to_editor', 'thaim_add_caption', 21, 8 );

//limit the number of tags in tag cloud
function thaim_tag_cloud_args( $args ) {
	$args['number'] = 20;

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'thaim_tag_cloud_args' );

// Remove Filters
remove_filter( 'the_excerpt', 'wpautop' ); // Remove <p> tags from Excerpt altogether
remove_filter( 'image_send_to_editor', 'image_add_caption' );

/*
 * ========================================================================
 *  Thaim specific functions for WP Idea Stream
 *  Demo of possible ways to customize the plugin
 * ========================================================================
 */

/**
 * Can be used by any theme.
 * Just create a sidebar template named sidebar-ideastream.php
 * Use dynamic_sidebar( 'widget-area-ideastream' ) in it
 * call it in your main ideastream.php template
 * Then use the Widgets Administration to add IdeaStream Widgets in it
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
}
add_action( 'widgets_init', 'thaim_ideastream_widgets_sidebar', 11 );

// Specific to Thaim
function thaim_allow_ideastream_editor() {
	remove_filter( 'user_can_richedit', 'thaim_is_for_coder' );

	if ( wp_idea_stream_is_ideastream() ) {
		remove_filter( 'excerpt_more', 'thaim_wp_view_article' );
	}
}
add_action( 'wp_idea_stream_template_redirect', 'thaim_allow_ideastream_editor' );

// Specific to Thaim
function thaim_ideastream_headline() {
	if ( ! function_exists( 'wp_idea_stream' ) ) {
		return;
	}

	if ( wp_idea_stream_is_single_idea() ) {
		?>
		<h2><a href="<?php echo esc_url( wp_idea_stream_get_root_url() );?>"><?php echo esc_html( wp_idea_stream_archive_title() ) ;?></a></h2>
		<?php
	} else {
		?>
		<h2><?php the_title(); ?></h2>
		<?php
	}
}

function thaim_the_custom_logo() {
	if ( ! has_custom_logo() ) {
		return;
	}
	?>
	<div id="thaim-logo">
		<?php the_custom_logo(); ?>
	</div>
	<?php
}
