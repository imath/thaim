<?php 
/**
 * IdeaStream main template file.
 *
 * @package thaim
 * @since thaim 1.1-beta1
 */

get_header( 'ideastream' ); ?>
	
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
		
		<?php comments_template( '', true ); // Remove if you don't want comments ?>
		
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<?php get_template_part( 'entry', 'none' );?>
	
	<?php endif; ?>
	
	</section>
	<!-- /Section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>