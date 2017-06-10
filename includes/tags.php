<?php
/**
 * Thaim tags
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Output the blogname
 *
 * @since 1.0.0
 */
function thaim_blogname() {
	echo thaim_get_blogname();
}
	/**
	 * Get the blogname
	 *
	 * @since 2.0.0
	 */
	function thaim_get_blogname() {
		$blog_name      = esc_html( get_bloginfo( 'name' ) );
		$blog_name_left = substr( $blog_name, 0, 5 );
		$blog_name      = str_replace( $blog_name_left, '<span>' . $blog_name_left . '</span>', $blog_name );

		return apply_filters( 'thaim_get_blogname', $blog_name );
	}

/**
 * Custom logo
 *
 * @since 2.0.0
 */
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

/**
 * Thaim Main navigation
 *
 *  @since 1.0.0
 */
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
 * @since 2.0.0
 */
function thaim_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	$thaim = thaim();

	if ( is_singular() ) :
		// Add a temporary filter intercept the attachment object
		add_filter( 'wp_get_attachment_image_attributes', 'thaim_get_thumbnail_credit', 10, 2 );
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>

		<?php if ( ! empty( $thaim->thumbnail_overlay ) ) : ?>
			<small class="post-thumbnail-overlay">
				<?php echo apply_filters( 'the_content', $thaim->thumbnail_overlay ) ; ?>
			</small>
		<?php endif ; ?>
	</div><!-- .post-thumbnail -->

	<?php
		// Remove the filter we used to intercept the attachment object
		remove_filter( 'wp_get_attachment_image_attributes', 'thaim_get_thumbnail_credit', 10, 2 );

	else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'thaim_excerpt' ) ) :
/**
 * Thaim Excerpt
 *
 * @since 2.0.0
 */
function thaim_excerpt( $class = '', $length = 'thaim_excerpt_more' ) {
	$thaim = thaim();

	// Remove the wpautop filter for our custom excerpt
	remove_filter( 'the_excerpt', 'wpautop' );

	if ( ! empty( $class ) ) {
		$class = ' class="' . sanitize_html_class( $class ) . '"';
	}

	if ( has_excerpt() ) : ?>
		<p<?php echo $class; ?>><?php the_excerpt(); ?></p>

	<?php else :
		add_filter( 'excerpt_more', 'thaim_excerpt_more' );

		if ( ! empty( $length ) ) {
			add_filter( 'excerpt_length', 'thaim_excerpt_length' );
		}
	?>

		<p<?php echo $class; ?>><?php the_excerpt();?></p>

	<?php
		remove_filter( 'excerpt_more', 'thaim_excerpt_more' );

		if ( ! empty( $length ) ) {
			remove_filter( 'excerpt_length', 'thaim_excerpt_length' );
		}

	endif;

	if ( has_excerpt() || empty( $thaim->excerpt_was_trimed ) ) : ?>
		<p class="readmore">
			<a class="view-article" href="<?php the_permalink()?>" title="<?php the_title();?>"><?php esc_html_e( 'View Article', 'thaim' );?> &rarr;</a>
		</p>

	<?php endif;
	$thaim->excerpt_was_trimed = false;

	// Restore the wpautop filter
	add_filter( 'the_excerpt', 'wpautop' );
}
endif;

if ( ! function_exists( 'thaim_single_post_nav' ) ) :
/**
 * adds a navigation to adjacent post and a scroll top!
 *
 * @since 1.0.0
 */
function thaim_single_post_nav() {
	?>
	<nav class="nav-single">
		<div class="nav-top">
			<a href="#top" class="backtotop single" title="<?php _e('Jump to the top of the page', 'thaim');?>"><span class="dashicons dashicons-arrow-up"></span> <?php esc_html_e( 'Back to top', 'thaim' );?></a>
		</div>
		<br class="clear"/>
		<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav"><span class="dashicons dashicons-arrow-left"></span></span> %title' ); ?></span>
		<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav"><span class="dashicons dashicons-arrow-right"></span></span>' ); ?></span>
		<br class="clear"/>
	</nav><!-- .nav-single -->
	<?php
}
endif;

