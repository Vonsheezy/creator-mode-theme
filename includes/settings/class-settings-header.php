<?php
/**
 * Class Settings_Header
 * This class is responsible for defining the custom header settings for the Holy Canvas theme
 * specifically for Elementor plugin customizations.
 *
 * Extends the Elementor Tab_Base class to create a customizable header settings tab.
 *
 * @package CreatorMode\Includes\Settings\Settings_Header
 */

declare(strict_types=1);


namespace CreatorMode\Includes\Settings;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Settings_Header
 *
 * Extends Tab_Base to manage the settings for the theme header configuration in the Holy Canvas theme.
 * Provides controls for customizing header layout, visibility of elements, and other styling options.
 */
class Settings_Header extends Tab_Base {

	/**
	 * Retrieves the unique identifier.
	 *
	 * @return string The unique identifier.
	 */
	public function get_id(): string {
		return 'vonsheezy-settings-header';
	}

	/**
	 * Retrieves the title of the Holy Canvas Theme Header.
	 *
	 * @return string Returns the localized and escaped theme header title.
	 */
	public function get_title(): string {
		return esc_html__( 'CreatorMode Theme Header', 'creator-mode' );
	}

	/**
	 * Retrieves the icon identifier associated with this element or component.
	 *
	 * @return string The icon identifier as a string.
	 */
	public function get_icon(): string {
		return 'eicon-header';
	}

	/**
	 * Retrieves the URL for the help or support documentation.
	 *
	 * @return string The help URL as a string.
	 */
	public function get_help_url(): string {
		return '';
	}

	/**
	 * Retrieves the group identifier associated with this element or component.
	 *
	 * @return string The group identifier as a string.
	 */
	public function get_group(): string {
		return 'theme-style';
	}

	/**
	 * Registers controls for configuring the tab settings related to the header section.
	 * These controls allow users to customize various elements of the header, including
	 * logo display, tagline, layout, background, width, and responsive settings.
	 *
	 * @return void This method does not return any value.
	 */
	protected function register_tab_controls() {
		$this->start_controls_section(
			'vonsheezy_header_section',
			array(
				'tab'   => 'vonsheezy-settings-header',
				'label' => esc_html__( 'Header', 'creator-mode' ),
			)
		);

		$this->add_control(
			'vonsheezy_header_logo_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Site Logo', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
			)
		);

