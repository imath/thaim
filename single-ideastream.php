<?php
/**
 * IdeaStream single template file. (full width page to make it different from ideastream.php template)
 *
 * This template will not be loaded. It needs version 2.0.1 of WP Idea Stream
 * at the time i'm adding it to this branch of thaim, WP Idea Stream is 2.0.0
 * @see  https://github.com/imath/wp-idea-stream/issues/4
 *
 * @package thaim
 * @since thaim 1.1-beta1
 */

get_header( 'ideastream' ); ?>

	<!-- Section -->
	<section id="thaim-section" class="twelvecol">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<!-- Article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<!-- Post Title -->
			<header class="entry-header">

				<h1 class="entry-title"><?php the_title(); ?></h1>

			</header>


			<div class="entry-content">

				<?php the_content(); ?>

			</div>

			<br class="clear">

			<?php edit_post_link(); ?>

		</article>
		<!-- /Article -->

		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>

	<?php else: ?>

		<?php get_template_part( 'entry', 'none' );?>

	<?php endif; ?>

	</section>
	<!-- /Section -->

<!-- No Sidebar on single idea, just to see it's not the ideastream.php template that is used -->

<?php get_footer( 'ideastream' ); ?>