if ( ! function_exists( 'thaim_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Credit : Twenty Twelve 1.0
 *
 *  @since 1.0.0
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
 * Entry metas
 *
 * Credit : Twenty Twelve 1.0
 *
 *  @since 1.0.0
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

/**
 * Output the Current Screen title
 *
 * @since 2.0.0
 */
function thaim_headline_h2() {
	echo thaim_headline_get_h2();
}

	/**
	 * Get the Current Screen title
	 *
	 * @since 2.0.0
	 */
	function thaim_headline_get_h2() {
		add_filter( 'document_title_parts', 'thaim_title_parts', 10, 1 );

		$headline = wp_get_document_title();

		if ( $headline === get_bloginfo( 'sitename' ) ) {
			$headline = '';
		}

		remove_filter( 'document_title_parts', 'thaim_title_parts', 10, 1 );

		return apply_filters( 'thaim_headline_get_h2', $headline );
	}

/**
 * Get the Screen title for a single post
 *
 * @since 1.0.0
 */
function thaim_headline_single() {
	$post_title = get_the_title();

	if ( empty( $post_title ) ) {
		$post_title = thaim_headline_get_h2();
	}

	printf( '<h2>%s</h2>', $post_title );
}

/**
 * Get the Screen title for a term
 *
 * @since 1.0.0
 */
function thaim_headline_term() {
	$term = get_queried_object();
	?>
	<h2><?php echo thaim_get_term_dashicon( $term->term_id ); ?> <?php echo esc_html( $term->name );?></h2>
	<?php
}

/**
 * Get the term descrition
 *
 * @since 2.0.0
 */
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
			<?php echo wp_kses( $term->description, array( 'a' => array( 'href' => true ) ) ) ;?>
		</blockquote>
	</div>
	<?php
}

/**
 * Thaim Headline
 *
 * @since 1.0.0
 */
function thaim_headline() {
	// if On home and There are stickies, display the Thaim Slider
	if ( is_front_page() && thaim_has_stickies() && 1 >= get_query_var( 'paged' ) ) {

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

/**
 * Thaim Slider
 *
 * @since 1.0.0
 */
function thaim_slider_handle() {
	if ( ! thaim_has_stickies() ) {
		return;
	}

	query_posts( array(
		'post__in' => thaim()->stickies
	) );

	if ( have_posts() ) : ?>

	<div class="thaim-hero-slide-container">

		<?php while ( have_posts() ) : the_post(); ?>

		<div class="thaim-hero-slide">

			<?php if ( has_post_thumbnail() ) :?>

				<div class="post-thumbnail fivecol">
					<?php the_post_thumbnail( 'medium' ); ?>
					<div class="cycle-overlay">
						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>
					</div>
				</div>
				<div class="sevencol last">
					<div class="thaim-slide-article">

						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>

						<?php thaim_excerpt('desc', false ); ?>

					</div>
				</div>

			<?php else: ?>

				<div class="twelvecol">
					<div class="thaim-slide-article">
						<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title()?></a></h2>

						<?php thaim_excerpt( 'desc', false ); ?>

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

/**
 * Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links
 *
 * @since 1.0.0
 */
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

/**
* this is specific to me you can comment or customize with your twitter account
* and your paypal link
*
* @since 1.0.0
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

/**
* Prepend rss links to the sidebar
*
* @since 1.0.0
*/
function thaim_add_feed_link() {

	$feed_link = get_feed_link();
	$title = esc_html__( 'blog', 'thaim' );

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
add_action( 'thaim_before_sidebar_widgets', 'thaim_add_feed_link' );

/**
 * Add Twitter cards to single posts/pages
 *
 * @since 2.0.0
 */
function thaim_twitter_card() {
	if ( ! is_singular() ) {
		return;
	}

	$post = get_post();

	if ( ! empty( $post->post_excerpt ) ) {
		$description = strip_shortcodes( $post->post_excerpt );
	} else {
		$description = strip_shortcodes( $post->post_content );
	}

	$twitter = array(
		'twitter:card'        => 'summary_large_image',
		'twitter:site'        => '@imath',
		'twitter:title'       => wp_strip_all_tags( apply_filters( 'the_title', $post->post_title, $post->ID ) ),
		'twitter:description' => wp_strip_all_tags( apply_filters( 'the_excerpt', wp_trim_words( $description, 40, '...' ) ) ),
	);

	if ( empty( $twitter['twitter:title'] ) || empty( $twitter['twitter:description'] ) ) {
		return;
	}

	$large_image = get_the_post_thumbnail_url( $post );

	if ( empty( $large_image ) ) {
		$twitter['twitter:card'] = 'summary';

		$small_image = get_site_icon_url( 120 );

		if ( ! empty( $small_image ) ) {
			$twitter['twitter:image'] = esc_url_raw( $small_image );
		}
	} else {
		$twitter['twitter:image'] = esc_url_raw( $large_image );
	}

	foreach ( $twitter as $meta_name => $meta_content ) {
		printf( '<meta name="%1$s" content="%2$s">' . "\n", esc_attr( $meta_name ), esc_attr( $meta_content ) );
	}
}
add_action( 'wp_head', 'thaim_twitter_card', 20 );

/**
 * Check if a question was just sent and build the feedback output for the user.
 *
 * @since  2.1.0
 *
 * @return bool True if a question was successfully saved. False otherwise.
 */
function thaim_question_sent() {
	if ( empty( $_GET['message'] ) ) {
		return false;
	}

	$thaim = thaim();

	$id       = (int) $_GET['message'];
	$question = get_comment( $id );

	if ( ! empty( $question->comment_author_email ) && isset( $_COOKIE['comment_author_email_' . COOKIEHASH] ) && $question->comment_author_email === $_COOKIE['comment_author_email_' . COOKIEHASH] ) {
		$message         = __( 'Thanks a lot for your feedback. Your message was sent successfully. We will reply to it asap.', 'thaim' );
		$message_content = $question->comment_content;

		if ( 'flag' === $question->comment_type ) {
			$plugin_slug     = get_comment_meta( $question->comment_ID, 'galerie_plugin_slug', true );
			$message         = __( 'Thanks a lot for your report. Your message was sent successfully.', 'thaim' );
			$message_content = sprintf( __( 'Flagged plugin: <strong>%s</strong>.', 'thaim' ), $plugin_slug );
		}

		$thaim->message_raw = array(
			'message-feedback' => sprintf( '<p class="message-info success">%1$s</p>',
				$message
			),
			'message-content'  => $message_content,
			'message-email'    => $question->comment_author_email,
		);
	} else {
		$message = __( 'Ouch. There was a problem sending the message. Please try again.', 'thaim' );
		if ( isset( $question->comment_type ) && 'flag' === $question->comment_type ) {
			$message = __( 'Ouch. There was a problem reporting the plugin. Please try again.', 'thaim' );
		}

		$thaim->message_raw = array(
			'message-feedback' => sprintf( '<p class="message-info error">%s</p>',
				$message
			),
			'message-content' => '',
			'message-email'   => '',
		);
	}

	return true;
}

/**
 * Display The Question's feedback.
 *
 * @since  2.1.0
 *
 * @return string HTML Output.
 */
function thaim_question_content() {
	echo thaim_question_get_content();
}

	/**
	 * Get the Question's feedback.
	 *
	 * @since  2.1.0
	 *
	 * @return string The question's feedback content.
	 */
	function thaim_question_get_content() {
		$thaim  = thaim();
		$output = '';

		if ( empty( $thaim->message_raw ) ) {
			return $output;
		}


		foreach ( $thaim->message_raw as $class => $html )  {
			if ( empty( $html ) ) {
				continue;
			}

			$part = $html;

			if ( 'message-email' === $class ) {
				$part = sprintf( '<strong>%1$s</strong> %2$s', __( 'Email used:', 'thaim' ), $html );
			}

			$output .= '<div class="' . sanitize_html_class( $class ) . '">' . $part . '</div>' . "\n";
		}

		/**
		 * Sanitize the output.
		 *
		 * @since  2.1.0
		 *
		 * @param string $output The question's feedback content.
		 */
		$output = apply_filters( 'comment_text', $output );

		/**
		 * Filter here to edit the output.
		 *
		 * @since  2.1.0
		 *
		 * @param string $output The question's feedback content.
		 */
		return apply_filters( 'thaim_question_get_content', $output );
	}

/**
 * Prints the necessary markup for the embed download button.
 *
 * @since 2.2.0
 */
function thaim_print_download_button() {
	$link_data = thaim_github_release( array( 'name' => 'wp-idea-stream', 'link_only' => true ) );

	if ( empty( $link_data['url'] ) ) {
		return;
	}

	$link = add_query_arg( 'redirectto', $link_data['url'], get_permalink() );
	?>
	<div class="wp-embed-download">
		<a href="<?php echo esc_url( $link ); ?>" target="_top">
			<span class="dashicons dashicons-download"></span>

			<?php if ( ! empty( $link_data['count'] ) ) :
			printf(
				_n(
					'%s <span class="screen-reader-text">Download</span>',
					'%s <span class="screen-reader-text">Downloads</span>',
					$link_data['count'],
					'thaim'
				),
				number_format_i18n( $link_data['count'] )
			);
		  endif; ?>

		</a>
	</div>
	<?php
}

function thaim_print_translate_button() {
	$french  = wp_staticize_emoji( '🇫🇷' );
	$english = wp_staticize_emoji( '🇬🇧' );

	if ( is_locale_switched() ) {
		$to         = $french;
		$to_class   = 'fr_FR';
		$from       = $english;
		$from_class = 'en_US';
	} else {
		$from       = $french;
		$from_class = 'fr_FR';
		$to         = $english;
		$to_class   = 'en_US';
	}
	?>
	<div class="wp-embed-translate">
		<a href="#" id="thaim-translate">
			<span class="thaim-translate-emoji to" data-locale="<?php echo esc_attr( $to_class ); ?>"><?php echo $to; ?></span>
			<span class="thaim-translate-emoji from hidden" data-locale="<?php echo esc_attr( $from_class ); ?>"><?php echo $from; ?></span>
		</a>
	</div>
	<?php
}

function thaim_embed_enqueue_script() {
	$post = get_post();

	if ( empty( $post->ID ) || thaim()->galerie_page_id !== (int) $post->ID ) {
		return;
	}

	remove_action( 'embed_content_meta', 'print_embed_comments_button' );
	add_action( 'embed_content_meta', 'thaim_print_translate_button', 1 );
	add_action( 'embed_content_meta', 'thaim_print_download_button',  3 );

	wp_enqueue_style ( 'thaim-embed', get_template_directory_uri() . '/css/embed.css', thaim()->version, 'all' );
	wp_enqueue_script( 'thaim-embed', get_template_directory_uri() . '/js/embed.js', array(), thaim()->version, true );

	$fr_fr = apply_filters( 'the_excerpt_embed', wp_trim_excerpt( $post->post_content ) );
	$en_us = apply_filters( 'the_excerpt_embed', wp_trim_excerpt( $post->post_excerpt ) );
	$link_fr = str_replace( 'en-us/', '', get_permalink( $post ) );
	$link_us = trailingslashit( $link_fr ) . 'en-us/';

	$locale = get_locale();
	$switch = array(
		'fr_FR' => 'en_US',
		'en_US' => 'fr_FR',
	);

	switch_to_locale( $switch[ $locale ] );

	$ui_strings = array(
		'wp-embed-share-dialog-open'           => esc_attr__( 'Open sharing dialog', 'default' ),
		'wp-embed-share-dialog'                => esc_attr__( 'Sharing options', 'default' ),
		'wp-embed-share-tab-button-wordpress'  => esc_html__( 'WordPress Embed', 'default' ),
		'wp-embed-share-tab-button-html'       => esc_html__( 'HTML Embed', 'default' ),
		'wp-embed-share-description-wordpress' => esc_html__( 'Copy and paste this URL into your WordPress site to embed', 'default' ),
		'wp-embed-share-description-html'      => esc_html__( 'Copy and paste this code into your site to embed', 'default' ),
		'wp-embed-share-dialog-close'          => esc_html__( 'Close sharing dialog', 'default' ),
	);

	restore_previous_locale();

	$l10nthaimembed = array(
		'link' => array(
			'fr_FR' => esc_url_raw( $link_fr ),
			'en_US' => esc_url_raw( $link_us ),
		),
		'content'   => array(
			'fr_FR' => $fr_fr,
			'en_US' => $en_us,
		),
		'uiStrings' => array(
			$switch[ $locale ] => $ui_strings,
		),
		'currentLocale' => $locale,
	);

	if ( 'en_US' !== $locale ) {
		$GLOBALS['post']->post_excerpt = '';
	}
	wp_localize_script( 'thaim-embed', 'l10nThaimEmbed', $l10nthaimembed );
}
add_action( 'enqueue_embed_scripts', 'thaim_embed_enqueue_script' );
