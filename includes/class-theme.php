<?php

declare(strict_types=1);
/**
 *
 */

namespace HolyVonsheezy;

class Theme {
    private static $instance = null;
    private function __construct()
    {
        add_action( 'wp_enqueue_scripts', [$this, 'register_assets'] );
        add_action( 'after_setup_theme', [$this, 'content_width'], 0 );
        add_action( 'after_setup_theme', [$this, 'setup'] );
        add_action( 'init', [$this, 'register_customizer'] );
        add_action( 'wp_head', [$this, 'add_description_meta_tag'] );
    }

    public static function instance()
    {
        if( null === static::$instance ) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Initializes the theme setup by adding theme support options,
     * menu registrations, and other customizations.
     *
     * This method conditionally applies theme supports based on filters
     * and registers navigation menus and post type supports. It also
     * configures WooCommerce feature support if enabled.
     *
     * @return void
     */
    public function setup()
    {
        if ( is_admin() ) {
            $this->maybe_update_theme_version_in_db();
        }

        if ( apply_filters( 'vonsheezy_elementor_register_menus', true ) ) {
            register_nav_menus( array( 'menu-1' => esc_html__( 'Header', 'holy-vonsheezy' ) ) );
            register_nav_menus( array( 'menu-2' => esc_html__( 'Footer', 'holy-vonsheezy' ) ) );
        }

        if ( apply_filters( 'vonsheezy_elementor_post_type_support', true ) ) {
            add_post_type_support( 'page', 'excerpt' );
        }

        if ( apply_filters( 'vonsheezy_elementor_add_theme_support', true ) ) {
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'automatic-feed-links' );
            add_theme_support( 'title-tag' );
            add_theme_support(
                'html5',
                array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'script',
                    'style',
                )
            );
            add_theme_support(
                'custom-logo',
                array(
                    'height'      => 100,
                    'width'       => 350,
                    'flex-height' => true,
                    'flex-width'  => true,
                )
            );

            /*
             * Editor Style.
             */
            add_editor_style( 'classic-editor.css' );

            /*
             * Gutenberg wide images.
             */
            add_theme_support( 'align-wide' );

            /*
             * WooCommerce.
             */
            if ( apply_filters( 'vonsheezy_elementor_add_woocommerce_support', true ) ) {
                // WooCommerce in general.
                add_theme_support( 'woocommerce' );
                // Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
                // zoom.
                add_theme_support( 'wc-product-gallery-zoom' );
                // lightbox.
                add_theme_support( 'wc-product-gallery-lightbox' );
                // swipe.
                add_theme_support( 'wc-product-gallery-slider' );
            }
        }
    }

    /**
     * Updates the theme version stored in the database if it is outdated or not set.
     *
     * This function checks the current theme version saved in the database. If the version
     * does not exist or is older than the defined theme version, it updates the database
     * with the latest theme version.
     *
     * @return void
     */
    public function maybe_update_theme_version_in_db() {
        $theme_version_option_name = 'vonsheezy_theme_version';
        // The theme version saved in the database.
        $vonsheezy_theme_db_version = get_option( $theme_version_option_name );

        // If the 'vonsheezy_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
        if ( ! $vonsheezy_theme_db_version || version_compare( $vonsheezy_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
            update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
        }
    }

    /**
     * Loads customizer functions for Elementor integration if conditions are met.
     *
     * This function checks whether the site is in customize preview mode and if the
     * Elementor header and footer display logic allows customizer functionality. If both
     * conditions are satisfied, it includes the necessary customizer-functions file.
     *
     * @return void
     */
    public function register_customizer() {
        if ( ! is_customize_preview() ) {
            return;
        }

        if ( ! $this->display_header_footer() ) {
            return;
        }

        require get_template_directory() . '/includes/customizer-functions.php';
    }

    public function add_description_meta_tag()
    {
        if ( ! apply_filters( 'vonsheezy_elementor_description_meta_tag', true ) ) {
            return;
        }

        if ( ! is_singular() ) {
            return;
        }

        $post = get_queried_object();
        if ( empty( $post->post_excerpt ) ) {
            return;
        }

        echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
    }
    public static function display_header_footer() {
        return apply_filters( 'vonsheezy_elementor_header_footer', true );
    }

    /**
     * Set default content width.
     *
     * @return void
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters( 'vonsheezy_elementor_content_width', 800 );
    }

    public function register_assets()
    {
        $min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        if ( apply_filters( 'vonsheezy_elementor_enqueue_style', true ) ) {
            wp_enqueue_style(
                'holy-vonsheezy',
                get_template_directory_uri() . '/style' . $min_suffix . '.css',
                array(),
                HELLO_ELEMENTOR_VERSION
            );
        }

        if ( apply_filters( 'vonsheezy_elementor_enqueue_theme_style', true ) ) {
            wp_enqueue_style(
                'holy-vonsheezy-theme-style',
                get_template_directory_uri() . '/theme' . $min_suffix . '.css',
                array(),
                HELLO_ELEMENTOR_VERSION
            );
        }

        if ( $this->display_header_footer() ) {
            wp_enqueue_style(
                'holy-vonsheezy-header-footer',
                get_template_directory_uri() . '/header-footer' . $min_suffix . '.css',
                array(),
                HELLO_ELEMENTOR_VERSION
            );
        }
    }
};