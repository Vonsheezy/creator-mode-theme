<?php
/**
 * Theme functions and definitions
 *
 * @package HolyVonsheezy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.0.2' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'vonsheezy_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_setup() {
		if ( is_admin() ) {
			vonsheezy_maybe_update_theme_version_in_db();
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
}
add_action( 'after_setup_theme', 'vonsheezy_elementor_setup' );

/**
 * Updates the theme version stored in the database if it is outdated or not set.
 *
 * This function checks the current theme version saved in the database. If the version
 * does not exist or is older than the defined theme version, it updates the database
 * with the latest theme version.
 *
 * @return void
 */
function vonsheezy_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'vonsheezy_theme_version';
	// The theme version saved in the database.
	$vonsheezy_theme_db_version = get_option( $theme_version_option_name );

	// If the 'vonsheezy_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $vonsheezy_theme_db_version || version_compare( $vonsheezy_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'vonsheezy_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function vonsheezy_elementor_display_header_footer() {
		$vonsheezy_elementor_header_footer = true;

		return apply_filters( 'vonsheezy_elementor_header_footer', $vonsheezy_elementor_header_footer );
	}
}

if ( ! function_exists( 'vonsheezy_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_scripts_styles() {
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

		if ( vonsheezy_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'holy-vonsheezy-header-footer',
				get_template_directory_uri() . '/header-footer' . $min_suffix . '.css',
				array(),
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'vonsheezy_elementor_scripts_styles' );

if ( ! function_exists( 'vonsheezy_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'vonsheezy_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'vonsheezy_elementor_register_elementor_locations' );

if ( ! function_exists( 'vonsheezy_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'vonsheezy_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'vonsheezy_elementor_content_width', 0 );

if ( ! function_exists( 'vonsheezy_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_add_description_meta_tag() {
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
}
add_action( 'wp_head', 'vonsheezy_elementor_add_description_meta_tag' );

// Admin notice.
if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

// Settings page.
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor.
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'vonsheezy_elementor_customizer' ) ) {
	/**
	 * Loads customizer functions for Elementor integration if conditions are met.
	 *
	 * This function checks whether the site is in customize preview mode and if the
	 * Elementor header and footer display logic allows customizer functionality. If both
	 * conditions are satisfied, it includes the necessary customizer-functions file.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! vonsheezy_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'vonsheezy_elementor_customizer' );

if ( ! function_exists( 'vonsheezy_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function vonsheezy_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'vonsheezy_elementor_page_title', 'vonsheezy_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `vonsheezy_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'vonsheezy_elementor_body_open' ) ) {

	/**
	 * Outputs the wp_body_open action hook.
	 *
	 * This function calls the wp_body_open function to trigger the 'wp_body_open'
	 * action hook. It should be called at the very beginning of the body tag in a theme
	 * to allow developers to add content or scripts.
	 *
	 * @return void
	 */
	function vonsheezy_elementor_body_open() {
		wp_body_open();
	}
}
