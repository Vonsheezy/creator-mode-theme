<?php
/**
 * Theme class.
 *
 * @package CreatorMode
 */

declare(strict_types=1);

namespace CreatorMode\Includes;

use Error;

/**
 * Handles theme functionality and settings integration.
 */
class Theme {
	/**
	 * The single instance of the class.
	 *
	 * @var Theme|null
	 */
	private static ?Theme $instance = null;
	/**
	 * Theme constructor.
	 *
	 * @throws Error Throw an error when no assets are found.
	 * @return void
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_head', array( $this, 'add_description_meta_tag' ) );
		add_action( 'init', array( $this, 'tweak_settings' ), 0 );
		add_action( 'admin_menu', array( $this, 'settings_page' ) );
	}

	/**
	 * Register the theme settings page.
	 */
	public function settings_page() {

		$menu_hook = '';

		$menu_hook = add_theme_page(
			esc_html__( 'CreatorMode Theme Settings', 'creator-mode' ),
			esc_html__( 'Theme Settings', 'creator-mode' ),
			'manage_options',
			'creator-mode-theme-settings',
			array( $this, 'settings_page_render' )
		);

		add_action(
			'load-' . $menu_hook,
			function () {
				add_action( 'admin_enqueue_scripts', array( $this, 'settings_page_scripts' ), 10 );
			}
		);
	}

	/**
	 * Render settings page wrapper element.
	 *
	 * @return void
	 */
	public function settings_page_render() {
		?>
		<div id="creator-mode-settings"></div>
		<?php
	}

