<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */
?>

	<article id="post-0">

		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Oops ! nothing there..', 'thaim' ); ?></a></h1>
		</header>

		<div class="entry-content">
			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'thaim' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->

	</article>