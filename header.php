<?php
/**
 * The header of thaim.
 *
 * @package thaim
 * @since 1.0.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

	<!-- CSS + jQuery + JavaScript -->
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<a id="top"></a>
	<!-- Header -->
	<header id="thaim-site">

		<!-- Wrapper -->
		<div class="wrapper">

			<?php thaim_the_custom_logo(); ?>

			<!-- Info -->
			<div id="thaim-info">
				<h1><a href="<?php echo site_url();?>"><?php thaim_blogname();?></a></h1>
				<div class="description"><?php bloginfo( 'description' );?></div>
			</div>
			<!-- /Info -->

			<!-- Nav -->
			<nav role="navigation" class="site-navigation main-navigation">
				<h1 class="assistive-text"><?php esc_html_e( 'Menu', 'thaim' ); ?></h1>
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

			<?php thaim_headline();?>

		</div>
		<!-- /Wrapper -->
	</div>

	<div id="thaim-content" role="main">

		<!-- Wrapper -->
		<div class="wrapper">

			<div class="row">
