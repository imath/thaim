<?php
/**
 * The template for displaying a page.
 *
 * @package thaim
 * @since  1.0.0
 * @since  2.1.0 Use a "dynamic" comments template.
 */
get_header(); ?>

	<!-- Section -->
	<section id="thaim-section" class="eightcol">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<!-- Article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">

				<?php the_content(); ?>

			</div>

			<br class="clear">

			<?php edit_post_link(); ?>

		</article>
		<!-- /Article -->

		<?php comments_template( thaim_get_comment_template(), true ); // Remove if you don't want comments ?>

	<?php endwhile; ?>

	<?php else: ?>

		<?php get_template_part( 'entry', 'none' );?>

	<?php endif; ?>

	</section>
	<!-- /Section -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
