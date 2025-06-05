<?php
/**
 * A class module for the Customizer
 *
 * @package CreatorMode\Includes\Customizer
 */

declare(strict_types=1);

namespace CreatorMode\Includes\Customizer;

/**
 * A class that keeps all Customizer registration and customization.
 */
class Customizer {
    private static ?Customizer $instance = null;

    private function __construct()
    {
        add_action( 'customize_register', [$this, 'register'] );
        add_action( 'admin_enqueue_scripts', [$this, 'customizer_styles'] );
    }

    /**
     * A singleton method of the Customizer module
     *
     * @return Customizer
     */
    public static function instance(): Customizer
    {
        if( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * A method that registers customizer sections and settings
     *
     * @param $wp_customize
     * @return void
     */
    public function register( $wp_customize ){
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
            HELLO_ELEMENTOR_VERSION
        );
    }
}