<?php
/**
 * The template for displaying an entry in the single template.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */
?>

	<!-- Article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<!-- Post Title -->
		<header class="entry-header">

			<h1 class="entry-title"><?php the_title(); ?></h1>

			<?php if ( comments_open() ) : ?>
				<div class="comments-link">
					<span aria-hidden="true" data-icon="&#xe039;"></span> 
					<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'thaim' ) . '</span>', __( '1 Reply', 'thaim' ), __( '% Replies', 'thaim' ) ); ?>
					
					<?php do_action( 'thaim_single_reader_actions' );?>
				</div><!-- .comments-link -->
			<?php endif; // comments_open() ?>

		</header>
		<!-- /Post Title -->

		<div class="entry-content">

			<?php the_content(); // Dynamic Content ?>

		</div>

		<footer class="entry-meta">
			<?php thaim_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'thaim' ), '<span class="edit-link">', '</span>' ); ?>
			<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
				<div class="author-info">
					<div class="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'thaim_author_bio_avatar_size', 68 ) ); ?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2><?php printf( __( 'About %s', 'thaim' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<div class="author-link">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'thaim' ), get_the_author() ); ?>
							</a>
						</div><!-- .author-link	-->
					</div><!-- .author-description -->
				</div><!-- .author-info -->
			<?php endif; ?>
		</footer><!-- .entry-meta -->

	</article>
	<!-- /Article -->
	
	<?php thaim_single_post_nav(); ?>

	<?php comments_template( '', true ); ?>