<?php
$admin = dirname( __FILE__ ) ;
$admin = substr( $admin , 0 , strpos( $admin , "wp-content" ) ) ;

require_once( $admin . 'wp-load.php' ) ;

wp_enqueue_style( 'global' );
wp_enqueue_style( 'wp-admin' );
wp_enqueue_style('thaim-admin-shortcode-css', get_template_directory_uri() . '/css/thaim-admin-shortcode.css' );
wp_enqueue_script('jquery');
wp_enqueue_script('thaim-admin-shortcode-js', get_template_directory_uri() . '/js/thaim-admin-shortcode.js', array('jquery'), '1.0.0' );
wp_localize_script('thaim-admin-shortcode-js', 'thaim_code_vars', array(
			'rawoops'        => __('Oops, you forgot to add a github raw url','thaim'),
			'urloops'        => __('Oops, you forgot to add a github url','thaim'),
		)
	);

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php _e('Edit your shortcode', 'thaim');?></title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="imath">
	<!-- Date: 2012-02-17 -->
	<?php 
	do_action('admin_print_styles');
	wp_admin_css( 'colors-fresh', true );
	do_action('admin_print_scripts');
	do_action('admin_head');
	?>
</head>
<body class="wp-core-ui">
	<div class="wrap" style="margin:0 auto;width:90%">
		<p><?php _e('Choose the kind of shortcode you want to use', 'thaim');?></p>
		
		<p><a href="#" class="button-secondary" id="thaim-code-button"><?php _e('Add your code', 'thaim');?></a>&nbsp;<a href="#" class="button-secondary" id="github-code-button"><?php _e('Add a code from github', 'thaim');?></a></p>
		
		<div id="thaim-code">
			<label>
				<input type="checkbox" class="linenums"> <?php _e('Print line numbers', 'thaim')?></input>
			</label>
			<textarea placeholder="Paste your code in this box" id="thaim-code-content" width="100%" height="200px"></textarea>
		</div>
		<div id="github-code">
			<label>
				<input type="checkbox" class="linenums"> <?php _e('Print line numbers', 'thaim')?></input>
			</label>
			<label>
				<span><?php _e('Add the github url to your gist or repo file', 'thaim');?></span><br/>
				<input type="text" id="githuburl" />
			</label>
			<label>
				<span><?php _e('Add the github <b>raw</b> url to your gist or repo file', 'thaim');?></span><br/>
				<input type="text" id="githubraw" />
				<p class="description"><?php _e('The code is taken from this raw url', 'thaim')?></p>
			</label>
			<label>
				<span><?php _e('You can extract a portion of the github code by adding the starting and ending line numbers', 'thaim');?></span><br/>
			</label>
			<label>
				<span><?php _e('Starting point', 'thaim');?></span> <input type="text" id="githubfrom" />
			</label>
			<label>
				<span><?php _e('Ending point', 'thaim');?></span> <input type="text" id="githubto" />
			</label>
		</div>
		
		<p class="submit"><a href="#" class="button-primary insertShortcode"><?php _e('Insert Shortcode', 'thaim');?></a> &nbsp;<a href="#" class="button-secondary cancelShortcode"><?php _e('Cancel', 'thaim');?></a></p>
		<p class="description"><?php _e('Do not forget to add a custom field having the name of prettifyed and a value set to 1', 'thaim')?></p>
	</div>
</body>
</html>