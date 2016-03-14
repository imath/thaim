<?php
/**
 * The template for displaying the maintenance page.
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
	<meta charset="UTF-8">
	<title><?php echo wp_get_document_title(); ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Meta -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0;">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<meta name="description" content="<?php bloginfo( 'description' ); ?>">

	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

	<?php do_action( 'thaim_maintenance_head' ) ;?>
</head>
<body <?php body_class(); ?>>

	<div id="thaim-site" role="main">

		<!-- Wrapper -->
		<div class="wrapper">

			<div class="row">

				<div class="fourcol">&nbsp;</div>

				<div id="thaim-info-maintenance" class="fourcol">

					<h1><?php thaim_blogname();?></h1>

					<?php thaim_maintenance_info();?>

				</div>

				<div class="fourcol last">&nbsp;</div>

			</div>

		</div>

	</div>

</body>
</html>
