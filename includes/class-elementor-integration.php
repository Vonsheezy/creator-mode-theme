<?php
/**
 * A class module for the Elementor integration.
 *
 * @package CreatorMode\Includes\Elementor_Integration
 *
 * @author Eliasu Abraman
 * @link https://github.com/vonsheezy/vonsheezy-elementor-theme
 * @license https://opensource.org/licenses/MIT MIT License
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace CreatorMode\Includes;

use CreatorMode\Includes\Settings\Settings_Footer;
use CreatorMode\Includes\Settings\Settings_Header;
use Elementor\Core\Experiments\Manager;
use Elementor\Plugin;


const EDITOR_STYLE = 'vonsheezy-editor';
/**
 * Class Elementor_Integration
 *
 * This class integrates theme-specific functionalities with Elementor.
 * It handles custom settings, experiments, enqueues scripts/styles,
 * and manages header and footer customizations.
 */
class Elementor_Integration {
	const ELEMENTOR_EDITOR_AFTER_ENQUEUE_SCRIPTS            = 'elementor/editor/after_enqueue_scripts';
	const THEME_EDITOR_SCRIPT                               = 'creator-mode-theme-editor';
	const ELEMENTOR_EXPERIMENTS_DEFAULT_FEATURES_REGISTERED = 'elementor/experiments/default-features-registered';
	/**
	 * Represents a single instance of the object or class. Initialized to null by default.
	 *
	 * @var Elementor_Integration|null
	 */
	private static ?Elementor_Integration $instance = null;

	/**
	 * Constructor for the class.
	 *
	 * Initializes various hooks for setting up settings, enqueuing scripts and styles
	 * for Elementor integration and theme frontend, and registering experiments
	 * for the Holy Vonsheezy theme's header and footer features.
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'elementor/init', array( $this, 'settings_init' ) );
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
                    CREATIVE_MODE_VERSION,
					true
				);

				wp_enqueue_style(
					EDITOR_STYLE,
					get_template_directory_uri() . '/editor' . $suffix . '.css',
					array(),
                    CREATIVE_MODE_VERSION
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
					'creator-mode-theme-frontend',
					CREATIVE_MODE_ASSETS_URL . '/js/creative-mode-frontend' . $suffix . '.js',
					array(),
					CREATIVE_MODE_VERSION,
					true
				);

				Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
			}
		);

		/**
		 * Add Holy Vonsheezy theme Header & Footer to Experiments.
		 */
		add_action(
			self::ELEMENTOR_EXPERIMENTS_DEFAULT_FEATURES_REGISTERED,
			function ( Manager $experiments_manager ) {
				$experiments_manager->add_feature(
					array(
						'name'           => 'creator-mode-theme-header-footer',
						'title'          => esc_html__( 'CreatorMode Theme Header & Footer', 'creator-mode' ),
						'description'    => sprintf(
							'%1$s <a href="%2$s" target="_blank">%3$s</a>',
							esc_html__( 'Customize and style the builtin CreatorMode Theme’s cross-site header & footer from the Elementor "Site Settings" panel.', 'creator-mode' ),
							'https://go.elementor.com/wp-dash-header-footer',
							esc_html__( 'Learn More', 'creator-mode' )
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

		add_filter( 'creative_mode_page_title', array( $this, 'check_hide_title' ) );
	}

	/**
	 * Returns the single instance of the class.
	 *
	 * Initializes the class if it hasn't been initialized yet.
	 *
	 * @uses Elementor_Integration::instance()
	 *
	 * @return Elementor_Integration The single instance of the class.
	 */
	public static function instance() {
		if ( null === static::$instance ) {
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
	public function check_hide_title( $val ): bool {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}

	/**
	 * Register the settings for the Holy Creative Mode theme's header and footer features.'
	 *
	 * @return void
	 */
	public function settings_init() {
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

	/**
	 * Returns the value of a setting.
	 *
	 * @param string $setting_id The ID of the setting.
	 *
	 * @return mixed|null The value of the setting.
	 */
	public static function get_setting( string $setting_id ) {
		global $vonsheezy_elementor_settings;

		$return = '';

		if ( ! isset( $vonsheezy_elementor_settings['kit_settings'] ) ) {
			$kit = Plugin::$instance->kits_manager->get_active_kit();
			$vonsheezy_elementor_settings['kit_settings'] = $kit->get_settings();
		}

		if ( isset( $vonsheezy_elementor_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $vonsheezy_elementor_settings['kit_settings'][ $setting_id ];
		}

		return apply_filters( 'creative_mode_' . $setting_id, $return );
	}

	/**
	 * Returns the value of a setting.
	 *
	 * @param string $setting_id The ID of the setting.
	 *
	 * @return string The value of the setting.
	 */
	public static function show_or_hide( string $setting_id ): string {
		return ( 'yes' === self::get_setting( $setting_id ) ? 'show' : 'hide' );
	}

	/**
	 * Helper function to translate the header layout setting into a class name.
	 *
	 * @return string
	 */
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

	/**
	 * Helper function to translate the header layout setting into a class name.
	 *
	 * @param string $setting_id A settings ID.
	 * @return mixed|null
	 */
	public static function elementor_get_setting( string $setting_id ) {
		global $vonsheezy_elementor_settings;

		$return = '';

		if ( ! isset( $vonsheezy_elementor_settings['kit_settings'] ) ) {
			$kit = Plugin::$instance->kits_manager->get_active_kit();
			$vonsheezy_elementor_settings['kit_settings'] = $kit->get_settings();
		}

		if ( isset( $vonsheezy_elementor_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $vonsheezy_elementor_settings['kit_settings'][ $setting_id ];
		}

		return apply_filters( 'creative_mode_' . $setting_id, $return );
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
		$is_editor = isset( $_GET['elementor-preview'] ); // phpcs:ignore

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
	public function get_footer_display(): bool {
		$is_editor = isset( $_GET['elementor-preview'] ); // phpcs:ignore

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
		if ( ! method_exists( Plugin::$instance->experiments, 'is_feature_active' ) ) {
			return false;
		}

		return (bool) ( Plugin::$instance->experiments->is_feature_active( 'creator-mode-theme-header-footer' ) );
	}
}
