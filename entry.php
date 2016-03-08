<?php
/**
 * The template for displaying an entry on the templates != single.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */
?>

	<!-- Article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<!-- Post Title -->
		<header class="entry-header">

			<h3 class="entry-title"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title(); ?></a></h3>

		</header>
		<!-- /Post Title -->

		<div class="twelvecol">

			<?php thaim_post_thumbnail(); ?>

			<?php if ( comments_open() ) : ?>
				<div class="comments-link">
					<span class="dashicons dashicons-admin-comments"></span>
					<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'thaim' ) . '</span>', __( '1 Reply', 'thaim' ), __( '% Replies', 'thaim' ) ); ?>
				</div><!-- .comments-link -->
			<?php endif; // comments_open() ?>

			<!-- Entry-summary -->
			<div class="entry-summary">

				<p><?php thaim_wp_excerpt('thaim_wp_index'); ?></p>

				<p class="readmore"><a class="view-article" href="<?php the_permalink()?>" title="<?php the_title();?>"> <?php _e('View Article', 'thaim');?> &rarr;</a></p>

			</div>
			<!-- .entry-summary -->

		</div>

		<footer class="entry-meta">

			<?php thaim_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'thaim' ), '<span class="edit-link">', '</span>' ); ?>

		</footer>
		<br class="clear">

	</article>
	<!-- /Article -->
