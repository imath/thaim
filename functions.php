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

if (!isset($content_width))
{
    $content_width = 600;
}


function thaim_setup() {
	
	// Localisation Support
	load_theme_textdomain( 'thaim', get_template_directory() . '/languages' );
	
	if ( function_exists('add_theme_support') )
	{
	    // Add Menu Support
	    add_theme_support( 'menus' );

	    // Enables post and comment RSS feed links to head
	    add_theme_support( 'automatic-feed-links' );
	    
	}
	
	/*
	 * =====================================================================
	 * Thaim options
	 * =====================================================================
	 */
	require_once( get_template_directory() . '/includes/thaim-options.php' );
	
	
	/*
	 * ========================================================================
	 *  Shortcodes
	 * ========================================================================
	 */
	if( 1 == get_option( 'thaim_use_prettify' ) ) {
		require_once( get_template_directory() . '/includes/thaim-code-shortcode.php' );
	}

	/*
	 * ========================================================================
	 *  WordPress.org API
	 * ========================================================================
	 */
	if( thaim_wp_org_link() ) {
		require_once( get_template_directory() . '/includes/thaim-wordpress-org-api.php' );
	}
		
	/*
	 * ========================================================================
	 *  Checking for maitenance mode..
	 * ========================================================================
	 */	
	if( thaim_is_maintenance_mode() ) {
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
	register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu'  => __('Header Menu', 'thaim'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'thaim'), // Sidebar Navigation
        'extra-menu'   => __('Extra Menu', 'thaim') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

add_action( 'after_setup_theme', 'thaim_setup' );



/*
 * ========================================================================
 * Widget in dynamic sidbar areas
 * ========================================================================
 */

function thaim_widgets_init()
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'thaim'),
        'description' => __('Top sidebar area', 'thaim'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'thaim'),
        'description' => __('Bottom sidebar area', 'thaim'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));

	// Define Footer Widget areas
    register_sidebar(array(
        'name' => __('Footer Area 1', 'thaim'),
        'description' => __('Left footer area', 'thaim'),
        'id' => 'footer-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));

	register_sidebar(array(
        'name' => __('Footer Area 2', 'thaim'),
        'description' => __('Middle footer area', 'thaim'),
        'id' => 'footer-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));

	register_sidebar(array(
        'name' => __('Footer Area 3', 'thaim'),
        'description' => __('Right footer area', 'thaim'),
        'id' => 'footer-area-3',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}

add_action( 'widgets_init', 'thaim_widgets_init' );



/*
 * ========================================================================
 * Functions
 * ========================================================================
 */

function thaim_wp_org_link() {
	if ( is_array( $wporg = get_option('thaim_link_wordpress_org') ) && !empty( $wporg['username'] ) )
		return true;
	else
		return false;
}

function thaim_github_has_repos() {
	$git_option = get_option('thaim_list_github_repos');
	
	if( !empty( $git_option ) && is_array( $git_repos = get_option('thaim_github_repos') ) && count( $git_repos ) >= 1 )
		return true;
		
	else
		return false;
}

function thaim_is_maintenance_mode() {
	$option = get_option( 'thaim_maintenance_mode', 0 );
	
	if( !empty( $option ) )
		return true;
		
	else
		return false;
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
function thaim_nav()
{
	wp_nav_menu(
	array(
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
		)
	);
}


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
function thaim_wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using thaim_wp_excerpt('thaim_wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using thaim_wp_excerpt('thaim_wp_custom_post');
function thaim_wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function thaim_wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}



/*
 * ========================================================================
 * Headline and slider
 * ========================================================================
 */

function thaim_headline() {
	//if home slider....
	if( is_front_page() ) {
		
		thaim_slider_handle();
		
	} else {
		
		if ( is_search() ):
			global $wp_query;
		?>
			<h1><?php echo sprintf( __( '%s Search Results for ', 'thaim' ), $wp_query->found_posts ); echo get_search_query(); ?></h1>
		
		<?php elseif ( is_category() ):
		
			thaim_headline_category();
		
		elseif ( is_tag() ):
		
			thaim_headline_tag();
		
		elseif ( is_single() ):
		
			thaim_headline_single();

		else : ?>

			<h1><?php thaim_headline_h1(); ?></h1>

		<?php
		endif;
		
		do_action( 'thaim_headline' );

	}
	
}

function thaim_headline_h1() {
	echo thaim_headline_get_h1();
}
	
	function thaim_headline_get_h1() {
		$headline = wp_title( false, false );

		return apply_filters( 'thaim_headline_get_h1', $headline );
	}

function thaim_headline_tag() {
	$tag = get_tags( array( 'slug' => wp_title( false, false ) ) );
	
	thaim_headline_html_for_cat_tags( $tag[0]->term_id, $tag[0]->name, $tag[0]->description );
}

function thaim_headline_category(){
	
	$category = get_the_category();
	
	thaim_headline_html_for_cat_tags( $category[0]->term_id, $category[0]->name, $category[0]->description, 'cat' );
}

function thaim_headline_html_for_cat_tags( $term_id, $term_name, $term_desc, $type = 'tag' ) {
	
	if( $type == 'tag' )
		$default_icon = '&#xe012;';
	else
		$default_icon = '&#xe03f;';
	
	$icon = get_tax_meta( $term_id, 'thaim_tax_icon' );
	
	$icon = !empty( $icon ) ? $icon : $default_icon ;
	
	$image_header = get_tax_meta( $term_id, 'thaim_tax_image' );
	?>
	<div class="row thaim-in-headline">
		
		<?php if( is_array( $image_header ) && !empty( $image_header['src'] ) ):?>
			<div class="sevencol">
				<img src="<?php echo $image_header['src'];?>" alt="Illustration" class="thaim-image">
			</div>
			<div class="fivecol last">
		<?php else:?>
			<div class="twelvecol">
		<?php endif;?>
				<h1>
					<span aria-hidden="true" data-icon="<?php echo $icon ;?>"></span>
					<?php echo esc_html( $term_name );?>
				</h1>
				<?php if( !empty( $term_desc ) ):?>
					<p><?php echo esc_html( $term_desc );?></p>
				<?php endif;?>
			</div>
	</div>
	<?php
}

function thaim_headline_single() {
	?>
	<div class="row thaim-in-headline">
	<?php if( get_post_meta(get_the_ID(), 'imageslider', true ) ):?>
		<div class="threecol">
			<img src="<?php echo get_post_meta(get_the_ID(), 'imageslider', true );?>" alt="Illustration" class="thaim-image">
		</div>
		<div class="ninecol last">
	
			<p class="thaim-post-desc"><?php the_excerpt()?></p>
			
		</div>
	<?php else:?>
		<div class="twelvecol">
	
			<p class="thaim-post-desc"><?php the_excerpt()?></p>
				
		</div>
	<?php endif;?>
	</div>
	<?php
}

function thaim_cycle() {

	if( is_front_page() ) {
		wp_enqueue_style('thaim-cycle-style', get_template_directory_uri() .'/css/slider.css' );
		wp_enqueue_script('thaim-cycle-js', get_template_directory_uri() .'/js/jquery.cycle.all.js', array('jquery') );
		
		add_action( 'wp_footer', 'thaim_cycle_settings');
	}
		
}

add_action('wp_enqueue_scripts', 'thaim_cycle' );

function thaim_cycle_settings() {
	?>
	<script>
		jQuery(document).ready(function($) { 
			$(".thaim-hero-slide-container").cycle({
				fx:		'scrollHorz',
			    pager:  '.thaim-slide-nav',
			    timeout: 8000
			}); 
		});
	</script>
	<?php
}

add_action( 'thaim_slider', 'thaim_slider_handle' );

function thaim_slider_handle() {
	query_posts('meta_key=inslider&meta_value=1');
	
	if ( have_posts() ) :
	?>
	<div class="thaim-hero-slide-container">
		
		<?php while ( have_posts() ) : the_post(); ?>
		
		<div class="thaim-hero-slide">
			
			<?php if( get_post_meta(get_the_ID(), 'imageslider', true ) ):?>
				<div class="fivecol">
					<img src="<?php echo get_post_meta(get_the_ID(), 'imageslider', true );?>" alt="Image slider">
				</div>
				<div class="sevencol last">
					<div class="thaim-slide-article">
				
						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>
			
						<p class="desc"><?php the_excerpt()?></p>
						
						<p class="readmore"><a class="view-article" href="<?php the_permalink()?>" title="<?php the_title();?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>
					</div>
				</div>
			<?php else:?>
				<div class="twelvecol">
					<div class="thaim-slide-article">
						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>
			
						<p class="desc"><?php the_excerpt()?></p>
						
						<p class="readmore"><a class="view-article" href="<?php the_permalink()?>" title="<?php the_title();?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>
					</div>
				</div>
			<?php endif;?>
		</div>
		
		<?php endwhile; ?>
		
	</div>
	<div class="thaim-slide-nav">
		
	</div>
	
	<?php else: ?>
		
		<h1><?php _e('Home', 'thaim');?></h1>
	
	<?php endif;
	
	wp_reset_query();
}

function thaim_github_display_repos() {
	
	$git_repos = get_option('thaim_github_repos');
	
	//double check !
	if( is_array( $git_repos ) && count( $git_repos ) >=1 ):?>
	<table>
		<thead>
			<tr><th><?php _e('Name of the repo', 'thaim');?></th><th><?php _e('Infos about the repo', 'thaim');?></th><th><?php _e('Repo link', 'thaim');?></th></tr>
		</thead>
		<tbody>
	
		<?php foreach ( $git_repos as $repo ): ?>
			<tr>
				<td><?php echo $repo['name'];?></td>
				<td>
					<p class="thaim-short-desc"><?php echo $repo['description'];?></p>
				</td>
				<td>
					<p class="info-wp">
						<a href="<?php echo $repo['url'];?>" title="<?php _e('View on github', 'thaim');?>"><?php _e('View on github', 'thaim');?></a>
					</p>
				</td>
			</tr>

		<?php endforeach; ?>
		
		</tbody>
	</table>
		
	<?php else:?>
		<p><?php _e( 'Oops, seems no github repos were synced...', 'thaim' );?></p>
	<?php endif;
}


/*
 * ========================================================================
 * Actions + Filters
 * ========================================================================
 */

// Theme Stylesheets using Enqueue
function thaim_styles()
{
    wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize'); // Enqueue it!
    
    wp_register_style('thaim', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('thaim'); // Enqueue it!
}

add_action( 'wp_enqueue_scripts', 'thaim_styles' ); // Add Theme Stylesheet



// Load Custom Theme Scripts using Enqueue
function thaim_scripts()
{
    if (!is_admin()) {
        /*
		thaim will use WordPress jQuery..
		wp_deregister_script('jquery'); // Deregister WordPress jQuery
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', array(), '1.8.3'); // Load Google CDN jQuery*/
        wp_enqueue_script('jquery'); // Enqueue it!

        wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array('jquery'), '2.6.2'); // Modernizr with version Number at the end
        wp_enqueue_script('modernizr'); // Enqueue it!

        wp_register_script('thaimscripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0'); // Thaim Blank script with version number
        wp_enqueue_script('thaimscripts'); // Enqueue it!
    }
}

add_action( 'init', 'thaim_scripts' ); // Add Custom Scripts



// prettify the snippets if the post meta is set so.
function thaim_load_prettify() {
	
	global $post;

	if ( empty( $post->ID ) )
		return;
	
	if( get_post_meta( $post->ID, 'prettifyed', true ) == 1 ) {
		
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
function add_google_analytics()
{
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


// jQuery Fallbacks load in the footer
function add_jquery_fallback()
{
    $jqueryfallback = "<!-- Protocol Relative jQuery fall back if Google CDN offline -->";
    $jqueryfallback .= "<script>";
    $jqueryfallback .= "window.jQuery || document.write('<script src=\"" . get_template_directory_uri() . "/js/jquery-1.8.3.min.js\"><\/script>')";
    $jqueryfallback .= "</script>";
    echo $jqueryfallback;
}

//add_action('wp_footer', 'add_jquery_fallback'); // jQuery fallbacks loaded through footer


// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

add_action( 'get_header', 'enable_threaded_comments' ); // Enable Threaded Comments


// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

add_action( 'widgets_init', 'my_remove_recent_comments_style' ); // Remove inline Recent Comment Styles from wp_head()

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function thaim_wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

add_action( 'init', 'thaim_wp_pagination' ); // Add our Thaim Pagination


/**
* this is specific to me you can comment or customize with your twitter account
* and your paypal link
*/
function thaim_single_reader_add_actions() {
	?>
	&nbsp;&nbsp;
	<span aria-hidden="true" data-icon="&#xe0e6;"></span>
	<span class="twitter-share">
		<a href="https://twitter.com/intent/tweet?original_referer=<?php echo urlencode( get_permalink());?>&amp;source=tweetbutton&amp;text=<?php echo urlencode( get_the_title());?>&amp;url=<?php echo urlencode( get_permalink());?>&amp;via=imath" class="share-on-twitter single" title="<?php _e('Share', 'thaim')?>" target="_blank"><?php _e('Share', 'thaim')?></a>
	</span>
	&nbsp;&nbsp;
	<span aria-hidden="true" data-icon="&#xe127;"></span>
	<span class="paypal-support">
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=2QSLY676C6HKE" title="buy me a coffee ;)" target="_blank"><?php _e('Support', 'thaim');?></a>
	</span>
	<?php
}

add_action( 'thaim_single_reader_actions', 'thaim_single_reader_add_actions' );



function thaim_add_feed_link() {
	
	$feed_link = get_feed_link();
	$title = __( 'blog', 'thaim' );
	
	if( is_category() || is_tag() ) {
		$term = get_queried_object();

		$feed_link = is_tag() ? get_term_feed_link( $term->term_id, 'post_tag' ) : get_term_feed_link( $term->term_id, 'category' );
		$title = esc_html( $term->name );
	} elseif( is_single() ) {
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


/**
* As we can only do a few requests per hour while not oauthed
* let's do only one and cache the results
*/
function thaim_github_do_job() {
	$git_user = get_option('thaim_list_github_repos');
	
	if ( !empty( $git_user ) ) {
		
		require( get_template_directory() . '/includes/thaim-github-api.php' );

		$github_repos = new Thaim_Github_API( $git_user );
		
	}
	
}

add_action('thaim_github_cron_job', 'thaim_github_do_job');


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
function add_slug_to_body_class($classes)
{
    global $post;
    if ( is_home() ) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif ( is_page() ) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif ( is_singular() ) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

add_filter( 'body_class', 'add_slug_to_body_class' ); // Add slug to body class (Starkers build)


add_filter( 'widget_text', 'do_shortcode' ); // Allow shortcodes in Dynamic Sidebar
add_filter( 'widget_text', 'shortcode_unautop' ); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter( 'the_excerpt', 'shortcode_unautop' ); // Remove auto <p> tags in Excerpt (Manual Excerpts only)

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function thaim_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

add_filter( 'wp_nav_menu_args', 'thaim_wp_nav_menu_args' ); // Remove surrounding <div> from WP Navigation


// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

add_filter( 'the_category', 'remove_category_rel_from_category_list' ); // Remove invalid rel attribute


// thaim doesn't need a read more link as we only use excerpt !
function thaim_wp_view_article($more)
{
    return '';
}

add_filter( 'excerpt_more', 'thaim_wp_view_article' ); // Well i dont need as i always use the excerpt field..


// Remove 'text/css' from our enqueued stylesheet
function thaim_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

add_filter( 'style_loader_tag', 'thaim_style_remove' ); // Remove 'text/css' from enqueued stylesheet


// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 ); // Remove width and height dynamic attributes to thumbnails
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 ); // Remove width and height dynamic attributes to post images

// Disable WYSIWYG editor
function thaim_is_for_coder( $rich_edit ) {
	return false;
}

add_filter( 'user_can_richedit', 'thaim_is_for_coder' ); // thaim is for coder, not wysiwyger !

//limit the number of tags in tag cloud
function thaim_tag_cloud_args( $args ) {
	$args['number'] = 20;
	
	return $args;
}

add_filter( 'widget_tag_cloud_args', 'thaim_tag_cloud_args' );

// Remove Filters
remove_filter( 'the_excerpt', 'wpautop' ); // Remove <p> tags from Excerpt altogether


/*
 * ========================================================================
 *  Thaim tax meta
 *  Many thanks to Raz Ohad for https://github.com/bainternet/Tax-Meta-Class
 * ========================================================================
 */
require_once( get_template_directory() . '/includes/thaim-tax-meta.php' );