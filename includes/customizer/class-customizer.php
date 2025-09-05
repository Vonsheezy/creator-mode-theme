<?php
/**
 * A class module for the Customizer
 *
 * @package CreatorMode\Includes\Customizer
 */

declare(strict_types=1);

namespace CreatorMode\Includes\Customizer;

use WP_Customize_Manager;

/**
 * A class that keeps all Customizer registration and customization.
 */
class Customizer {
	/**
	 * A singleton instance of the Customizer module.
	 *
	 * @var Customizer|null The instance of the Customizer module.
	 */
	private static ?Customizer $instance = null;

	/**
	 * A private constructor to prevent creating an instance of the class.
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'customizer_styles' ) );
	}

	/**
	 * A singleton method of the Customizer module
	 *
	 * @return Customizer
	 */
	public static function instance(): Customizer {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * A method that registers customizer sections and settings
	 *
	 * @param WP_Customize_Manager $wp_customize  The Customizer object.
	 * @return void
	 */
	public function register( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section(
			'vonsheezy-options',
			array(
				'title'      => esc_html__( 'Header & Footer', 'creator-mode' ),
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

	/**
	 * Enqueue Customizer CSS.
	 *
	 * @return void
	 */
	public function customizer_styles() {

		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'creator-mode-customizer',
			get_template_directory_uri() . '/customizer' . $min_suffix . '.css',
			array(),
			CREATOR_MODE_VERSION
		);
	}
}