	/**
	 * Register settings page scripts.
	 *
	 * @throws Error Throw an error when no assets are found.
	 * @return void
	 */
	public function settings_page_scripts() {

		$dir        = CREATIVE_MODE_ASSETS_PATH . '/js';
		$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$handle     = 'creative-mode-home-app';
		$asset_path = "$dir/$handle.asset.php";
		$asset_url  = get_template_directory_uri() . '/assets/js';

		if ( ! file_exists( $asset_path ) ) {
			throw new Error( 'You need to run `npm run build` for the "creator-mode-theme" first.' );
		}
		$script_asset = require $asset_path;

		wp_enqueue_script(
			$handle,
			"$asset_url/$handle$suffix.js",
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations( $handle, 'creator-mode' );

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
	 * Theme tweaks & settings.
	 *
	 * @return void
	 */
	public function tweak_settings() {

		$settings_group = 'vonsheezy_elementor_settings';

		$settings = array(
			'DESCRIPTION_META_TAG' => '_description_meta_tag',
			'SKIP_LINK'            => '_skip_link',
			'HEADER_FOOTER'        => '_header_footer',
			'PAGE_TITLE'           => '_page_title',
			'HELLO_STYLE'          => '_vonsheezy_style',
			'HELLO_THEME'          => '_vonsheezy_theme',
		);

		$this->register_settings( $settings_group, $settings );
		$this->render_tweaks( $settings_group, $settings );
	}

	/**
	 * Registers Elementor settings for a given settings group.
	 *
	 * @param string $settings_group A unique identifier for the group of settings.
	 * @param array  $settings An associative array of settings, where each key represents a specific setting and its value is used as part of the setting name.
	 *
	 * @return void
	 */
	public function register_settings( string $settings_group, array $settings ) {

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
	 * Applies a set of tweaks to Elementor based on the provided settings group and settings.
	 *
	 * @param string $settings_group A unique identifier for the group of settings.
	 * @param array  $settings An associative array of settings, where each key corresponds to a specific tweak and its associated configuration.
	 *
	 * @return void
	 */
	public function render_tweaks( string $settings_group, array $settings ) {

		$this->do_tweak(
			$settings_group . $settings['DESCRIPTION_META_TAG'],
			function () {
				remove_action( 'wp_head', 'vonsheezy_elementor_add_description_meta_tag' );
			}
		);

		$this->do_tweak(
			$settings_group . $settings['SKIP_LINK'],
			function () {
				add_filter( 'vonsheezy_elementor_enable_skip_link', '__return_false' );
			}
		);

		$this->do_tweak(
			$settings_group . $settings['HEADER_FOOTER'],
			function () {
				add_filter( 'vonsheezy_elementor_header_footer', '__return_false' );
			}
		);

		$this->do_tweak(
			$settings_group . $settings['PAGE_TITLE'],
			function () {
				add_filter( 'vonsheezy_elementor_page_title', '__return_false' );
			}
		);

		$this->do_tweak(
			$settings_group . $settings['HELLO_STYLE'],
			function () {
				add_filter( 'vonsheezy_elementor_enqueue_style', '__return_false' );
			}
		);

		$this->do_tweak(
			$settings_group . $settings['HELLO_THEME'],
			function () {
				add_filter( 'vonsheezy_elementor_enqueue_theme_style', '__return_false' );
			}
		);
	}

	/**
	 * Executes a tweak callback if the specified setting is enabled and the callback is callable.
	 *
	 * @param string   $setting The option name representing the specific tweak to check.
	 * @param callable $tweak_callback The callback function to execute if the setting is enabled.
	 *
	 * @return void
	 */
	public function do_tweak( string $setting, callable $tweak_callback ) {

		$option = get_option( $setting );
		if ( isset( $option ) && ( 'true' === $option ) && is_callable( $tweak_callback ) ) {
			$tweak_callback();
		}
	}

	/**
	 * Returns the instance of the theme.
	 *
	 * Ensures only one instance of the theme is loaded or can be loaded.
	 *
	 * @return Theme|null
	 */
	public static function instance(): ?Theme {
		if ( null === static::$instance ) {
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
	public function setup() {
		if ( is_admin() ) {
			$this->maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'vonsheezy_elementor_register_menus', true ) ) {
			register_nav_menus( array( 'menu-1' => esc_html__( 'Header', 'creator-mode' ) ) );
			register_nav_menus( array( 'menu-2' => esc_html__( 'Footer', 'creator-mode' ) ) );
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
			 * Add support for responsive embeds.
			 */
			add_theme_support( 'responsive-embeds' );

			/*
			 * Add support for wp block styles.
			 */

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
	 * Adds a skip link to the page.
	 *
	 * @return void
	 */
	public function add_description_meta_tag() {
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

	/**
	 * Applies filter to header footer elements..
	 *
	 * @return mixed|null
	 */
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

	/**
	 * Register assets.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function register_assets() {
		$font_slug          = require CREATIVE_MODE_ASSETS_PATH . '/css/font.asset.php';
		$theme_slug         = require CREATIVE_MODE_ASSETS_PATH . '/css/theme.asset.php';
		$header_footer_slug = require CREATIVE_MODE_ASSETS_PATH . '/css/header-footer.asset.php';

		wp_enqueue_style(
			'creator-mode-fonts',
			CREATIVE_MODE_STYLE_URL . '/font.css',
			$font_slug['dependencies'],
			$font_slug['version']
		);

		if ( apply_filters( 'vonsheezy_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'creator-mode',
				CREATIVE_MODE_THEME_URL . '/style.css',
				array( 'creator-mode-fonts' ),
				CREATIVE_MODE_VERSION
			);
		}

		if ( apply_filters( 'vonsheezy_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'creator-mode-theme-style',
				CREATIVE_MODE_STYLE_URL . '/theme.css',
				array_merge( array( 'creator-mode-fonts' ), $theme_slug['dependencies'] ),
				$theme_slug['version']
			);
		}

		if ( $this->display_header_footer() ) {
			wp_enqueue_style(
				'creator-mode-header-footer',
				CREATIVE_MODE_STYLE_URL . '/header-footer.css',
				array_merge( array( 'creator-mode-fonts' ), $header_footer_slug['dependencies'] ),
				$header_footer_slug['version'],
			);
		}
	}
}