		$this->add_control(
			'vonsheezy_header_tagline_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Tagline', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
			)
		);

		$this->add_control(
			'vonsheezy_header_menu_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Menu', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
			)
		);

		$this->add_control(
			'vonsheezy_header_disable_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens the theme settings page. */
					__( 'Note: Hiding all the elements, only hides them visually. To disable them completely go to <a href="%s">Theme Settings</a> .', 'creator-mode' ),
					admin_url( 'themes.php?page=creator-mode-theme-settings' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'vonsheezy_header_logo_display'    => '',
					'vonsheezy_header_tagline_display' => '',
					'vonsheezy_header_menu_display'    => '',
				),
			)
		);

		$this->add_control(
			'vonsheezy_header_layout',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Layout', 'creator-mode' ),
				'options'   => array(
					'default'  => esc_html__( 'Default', 'creator-mode' ),
					'inverted' => esc_html__( 'Inverted', 'creator-mode' ),
					'stacked'  => esc_html__( 'Centered', 'creator-mode' ),
				),
				'selector'  => '.site-header',
				'default'   => 'default',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'vonsheezy_header_width',
			array(
				'type'     => Controls_Manager::SELECT,
				'label'    => esc_html__( 'Width', 'creator-mode' ),
				'options'  => array(
					'boxed'      => esc_html__( 'Boxed', 'creator-mode' ),
					'full-width' => esc_html__( 'Full Width', 'creator-mode' ),
				),
				'selector' => '.site-header',
				'default'  => 'boxed',
			)
		);

		$this->add_responsive_control(
			'vonsheezy_header_custom_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Content Width', 'creator-mode' ),
				'size_units' => array( '%', 'px', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px'  => array(
						'max' => 2000,
					),
					'em'  => array(
						'max' => 100,
					),
					'rem' => array(
						'max' => 100,
					),
				),
				'condition'  => array(
					'vonsheezy_header_width' => 'boxed',
				),
				'selectors'  => array(
					'.site-header .header-inner' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				),
			)
		);

		$this->add_responsive_control(
			'vonsheezy_header_gap',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Gap', 'creator-mode' ),
				'size_units' => array( '%', 'px', 'em ', 'rem', 'vw', 'custom' ),
				'default'    => array(
					'size' => '0',
				),
				'range'      => array(
					'px'  => array(
						'max' => 100,
					),
					'em'  => array(
						'max' => 5,
					),
					'rem' => array(
						'max' => 5,
					),
				),
				'selectors'  => array(
					'.site-header' => 'padding-inline-end: {{SIZE}}{{UNIT}}; padding-inline-start: {{SIZE}}{{UNIT}}',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'vonsheezy_header_layout',
							'operator' => '!=',
							'value'    => 'stacked',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'vonsheezy_header_background',
				'label'    => esc_html__( 'Background', 'creator-mode' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '.site-header',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_header_logo_section',
			array(
				'tab'        => 'vonsheezy-settings-header',
				'label'      => esc_html__( 'Site Logo', 'creator-mode' ),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'vonsheezy_header_logo_display',
							'operator' => '=',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'vonsheezy_header_logo_type',
			array(
				'label'              => esc_html__( 'Type', 'creator-mode' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => ( has_custom_logo() ? 'logo' : 'title' ),
				'options'            => array(
					'logo'  => esc_html__( 'Logo', 'creator-mode' ),
					'title' => esc_html__( 'Title', 'creator-mode' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'vonsheezy_header_logo_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Logo Width', 'creator-mode' ),
				'description' => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s logo', 'creator-mode' ),
					"javascript:\$e.route('panel/global/settings-site-identity')"
				),
				'size_units'  => array( '%', 'px', 'em', 'rem', 'vw', 'custom' ),
				'range'       => array(
					'px'  => array(
						'max' => 1000,
					),
					'em'  => array(
						'max' => 100,
					),
					'rem' => array(
						'max' => 100,
					),
				),
				'condition'   => array(
					'vonsheezy_header_logo_display' => 'yes',
					'vonsheezy_header_logo_type'    => 'logo',
				),
				'selectors'   => array(
					'.site-header .site-branding .site-logo img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'vonsheezy_header_title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'creator-mode' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'vonsheezy_header_logo_display' => 'yes',
					'vonsheezy_header_logo_type'    => 'title',
				),
				'selectors' => array(
					'.site-header h1.site-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'vonsheezy_header_title_typography',
				'label'       => esc_html__( 'Typography', 'creator-mode' ),
				'description' => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title', 'creator-mode' ),
					"javascript:\$e.route('panel/global/settings-site-identity')"
				),
				'condition'   => array(
					'vonsheezy_header_logo_display' => 'yes',
					'vonsheezy_header_logo_type'    => 'title',
				),
				'selector'    => '.site-header h1.site-title',
			)
		);

		$this->add_control(
			'vonsheezy_header_title_link',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title', 'creator-mode' ),
					"javascript:\$e.route('panel/global/settings-site-identity')"
				),
				'content_classes' => 'elementor-control-field-description',
				'condition'       => array(
					'vonsheezy_header_logo_display' => 'yes',
					'vonsheezy_header_logo_type'    => 'title',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_header_tagline',
			array(
				'tab'        => 'vonsheezy-settings-header',
				'label'      => esc_html__( 'Tagline', 'creator-mode' ),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'vonsheezy_header_tagline_display',
							'operator' => '=',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'vonsheezy_header_tagline_color',
			array(
				'label'     => esc_html__( 'Text Color', 'creator-mode' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'vonsheezy_header_tagline_display' => 'yes',
				),
				'selectors' => array(
					'.site-header .site-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'vonsheezy_header_tagline_typography',
				'label'     => esc_html__( 'Typography', 'creator-mode' ),
				'condition' => array(
					'vonsheezy_header_tagline_display' => 'yes',
				),
				'selector'  => '.site-header .site-description',
			)
		);

		$this->add_control(
			'vonsheezy_header_tagline_link',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s tagline', 'creator-mode' ),
					"javascript:\$e.route('panel/global/settings-site-identity')"
				),
				'content_classes' => 'elementor-control-field-description',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_header_menu_tab',
			array(
				'tab'        => 'vonsheezy-settings-header',
				'label'      => esc_html__( 'Menu', 'creator-mode' ),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'vonsheezy_header_menu_display',
							'operator' => '=',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$available_menus = wp_get_nav_menus();

		$menus = array( '0' => esc_html__( '— Select a Menu —', 'creator-mode' ) );
		foreach ( $available_menus as $available_menu ) {
			$menus[ $available_menu->term_id ] = $available_menu->name;
		}

		if ( 1 === count( $menus ) ) {
			$this->add_control(
				'vonsheezy_header_menu_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s: A link to edit navigation menus. */
					'raw'             => '<strong>' . esc_html__( 'There are no menus in your site.', 'creator-mode' ) . '</strong><br>' . sprintf( __( 'Go to <a href="%s" target="_blank">Menus screen</a> to create one.', 'creator-mode' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		} else {
			$this->add_control(
				'vonsheezy_header_menu',
				array(
					'label'       => esc_html__( 'Menu', 'creator-mode' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => $menus,
					'default'     => array_keys( $menus )[0],
					/* translators: %s: A link to edit navigation menus. */
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'creator-mode' ), admin_url( 'nav-menus.php' ) ),
				)
			);

			$this->add_control(
				'vonsheezy_header_menu_warning',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Changes will be reflected in the preview only after the page reloads.', 'creator-mode' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'vonsheezy_header_menu_layout',
				array(
					'label'              => esc_html__( 'Menu Layout', 'creator-mode' ),
					'type'               => Controls_Manager::SELECT,
					'default'            => 'horizontal',
					'options'            => array(
						'horizontal' => esc_html__( 'Horizontal', 'creator-mode' ),
						'dropdown'   => esc_html__( 'Dropdown', 'creator-mode' ),
					),
					'frontend_available' => true,
				)
			);

			$breakpoints = Responsive::get_breakpoints();

			$this->add_control(
				'vonsheezy_header_menu_dropdown',
				array(
					'label'     => esc_html__( 'Breakpoint', 'creator-mode' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'tablet',
					'options'   => array(
						/* translators: %d: Breakpoint number. */
						'mobile' => sprintf( esc_html__( 'Mobile (< %dpx)', 'creator-mode' ), $breakpoints['md'] ),
						/* translators: %d: Breakpoint number. */
						'tablet' => sprintf( esc_html__( 'Tablet (< %dpx)', 'creator-mode' ), $breakpoints['lg'] ),
						'none'   => esc_html__( 'None', 'creator-mode' ),
					),
					'selector'  => '.site-header',
					'condition' => array(
						'vonsheezy_header_menu_layout!' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'vonsheezy_header_menu_color',
				array(
					'label'     => esc_html__( 'Color', 'creator-mode' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'vonsheezy_header_menu_display' => 'yes',
					),
					'selectors' => array(
						'.site-header .site-navigation ul.menu li a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'vonsheezy_header_menu_toggle_color',
				array(
					'label'     => esc_html__( 'Toggle Color', 'creator-mode' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'vonsheezy_header_menu_display' => 'yes',
					),
					'selectors' => array(
						'.site-header .site-navigation-toggle i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'vonsheezy_header_menu_typography',
					'label'     => esc_html__( 'Typography', 'creator-mode' ),
					'condition' => array(
						'vonsheezy_header_menu_display' => 'yes',
					),
					'selector'  => '.site-header .site-navigation .menu li',
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Handles the save operation for the chosen header menu and updates WordPress settings accordingly.
	 *
	 * @param array $data An associative array containing the settings data, including the header menu identifier.
	 * @return void
	 */
	public function on_save( $data ) {
		// Save the chosen header menu to the WP settings.
		if ( isset( $data['settings']['vonsheezy_header_menu'] ) ) {
			$menu_id             = $data['settings']['vonsheezy_header_menu'];
			$locations           = get_theme_mod( 'nav_menu_locations' );
			$locations['menu-1'] = (int) $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/**
	 * Generates and retrieves the additional tab content for the Elementor editor.
	 *
	 * The method returns a formatted HTML content block, varying based on the defined state
	 * of the Elementor Pro plugin. If Elementor Pro is not active, a prompt to upgrade is displayed.
	 * When Elementor Pro is active, it offers links for creating a custom header using the Theme Builder.
	 *
	 * @return string The HTML content for the additional tab, prompting an upgrade or providing access to Theme Builder features.
	 */
	public function get_additional_tab_content(): string {
		$content_template = '
			<div class="creator-mode elementor-nerd-box">
				<img src="%1$s" class="elementor-nerd-box-icon" alt="%2$s">
				<p class="elementor-nerd-box-title">%3$s</p>
				<p class="elementor-nerd-box-message">%4$s</p>
				<a class="elementor-nerd-box-link elementor-button" target="_blank" href="%5$s">%6$s</a>
			</div>';

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return sprintf(
				$content_template,
				get_template_directory_uri() . '/assets/images/go-pro.svg',
				esc_attr__( 'Get Elementor Pro', 'creator-mode' ),
				esc_html__( 'Create a custom header with multiple options', 'creator-mode' ),
				esc_html__( 'Upgrade to Elementor Pro and enjoy free design and many more features', 'creator-mode' ),
				'https://go.elementor.com/creator-mode-theme-header/',
				esc_html__( 'Upgrade', 'creator-mode' )
			);
		} else {
			return sprintf(
				$content_template,
				get_template_directory_uri() . '/assets/images/go-pro.svg',
				esc_attr__( 'Elementor Pro', 'creator-mode' ),
				esc_html__( 'Create a custom header with the Theme Builder', 'creator-mode' ),
				esc_html__( 'With the Theme Builder you can jump directly into each part of your site', 'creator-mode' ),
				get_admin_url( null, 'admin.php?page=elementor-app#/site-editor/templates/header' ),
				esc_html__( 'Create Header', 'creator-mode' )
			);
		}
	}
}
