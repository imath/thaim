<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Thaim_WP_Org_Api_Template {
	var $current_plugin = -1;
	var $plugin_count;
	var $plugins;
	var $plugin;

	var $in_the_loop;

	var $pag_page;
	var $pag_num;
	var $pag_links;
	var $total_plugin_count;

	function __construct( $args = '', $action = 'query_plugins', $page_arg = 'spage' ) {
		
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

		/*$this->pag_page = !empty( $_REQUEST[$page_arg] ) ? intval( $_REQUEST[$page_arg] ) : (int) $page_number;
		$this->pag_num  = !empty( $_REQUEST['num'] )   ? intval( $_REQUEST['num'] )   : (int) $per_page;*/
		
		$defaults = array( 'page' => 1, 
					       'per_page' => 20, 
					       'author' => 'imath', 
					       'fields' => array( 'description' => true, 
										  'sections' => true, 
										  'tested' => true,
										  'requires' => true, 
										  'rating' => true, 
										  'downloaded' => true, 
										  'downloadlink' => true, 
										  'last_updated' => true, 
										  'homepage' => true, 
										  'tags' => true ) 
						);	
						
		$r = wp_parse_args( $args, $defaults );
		
		$this->plugins = plugins_api($action, $r );
		
		$this->plugins = $this->plugins->plugins;
		
		$this->plugin_count = $this->total_plugin_count = count( $this->plugins );
		
		if ( (int) $this->total_plugin_count && (int) $this->pag_num ) {
			$this->pag_links = paginate_links( array(
				'base'      => add_query_arg( $page_arg, '%#%' ),
				'format'    => '',
				'total'     => ceil( (int) $this->total_plugin_count / (int) $this->pag_num ),
				'current'   => (int) $this->pag_page,
				'prev_text' => _x( '&larr;', 'Plugins pagination previous text', 'thaim' ),
				'next_text' => _x( '&rarr;', 'Plugins pagination next text', 'thaim' ),
				'mid_size'   => 1
			) );
		}
		
	}

	function has_plugins() {
		if ( $this->plugin_count )
			return true;

		return false;
	}

	function next_plugin() {
		$this->current_plugin++;
		$this->plugin = $this->plugins[$this->current_plugin];

		return $this->plugin;
	}

	function rewind_plugins() {
		$this->current_plugin = -1;
		if ( $this->plugin_count > 0 ) {
			$this->plugin = $this->plugins[0];
		}
	}

	function plugins() {
		if ( $this->current_plugin + 1 < $this->plugin_count ) {
			return true;
		} elseif ( $this->current_plugin + 1 == $this->plugin_count ) {
			$this->rewind_plugins();
		}

		$this->in_the_loop = false;
		return false;
	}

	function the_plugin() {

		$this->in_the_loop = true;
		$this->plugin      = $this->next_plugin();

		// loop has just started
		if ( 0 == $this->current_plugin )
			do_action( 'plugin_loop_start' );
	}
}

function thaim_has_plugins( $args = '' ) {
	global $plugins_template;
	
	$wporg = get_option('thaim_link_wordpress_org');
	
	$author         = 'imath';
	$page         = 1;
	$per_page     = 20;
	
	$per_page = !empty( $wporg['perpage'] ) ? $wporg['perpage'] : $per_page;
	$author   = !empty( $wporg['username'] ) ? $wporg['username'] : $author;
	
	$defaults = array( 'page' => $page, 
					   'per_page' => $per_page, 
					   'author' => $author, 
					   'fields' => array( 'description' => false, 
					                      'sections' => false, 
					                      'tested' => false ,
					                      'requires' => true, 
					                      'rating' => true, 
					                      'downloaded' => true, 
					                      'downloadlink' => true, 
					                      'last_updated' => true, 
					                      'homepage' => false, 
					                      'tags' => true ) 
				     );
	
	$r = wp_parse_args( $args, $defaults );
	
	$plugins_template = new Thaim_WP_Org_Api_Template( $r );
	
	return apply_filters( 'thaim_has_plugins', $plugins_template->has_plugins(), $plugins_template );
}

function thaim_the_plugin() {
	global $plugins_template;
	return $plugins_template->the_plugin();
}

function thaim_plugins() {
	global $plugins_template;
	return $plugins_template->plugins();
}

function thaim_plugin_name() {
	echo thaim_get_plugin_name();
}

	function thaim_get_plugin_name() {
		global $plugins_template;
		
		return apply_filters('thaim_get_plugin_name', $plugins_template->plugin->name );
	}

