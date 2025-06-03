<?php
/**
 * A file that registers Customizer settings
 *
 * @package HolyCanvas\Includes
 */

namespace HolyCanvas\Includes;

use HolyCanvas\Includes\Customizer\Customizer_Action_Links;
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
function customizer_register( $wp_customize ) {
	//require get_template_directory() . '/includes/customizer/class-customizer-action-links.php';

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
		new Customizer_Action_Links(
			$wp_customize,
			'vonsheezy-header-footer',
			array(
				'section'  => 'vonsheezy-options',
				'priority' => 20,
			)
		)
	);
}
add_action( 'customize_register', __NAMESPACE__ . '\customizer_register' );

/**
 * Enqueue Customizer CSS.
 *
 * @return void
 */
function customizer_styles() {

	$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'holy-vonsheezy-customizer',
		get_template_directory_uri() . '/customizer' . $min_suffix . '.css',
		array(),
		HELLO_ELEMENTOR_VERSION
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\customizer_styles' );
