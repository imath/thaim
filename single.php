<?php  
/**
 * The template for a post.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */

get_header(); ?>
	
	<!-- Section -->
	<section id="thaim-section" class="eightcol">
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<?php get_template_part( 'entry', 'single' );?>
		
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<?php get_template_part( 'entry', 'none' );?>
	
	<?php endif; ?>
	
	</section>
	<!-- /Section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>