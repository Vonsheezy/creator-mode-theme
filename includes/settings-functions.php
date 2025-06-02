<?php
/**
 * A file to register and init settings.
 *
 * @package HolyVonsheezy\Includes\Customizer
 */

namespace HolyVonsheezy\Includes\Customizer;

use Error;
use HolyVonsheezy\Elementor_Integration;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'admin_menu', __NAMESPACE__ . '\vonsheezy_elementor_settings_page' );
add_action( 'init', __NAMESPACE__ . '\vonsheezy_elementor_tweak_settings', 0 );

/**
 * Register the theme settings page.
 */
function vonsheezy_elementor_settings_page() {

	$menu_hook = '';

	$menu_hook = add_theme_page(
		esc_html__( 'HolyVonsheezy Theme Settings', 'holy-vonsheezy' ),
		esc_html__( 'Theme Settings', 'holy-vonsheezy' ),
		'manage_options',
		'holy-vonsheezy-theme-settings',
		'vonsheezy_elementor_settings_page_render'
	);

	add_action(
		'load-' . $menu_hook,
		function () {
			add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\vonsheezy_elementor_settings_page_scripts', 10 );
		}
	);
}

/**
 * Register settings page scripts.
 *
 * @throws Error Throw an error when no assets are found.
 * @return void
 */
function vonsheezy_elementor_settings_page_scripts() {

	$dir        = get_template_directory() . '/assets/js';
	$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$handle     = 'vonsheezy-admin';
	$asset_path = "$dir/vonsheezy-admin.asset.php";
	$asset_url  = get_template_directory_uri() . '/assets/js';

	if ( ! file_exists( $asset_path ) ) {
		throw new Error( 'You need to run `npm run build` for the "holy-vonsheezy-theme" first.' );
	}
	$script_asset = require $asset_path;

	wp_enqueue_script(
		$handle,
		"$asset_url/$handle$suffix.js",
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_set_script_translations( $handle, 'holy-vonsheezy' );

	wp_enqueue_style(
		$handle,
		"$asset_url/$handle$suffix.css",
		array( 'wp-components' ),
		$script_asset['version']
	);

	$plugins = get_plugins();

	if ( ! isset( $plugins['elementor/elementor.php'] ) ) {
		$action_link_type = 'install-elementor';
		$action_link_url  = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'install-plugin',
					'plugin' => 'elementor',
				),
				admin_url( 'update.php' )
			),
			'install-plugin_elementor'
		);
	} elseif ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		$action_link_type = 'activate-elementor';
		$action_link_url  = wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' );
	} elseif ( Elementor_Integration::header_footer_experiment_active() && ! Elementor_Integration::header_footer_experiment_active() ) {
		$action_link_type = 'activate-header-footer-experiment';
		$action_link_url  = wp_nonce_url( 'admin.php?page=elementor#tab-experiments' );
	} elseif ( Elementor_Integration::header_footer_experiment_active() ) {
		$action_link_type = 'style-header-footer';
		$action_link_url  = wp_nonce_url( 'post.php?post=' . get_option( 'elementor_active_kit' ) . '&action=elementor' );
	} else {
		$action_link_type = '';
		$action_link_url  = '';
	}

	wp_localize_script(
		$handle,
		'vonsheezyAdminData',
		array(
			'actionLinkType'       => $action_link_type,
			'actionLinkURL'        => $action_link_url,
			'templateDirectoryURI' => get_template_directory_uri(),
		)
	);
}

/**
 * Render settings page wrapper element.
 *
 * @return void
 */
function vonsheezy_elementor_settings_page_render() {
	?>
	<div id="holy-vonsheezy-settings"></div>
	<?php
}

/**
 * Theme tweaks & settings.
 *
 * @return void
 */
function vonsheezy_elementor_tweak_settings() {

	$settings_group = 'vonsheezy_elementor_settings';

	$settings = array(
		'DESCRIPTION_META_TAG' => '_description_meta_tag',
		'SKIP_LINK'            => '_skip_link',
		'HEADER_FOOTER'        => '_header_footer',
		'PAGE_TITLE'           => '_page_title',
		'HELLO_STYLE'          => '_vonsheezy_style',
		'HELLO_THEME'          => '_vonsheezy_theme',
	);

	vonsheezy_elementor_register_settings( $settings_group, $settings );
	vonsheezy_elementor_render_tweaks( $settings_group, $settings );
}

/**
 * Registers Elementor settings for a given settings group.
 *
 * @param string $settings_group A unique identifier for the group of settings.
 * @param array  $settings An associative array of settings, where each key represents a specific setting and its value is used as part of the setting name.
 *
 * @return void
 */
function vonsheezy_elementor_register_settings( string $settings_group, array $settings ) {

	foreach ( $settings as $setting_key => $setting_value ) {
		register_setting(
			$settings_group,
			$settings_group . $setting_value,
			array(
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			)
		);
	}
}

/**
 * Executes a tweak callback if the specified setting is enabled and the callback is callable.
 *
 * @param string   $setting The option name representing the specific tweak to check.
 * @param callable $tweak_callback The callback function to execute if the setting is enabled.
 *
 * @return void
 */
function vonsheezy_elementor_do_tweak( string $setting, callable $tweak_callback ) {

	$option = get_option( $setting );
	if ( isset( $option ) && ( 'true' === $option ) && is_callable( $tweak_callback ) ) {
		$tweak_callback();
	}
}

/**
 * Applies a set of tweaks to Elementor based on the provided settings group and settings.
 *
 * @param string $settings_group A unique identifier for the group of settings.
 * @param array  $settings An associative array of settings, where each key corresponds to a specific tweak and its associated configuration.
 *
 * @return void
 */
function vonsheezy_elementor_render_tweaks( string $settings_group, array $settings ) {

	vonsheezy_elementor_do_tweak(
		$settings_group . $settings['DESCRIPTION_META_TAG'],
		function () {
			remove_action( 'wp_head', 'vonsheezy_elementor_add_description_meta_tag' );
		}
	);

	vonsheezy_elementor_do_tweak(
		$settings_group . $settings['SKIP_LINK'],
		function () {
			add_filter( 'vonsheezy_elementor_enable_skip_link', '__return_false' );
		}
	);

	vonsheezy_elementor_do_tweak(
		$settings_group . $settings['HEADER_FOOTER'],
		function () {
			add_filter( 'vonsheezy_elementor_header_footer', '__return_false' );
		}
	);

	vonsheezy_elementor_do_tweak(
		$settings_group . $settings['PAGE_TITLE'],
		function () {
			add_filter( 'vonsheezy_elementor_page_title', '__return_false' );
		}
	);

	vonsheezy_elementor_do_tweak(
		$settings_group . $settings['HELLO_STYLE'],
		function () {
			add_filter( 'vonsheezy_elementor_enqueue_style', '__return_false' );
		}
	);

	vonsheezy_elementor_do_tweak(
		$settings_group . $settings['HELLO_THEME'],
		function () {
			add_filter( 'vonsheezy_elementor_enqueue_theme_style', '__return_false' );
		}
	);
}
