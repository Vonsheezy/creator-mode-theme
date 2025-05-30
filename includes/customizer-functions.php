<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Customizer controls.
 *
 * @return void
 */
function vonsheezy_customizer_register( $wp_customize ) {
	require get_template_directory() . '/includes/customizer/customizer-action-links.php';

	$wp_customize->add_section(
		'vonsheezy-options',
		[
			'title' => esc_html__( 'Header & Footer', 'holy-vonsheezy' ),
			'capability' => 'edit_theme_options',
		]
	);

	$wp_customize->add_setting(
		'vonsheezy-header-footer',
		[
			'sanitize_callback' => false,
			'transport' => 'refresh',
		]
	);

	$wp_customize->add_control(
		new HolyVonsheezy\Includes\Customizer\Holy_VonsheezyCustomizer_Action_Links(
			$wp_customize,
			'vonsheezy-header-footer',
			[
				'section' => 'vonsheezy-options',
				'priority' => 20,
			]
		)
	);
}
add_action( 'customize_register', 'vonsheezy_customizer_register' );

/**
 * Enqueue Customizer CSS.
 *
 * @return void
 */
function vonsheezy_customizer_styles() {

	$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'holy-vonsheezy-customizer',
		get_template_directory_uri() . '/customizer' . $min_suffix . '.css',
		[],
		HELLO_ELEMENTOR_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'vonsheezy_customizer_styles' );
