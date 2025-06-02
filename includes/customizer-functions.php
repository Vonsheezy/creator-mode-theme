<?php
/**
 * A file that registers Customizer settings
 *
 * @package HolyVonsheezy\Includes
 */

namespace HolyVonsheezy\Includes;

use WP_Customize_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers customizer settings, sections, and controls for the theme.
 *
 * @param WP_Customize_Manager $wp_customize Instance of the WordPress Customizer manager.
 * @return void
 */
function vonsheezy_customizer_register( $wp_customize ) {
	require get_template_directory() . '/includes/customizer/class-customizer-action-links.php';

	$wp_customize->add_section(
		'vonsheezy-options',
		array(
			'title'      => esc_html__( 'Header & Footer', 'holy-vonsheezy' ),
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_setting(
		'vonsheezy-header-footer',
		array(
			'sanitize_callback' => false,
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new HolyVonsheezy\Includes\Customizer\Holy_VonsheezyCustomizer_Action_Links(
			$wp_customize,
			'vonsheezy-header-footer',
			array(
				'section'  => 'vonsheezy-options',
				'priority' => 20,
			)
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
		array(),
		HELLO_ELEMENTOR_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'vonsheezy_customizer_styles' );