function thaim_plugin_slug() {
	echo thaim_get_plugin_slug();
}

	function thaim_get_plugin_slug() {
		global $plugins_template;

		return apply_filters('thaim_get_plugin_slug', $plugins_template->plugin->slug );
	}
	
function thaim_plugin_version() {
	echo thaim_get_plugin_version();
}

	function thaim_get_plugin_version() {
		global $plugins_template;

		return apply_filters('thaim_get_plugin_version', $plugins_template->plugin->version );
	}	
	
function thaim_plugin_requires() {
	echo thaim_get_plugin_requires();
}

	function thaim_get_plugin_requires() {
		global $plugins_template;

		return apply_filters('thaim_get_plugin_requires', $plugins_template->plugin->requires );
	}
	
function thaim_plugin_rating() {
	echo thaim_get_plugin_rating();
}

	function thaim_get_plugin_rating() {
		global $plugins_template;
		
		$ratings = $plugins_template->plugin->rating;
		$num_ratings = $plugins_template->plugin->num_ratings;
		
		$ratings = ( $ratings / 100 ) * 5;
		$rating = $ratings;
		
		$output = sprintf( __('<div>Average of %s stars out of %s votes', 'thaim'), $ratings, $num_ratings);
		$output .= '<ul class="thaim-rating">';
		
		for( $i = 1 ; $i <= 5; $i++ ){
			
			if( $rating >= 1 )
				$icon = '&#xe08f;';
			elseif( $rating > 0 )
				$icon = '&#xe090;';
			else
				$icon = '&#xe08a;';
			
			$output .= '<li><span aria-hidden="true" data-icon="'.$icon.'"></span></li>';
			
			$rating -= 1; 
			
		}
		
		$output .= '</ul></div>';

		return apply_filters('thaim_get_plugin_rating', $output );
	}
	
function thaim_plugin_buddypress_tag() {
	global $plugins_template;
	
	$tags = $plugins_template->plugin->tags;
	
	if( in_array('buddypress', $tags ) )
		echo apply_filters('thaim_plugin_buddypress_tag', '<h4 class="buddypress"><span aria-hidden="true" data-icon="&#xe000;"></span></h4>');
}

function thaim_plugin_description() {
	echo thaim_get_plugin_description();
}

	function thaim_get_plugin_description() {
		global $plugins_template;
		
		$output = '<p>' . sprintf( __('Latest version is %s and requires %s', 'thaim'), thaim_get_plugin_version(), thaim_get_plugin_requires() ) .'</p>';
		
		$output .= '<p class="thaim-short-desc">'.$plugins_template->plugin->short_description.'</p>';
		
		$dowloaded = '<div class="thaim-downloads">' .sprintf( __('Number of downloads:<br/> %s', 'thaim' ), $plugins_template->plugin->downloaded ). '</div>';
		
		$output .= '<table class="thaim-plugin-desc"><tr><td>' .thaim_get_plugin_rating(). '</td><td>' .$dowloaded. '</td></tr></table>';
		
		return apply_filters('thaim_get_plugin_description', $output);
	}

function thaim_plugin_download_link() {
	echo thaim_get_plugin_download_link();
}

	function thaim_get_plugin_download_link() {
		global $plugins_template;
		
		$download_link = sprintf( __('<a href="%s" title="Download plugin">Download</a>', 'thaim'), $plugins_template->plugin->download_link);
		
		return apply_filters('thaim_get_plugin_download_link', $download_link );
	}
	
function thaim_plugin_info_link() {
	echo thaim_get_plugin_info_link();
}

	function thaim_get_plugin_info_link() {

		$info_link = sprintf( __('<a href="%s" title="View Plugin on WordPress.org">View details</a>', 'thaim'), 'http://wordpress.org/extend/plugins/'.thaim_get_plugin_slug() );

		return apply_filters('thaim_get_plugin_info_link', $info_link );
	}

function thaim_plugin_tag_link() {
	echo thaim_get_plugin_tag_link();
}

	function thaim_get_plugin_tag_link() {

		$tag_link = sprintf( __('<a href="%s" title="View Posts about the plugin">Posts</a>', 'thaim'), site_url('tag') .'/'.thaim_get_plugin_slug() );

		return apply_filters('thaim_get_plugin_info_link', $tag_link, thaim_get_plugin_slug() );
	}
