<?php
/**
 * The header of thaim.
 *
 * @package thaim
 * @since thaim 1.0-beta1
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]--> 
<head>
	<meta charset="UTF-8">
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
	
	<!-- Meta -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0;">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	<meta name="description" content="<?php bloginfo('description'); ?>">
	
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
	
	<!-- For third-generation iPad with high-resolution Retina display: -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-144x144-precomposed.png">
	<!-- For iPhone with high-resolution Retina display: -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-114x114-precomposed.png">
	<!-- For first- and second-generation iPad: -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-72x72-precomposed.png">
	<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png">
		
	<!-- CSS + jQuery + JavaScript -->
	<?php wp_head(); ?>
	
</head>
<body <?php body_class(); ?>>
	<a id="top"></a>
	<!-- Header -->
	<header id="thaim-site">
	
		<!-- Wrapper -->
		<div class="wrapper">
		
			<!-- Logo -->
			<div id="thaim-info">
				<h1><a href="<?php echo site_url();?>"><?php thaim_blogname();?></a></h1>
				<div class="description"><?php bloginfo('description');?></div>
			</div>
			<!-- /Logo -->
			
			<!-- Nav -->
			<nav role="navigation" class="site-navigation main-navigation">
				<h1 class="assistive-text"><?php _e( 'Menu', '_s' ); ?></h1>
				<?php thaim_nav(); ?>
			</nav>
			<!-- /Nav -->
			
			<br class="clear">
			
		</div>
		<!-- /Wrapper -->
	
	</header>
	<!-- /Header -->
	
	<div id="thaim-headline">
		<!-- Wrapper -->
		<div class="wrapper">
			
			<?php thaim_ideastream_headline();?>
			
		</div>
		<!-- /Wrapper -->
	</div>
	
	<div id="thaim-content" role="main">
		
		<!-- Wrapper -->
		<div class="wrapper">
		
			<div class="row">