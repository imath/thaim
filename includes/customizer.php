<?php
/**
 * Thaim Customizer
 *
 * @package Thaim
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Add custom settings for the Theme Customizer.
 *
 * @since  1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function thaim_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';

	/**
	 * Theme options.
	 */
	$wp_customize->add_section( 'theme_options', array(
		'title'    => __( 'Theme options', 'thaim' ),
		'priority' => 130, // Before Additional CSS.
	) );

	// Maintenance mode
	$wp_customize->add_setting( 'maintenance_mode', array(
		'default'           => 1,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'maintenance_mode', array(
		'label'       => __( 'Maintenance', 'thaim' ),
		'section'     => 'theme_options',
		'type'        => 'radio',
		'choices'     => array(
			0 => __( 'Maintenance off', 'thaim' ),
			1 => __( 'Maintenance on', 'thaim' ),
		),
	) );

	// Contact page
	$wp_customize->add_setting( 'contact_page', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'contact_page', array(
		'label'           => __( 'Contact page', 'thaim' ),
		'description'     => __( 'Select the page to use as the contact form.', 'thaim' ),
		'section'         => 'theme_options',
		'type'            => 'dropdown-pages',
		'allow_addition'  => true,
	) );

	// Entrepôt Page
	$wp_customize->add_setting( 'entrepot_page', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'entrepot_page', array(
		'label'           => __( 'Entrepôt page', 'thaim' ),
		'section'         => 'theme_options',
		'type'            => 'dropdown-pages',
		'allow_addition'  => true,
	) );
}
add_action( 'customize_register', 'thaim_customize_register' );
