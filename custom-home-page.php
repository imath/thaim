<?php
/**
 * Template Name: Custom Home page
 *
 * Description: Use this page template to remove the sidebar from any page.
 *
 * @package thaim
 * @since 1.0.0
 */

get_header(); ?>

	<!-- Section -->
	<section id="thaim-section" class="twelvecol">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<!-- Article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">

				<?php the_content(); ?>

				<?php if ( is_active_sidebar( 'home-widgets' ) ) :

					dynamic_sidebar( 'home-widgets' );

					else :

					thaim_home_nav();

				endif; ?>

			</div>

			<br class="clear">

			<?php edit_post_link(); ?>

		</article>
		<!-- /Article -->

	<?php endwhile; ?>

	<?php else: ?>

		<?php get_template_part( 'entry', 'none' );?>

	<?php endif; ?>

	</section>
	<!-- /Section -->

<?php get_footer(); ?>
