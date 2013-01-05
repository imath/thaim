<?php 
/**
 * The template for displaying search results.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */

get_header(); ?>
	
	<!-- Section -->
	<section id="thaim-section" class="eightcol">
		
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			
			<?php get_template_part('entry');?>
			
		<?php endwhile; ?>
		
		<!-- Pagination -->
		<div id="pagination">
			<?php thaim_wp_pagination(); ?>
		</div>
		<!-- /Pagination -->

		<?php else: ?>

			<?php get_template_part( 'entry', 'none' );?>

		<?php endif; ?>
	
	</section>
	<!-- /Section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>