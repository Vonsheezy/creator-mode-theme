<?php
/**
 * Implement Elementor functions
 *
 * @package HolyVonsheezy\Includes
 */

namespace HolyVonsheezy\Includes;

use HolyVonsheezy\Includes\Settings\Settings_Footer;
use HolyVonsheezy\Includes\Settings\Settings_Header;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Site Settings Controls.
 */

add_action( 'elementor/init', __NAMESPACE__. '\elementor_settings_init' );

/**
 * Initializes custom Elementor settings for header and footer management.
 *
 * This function checks the activation status of the header and footer experiment
 * and registers the necessary settings classes. It also hooks into Elementor's
 * kit registration process to add custom settings tabs for managing headers and footers.
 *
 * @return void
 */
function elementor_settings_init() {
	if ( ! header_footer_experiment_active() ) {
		return;
	}

	require 'settings/class-settings-header.php';
	require 'settings/class-settings-footer.php';

	add_action(
		'elementor/kit/register_tabs',
		function ( \Elementor\Core\Kits\Documents\Kit $kit ) {
			if ( ! vonsheezy_elementor_display_header_footer() ) {
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
 * Helper function to return a setting.
 *
 * Saves 2 lines to get kit, then get setting. Also caches the kit and setting.
 *
 * @param  string $setting_id Setting ID.
 * @return string|array same as the Elementor internal function does.
 */
function elementor_get_setting($setting_id ) {
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
 * Helper function to show/hide elements
 *
 * This works with switches, if the setting ID that has been passed is toggled on, we'll return show, otherwise we'll return hide
 *
 * @param  string $setting_id Setting ID.
 * @return string|array same as the Elementor internal function does.
 */
function show_or_hide($setting_id ) {
	return ( 'yes' === elementor_get_setting( $setting_id ) ? 'show' : 'hide' );
}

/**
 * Helper function to translate the header layout setting into a class name.
 *
 * @return string
 */
function get_header_layout_class(): string {
	$layout_classes = array();

	$header_layout = elementor_get_setting( 'vonsheezy_header_layout' );
	if ( 'inverted' === $header_layout ) {
		$layout_classes[] = 'header-inverted';
	} elseif ( 'stacked' === $header_layout ) {
		$layout_classes[] = 'header-stacked';
	}

	$header_width = elementor_get_setting( 'vonsheezy_header_width' );
	if ( 'full-width' === $header_width ) {
		$layout_classes[] = 'header-full-width';
	}

	$header_menu_dropdown = elementor_get_setting( 'vonsheezy_header_menu_dropdown' );
	if ( 'tablet' === $header_menu_dropdown ) {
		$layout_classes[] = 'menu-dropdown-tablet';
	} elseif ( 'mobile' === $header_menu_dropdown ) {
		$layout_classes[] = 'menu-dropdown-mobile';
	} elseif ( 'none' === $header_menu_dropdown ) {
		$layout_classes[] = 'menu-dropdown-none';
	}

	$vonsheezy_header_menu_layout = elementor_get_setting( 'vonsheezy_header_menu_layout' );
	if ( 'dropdown' === $vonsheezy_header_menu_layout ) {
		$layout_classes[] = 'menu-layout-dropdown';
	}

	return implode( ' ', $layout_classes );
}

/**
 * Helper function to translate the footer layout setting into a class name.
 *
 * @return string
 */
function get_footer_layout_class() {
	$footer_layout = elementor_get_setting( 'vonsheezy_footer_layout' );

	$layout_classes = array();

	if ( 'inverted' === $footer_layout ) {
		$layout_classes[] = 'footer-inverted';
	} elseif ( 'stacked' === $footer_layout ) {
		$layout_classes[] = 'footer-stacked';
	}

	$footer_width = elementor_get_setting( 'vonsheezy_footer_width' );

	if ( 'full-width' === $footer_width ) {
		$layout_classes[] = 'footer-full-width';
	}

	if ( elementor_get_setting( 'vonsheezy_footer_copyright_display' ) && '' !== elementor_get_setting( 'vonsheezy_footer_copyright_text' ) ) {
		$layout_classes[] = 'footer-has-copyright';
	}

	return implode( ' ', $layout_classes );
}

add_action(
	'elementor/editor/after_enqueue_scripts',
	function () {
		if ( ! header_footer_experiment_active() ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'holy-vonsheezy-theme-editor',
			get_template_directory_uri() . '/assets/js/vonsheezy-editor' . $suffix . '.js',
			array( 'jquery', 'elementor-editor' ),
			HELLO_ELEMENTOR_VERSION,
			true
		);

		wp_enqueue_style(
			'vonsheezy-editor',
			get_template_directory_uri() . '/editor' . $suffix . '.css',
			array(),
			HELLO_ELEMENTOR_VERSION
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! vonsheezy_elementor_display_header_footer() ) {
			return;
		}

		if ( ! header_footer_experiment_active() ) {
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
 * Helper function to decide whether to output the header template.
 *
 * @return bool
 */
function get_header_display(): bool {
	$is_editor = isset( $_GET['elementor-preview'] );

	return (
		$is_editor
		|| elementor_get_setting( 'vonsheezy_header_logo_display' )
		|| elementor_get_setting( 'vonsheezy_header_tagline_display' )
		|| elementor_get_setting( 'vonsheezy_header_menu_display' )
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
		|| elementor_get_setting( 'vonsheezy_footer_logo_display' )
		|| elementor_get_setting( 'vonsheezy_footer_tagline_display' )
		|| elementor_get_setting( 'vonsheezy_footer_menu_display' )
		|| elementor_get_setting( 'vonsheezy_footer_copyright_display' )
	);
}

/**
 * Add Holy Vonsheezy theme Header & Footer to Experiments.
 */
add_action(
	'elementor/experiments/default-features-registered',
	function ( \Elementor\Core\Experiments\Manager $experiments_manager ) {
		$experiments_manager->add_feature(
			array(
				'name'           => 'holy-vonsheezy-theme-header-footer',
				'title'          => esc_html__( 'HolyVonsheezy Theme Header & Footer', 'holy-vonsheezy' ),
				'description'    => sprintf(
					'%1$s <a href="%2$s" target="_blank">%3$s</a>',
					esc_html__( 'Customize and style the builtin HolyVonsheezy Theme’s cross-site header & footer from the Elementor "Site Settings" panel.', 'holy-vonsheezy' ),
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

/**
 * Helper function to check if Header & Footer Experiment is Active/Inactive.
 *
 * @return true
 */
function header_footer_experiment_active(): bool {
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
