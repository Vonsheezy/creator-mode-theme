<?php
declare(strict_types=1);

namespace HolyCanvas\Includes;

use HolyCanvas\Includes\Settings\Settings_Footer;
use HolyCanvas\Includes\Settings\Settings_Header;


const EDITOR_STYLE = 'vonsheezy-editor';
class Elementor_Integration {
    const ELEMENTOR_EDITOR_AFTER_ENQUEUE_SCRIPTS = 'elementor/editor/after_enqueue_scripts';
    const THEME_EDITOR_SCRIPT = 'holy-canvas-theme-editor';
    const ELEMENTOR_EXPERIMENTS_DEFAULT_FEATURES_REGISTERED = 'elementor/experiments/default-features-registered';
    private static $instance = null;
    private function __construct(){
        add_action( 'elementor/init', [$this, 'settings_init'] );
        add_action(
            self::ELEMENTOR_EDITOR_AFTER_ENQUEUE_SCRIPTS,
            function () {
                if ( ! self::header_footer_experiment_active() ) {
                    return;
                }

                $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

                wp_enqueue_script(
                    self::THEME_EDITOR_SCRIPT,
                    get_template_directory_uri() . '/assets/js/vonsheezy-editor' . $suffix . '.js',
                    array( 'jquery', 'elementor-editor' ),
                    HELLO_ELEMENTOR_VERSION,
                    true
                );

                wp_enqueue_style(
                    EDITOR_STYLE,
                    get_template_directory_uri() . '/editor' . $suffix . '.css',
                    array(),
                    HELLO_ELEMENTOR_VERSION
                );
            }
        );

        add_action(
            'wp_enqueue_scripts',
            function () {
                if ( ! Theme::display_header_footer() ) {
                    return;
                }

                if ( ! self::header_footer_experiment_active() ) {
                    return;
                }

                $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

                wp_enqueue_script(
                    'holy-vonsheezy-theme-frontend',
                    get_template_directory_uri() . '/assets/js/vonsheezy-frontend' . $suffix . '.js',
                    array(),
                    HELLO_ELEMENTOR_VERSION,
                    true
                );

                \Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            }
        );

        /**
         * Add Holy Vonsheezy theme Header & Footer to Experiments.
         */
        add_action(
            self::ELEMENTOR_EXPERIMENTS_DEFAULT_FEATURES_REGISTERED,
            function ( \Elementor\Core\Experiments\Manager $experiments_manager ) {
                $experiments_manager->add_feature(
                    array(
                        'name'           => 'holy-vonsheezy-theme-header-footer',
                        'title'          => esc_html__( 'HolyCanvas Theme Header & Footer', 'holy-vonsheezy' ),
                        'description'    => sprintf(
                            '%1$s <a href="%2$s" target="_blank">%3$s</a>',
                            esc_html__( 'Customize and style the builtin HolyCanvas Theme’s cross-site header & footer from the Elementor "Site Settings" panel.', 'holy-vonsheezy' ),
                            'https://go.elementor.com/wp-dash-header-footer',
                            esc_html__( 'Learn More', 'holy-vonsheezy' )
                        ),
                        'release_status' => $experiments_manager::RELEASE_STATUS_STABLE,
                        'new_site'       => array(
                            'minimum_installation_version' => '3.3.0',
                            'default_active'               => $experiments_manager::STATE_ACTIVE,
                        ),
                    )
                );
            }
        );

        add_filter( 'vonsheezy_elementor_page_title', [$this, 'check_hide_title'] );

    }
    public static function instance() {
        if( null === static::$instance){
            static::$instance = new static();
        }
        return static::$instance;
    }



