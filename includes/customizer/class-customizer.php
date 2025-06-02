<?php
declare(strict_types=1);

namespace HolyVonsheezy\Customizer;

use HolyVonsheezy\Includes\Customizer\Customizer_Action_Links;

class Customizer{
    private static Customizer $instance;

    private function __construct()
    {
        add_action( 'customize_register', [$this, 'register'] );
    }

    public static function instance(): Customizer
    {
        if( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function register( $wp_customize ){
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
}