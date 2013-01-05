<?php
/**
 * Template Name: Plugins template
 *
 * Description: use this template to link WordPress.org API and/or github one
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */

get_header(); ?>
	
	<!-- Section -->
	<section id="thaim-section" class="eightcol">
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<!-- Article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
			<div class="entry-content">
			
				<?php the_content(); ?>
				
			</div>
			
			<br class="clear">
			
			<?php edit_post_link(); ?>
			
		</article>
		<!-- /Article -->
		
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<?php get_template_part( 'entry', 'none' );?>
	
	<?php endif; ?>
	
	<?php if( thaim_wp_org_link() ):?>
	
		<article id="wp-org-plugins">
			
			<div class="entry-content">
				
				<h2 class="wordpress"><span aria-hidden="true" data-icon="&#xe104;"></span> <?php _e( 'My plugins in WordPress Repository', 'thaim');?></h2>
				
				<?php if( thaim_has_plugins() ):?>
					
					<table>
						<thead>
							<tr><th colspan="2"><?php _e('Name of the plugin', 'thaim');?></th><th><?php _e('Infos about the plugin', 'thaim');?></th><th><?php _e('Plugin links', 'thaim');?></th></tr>
						</thead>
						<tbody>
					
						<?php while ( thaim_plugins() ) : thaim_the_plugin(); ?>
							<tr>
								<td><?php thaim_plugin_buddypress_tag();?></td>
								<td><?php thaim_plugin_name();?></td>
								<td class="thaim-plugins-info">
									<?php thaim_plugin_description();?>
								</td>
								<td>
									<p class="download-wp">
										<?php thaim_plugin_download_link();?>
									</p>
									<p class="info-wp">
										<?php thaim_plugin_info_link();?>
									</p>
									<p class="thaim-plugin-tag">
										<?php thaim_plugin_tag_link();?>
									</p>
								</td>
							</tr>

						<?php endwhile; ?>
						
						</tbody>
					</table>
						
				<?php else:?>
					<p><?php _e( 'Oops, seems WordPress.org is not responding...', 'thaim' );?></p>
				<?php endif;?>
				
			</div>
			
		</article>
		
	<?php endif;?>
	
	<?php if( thaim_github_has_repos() ):?>
	
		<article id="github-repos">
		
			<div class="entry-content">
			
				<h2 class="github"><span aria-hidden="true" data-icon="&#xe10a;"></span> <?php _e( 'My github repositories', 'thaim');?></h2>
				
				<?php thaim_github_display_repos();?>
				
			</div>
			
		</article>
		
	<?php endif;?>
	
	</section>
	<!-- /Section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>