    /**
     * Check whether to display the page title.
     *
     * @param bool $val default value.
     *
     * @return bool
     */
    public function check_hide_title( $val ) {
        if ( defined( 'ELEMENTOR_VERSION' ) ) {
            $current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
            if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
                $val = false;
            }
        }
        return $val;
    }
    public function settings_init(){
        if ( ! self::header_footer_experiment_active() ) {
            return;
        }

        add_action(
            'elementor/kit/register_tabs',
            function ( \Elementor\Core\Kits\Documents\Kit $kit ) {
                if ( ! Theme::display_header_footer() ) {
                    return;
                }

                $kit->register_tab( 'vonsheezy-settings-header', Settings_Header::class );
                $kit->register_tab( 'vonsheezy-settings-footer', Settings_Footer::class );
            },
            1,
            40
        );
    }

    public static function get_setting($setting_id){
        global $vonsheezy_elementor_settings;

        $return = '';

        if ( ! isset( $vonsheezy_elementor_settings['kit_settings'] ) ) {
            $kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
            $vonsheezy_elementor_settings['kit_settings'] = $kit->get_settings();
        }

        if ( isset( $vonsheezy_elementor_settings['kit_settings'][ $setting_id ] ) ) {
            $return = $vonsheezy_elementor_settings['kit_settings'][ $setting_id ];
        }

        return apply_filters( 'vonsheezy_elementor_' . $setting_id, $return );
    }
    public static function show_or_hide($setting_id ) {
        return ( 'yes' === self::get_setting( $setting_id ) ? 'show' : 'hide' );
    }

    public static function get_header_layout_class(): string {
        $layout_classes = array();

        $header_layout = self::get_setting( 'vonsheezy_header_layout' );
        if ( 'inverted' === $header_layout ) {
            $layout_classes[] = 'header-inverted';
        } elseif ( 'stacked' === $header_layout ) {
            $layout_classes[] = 'header-stacked';
        }

        $header_width = self::get_setting( 'vonsheezy_header_width' );
        if ( 'full-width' === $header_width ) {
            $layout_classes[] = 'header-full-width';
        }

        $header_menu_dropdown = self::get_setting( 'vonsheezy_header_menu_dropdown' );
        if ( 'tablet' === $header_menu_dropdown ) {
            $layout_classes[] = 'menu-dropdown-tablet';
        } elseif ( 'mobile' === $header_menu_dropdown ) {
            $layout_classes[] = 'menu-dropdown-mobile';
        } elseif ( 'none' === $header_menu_dropdown ) {
            $layout_classes[] = 'menu-dropdown-none';
        }

        $vonsheezy_header_menu_layout = self::get_setting( 'vonsheezy_header_menu_layout' );
        if ( 'dropdown' === $vonsheezy_header_menu_layout ) {
            $layout_classes[] = 'menu-layout-dropdown';
        }

        return implode( ' ', $layout_classes );
    }
    public static function elementor_get_setting($setting_id ) {
        global $vonsheezy_elementor_settings;

        $return = '';

        if ( ! isset( $vonsheezy_elementor_settings['kit_settings'] ) ) {
            $kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
            $vonsheezy_elementor_settings['kit_settings'] = $kit->get_settings();
        }

        if ( isset( $vonsheezy_elementor_settings['kit_settings'][ $setting_id ] ) ) {
            $return = $vonsheezy_elementor_settings['kit_settings'][ $setting_id ];
        }

        return apply_filters( 'vonsheezy_elementor_' . $setting_id, $return );
    }

    /**
     * Helper function to translate the footer layout setting into a class name.
     *
     * @return string
     */
    public static function get_footer_layout_class() {
        $footer_layout = self::get_setting( 'vonsheezy_footer_layout' );

        $layout_classes = array();

        if ( 'inverted' === $footer_layout ) {
            $layout_classes[] = 'footer-inverted';
        } elseif ( 'stacked' === $footer_layout ) {
            $layout_classes[] = 'footer-stacked';
        }

        $footer_width = self::get_setting( 'vonsheezy_footer_width' );

        if ( 'full-width' === $footer_width ) {
            $layout_classes[] = 'footer-full-width';
        }

        if ( self::get_setting( 'vonsheezy_footer_copyright_display' ) && '' !== self::get_setting( 'vonsheezy_footer_copyright_text' ) ) {
            $layout_classes[] = 'footer-has-copyright';
        }

        return implode( ' ', $layout_classes );
    }

    /**
     * Helper function to decide whether to output the header template.
     *
     * @return bool
     */
    public static function get_header_display(): bool {
        $is_editor = isset( $_GET['elementor-preview'] );

        return (
            $is_editor
            || self::get_setting( 'vonsheezy_header_logo_display' )
            || self::get_setting( 'vonsheezy_header_tagline_display' )
            || self::get_setting( 'vonsheezy_header_menu_display' )
        );
    }

    /**
     * Helper function to decide whether to output the footer template.
     *
     * @return bool
     */
    function get_footer_display(): bool {
        $is_editor = isset( $_GET['elementor-preview'] );

        return (
            $is_editor
            || self::get_setting( 'vonsheezy_footer_logo_display' )
            || self::get_setting( 'vonsheezy_footer_tagline_display' )
            || self::get_setting( 'vonsheezy_footer_menu_display' )
            || self::get_setting( 'vonsheezy_footer_copyright_display' )
        );
    }

    /**
     * Helper function to check if Header & Footer Experiment is Active/Inactive.
     *
     * @return true
     */
    public static function header_footer_experiment_active(): bool {
        // If Elementor is not active, return false.
        if ( ! did_action( 'elementor/loaded' ) ) {
            return false;
        }
        // Backwards compat.
        if ( ! method_exists( \Elementor\Plugin::$instance->experiments, 'is_feature_active' ) ) {
            return false;
        }

        return (bool) ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'holy-vonsheezy-theme-header-footer' ) );
    }
}