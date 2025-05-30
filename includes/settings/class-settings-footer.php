<?php

namespace HolyVonsheezy\Includes\Settings;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Settings_Footer extends Tab_Base {

	public function get_id() {
		return 'vonsheezy-settings-footer';
	}

	public function get_title() {
		return esc_html__( 'HolyVonsheezy Theme Footer', 'holy-vonsheezy' );
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_help_url() {
		return '';
	}

	public function get_group() {
		return 'theme-style';
	}

	protected function register_tab_controls() {

		$this->start_controls_section(
			'vonsheezy_footer_section',
			array(
				'tab'   => 'vonsheezy-settings-footer',
				'label' => esc_html__( 'Footer', 'holy-vonsheezy' ),
			)
		);

		$this->add_control(
			'vonsheezy_footer_logo_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Site Logo', 'holy-vonsheezy' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'holy-vonsheezy' ),
				'label_off' => esc_html__( 'Hide', 'holy-vonsheezy' ),
				'selector'  => '.site-footer .site-branding',
			)
		);

		$this->add_control(
			'vonsheezy_footer_tagline_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Tagline', 'holy-vonsheezy' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'holy-vonsheezy' ),
				'label_off' => esc_html__( 'Hide', 'holy-vonsheezy' ),
				'selector'  => '.site-footer .site-description',
			)
		);

		$this->add_control(
			'vonsheezy_footer_menu_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Menu', 'holy-vonsheezy' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'holy-vonsheezy' ),
				'label_off' => esc_html__( 'Hide', 'holy-vonsheezy' ),
				'selector'  => '.site-footer .site-navigation',
			)
		);

		$this->add_control(
			'vonsheezy_footer_copyright_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Copyright', 'holy-vonsheezy' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'holy-vonsheezy' ),
				'label_off' => esc_html__( 'Hide', 'holy-vonsheezy' ),
				'selector'  => '.site-footer .copyright',
			)
		);

		$this->add_control(
			'vonsheezy_footer_disable_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens the theme settings page. */
					__( 'Note: Hiding all the elements, only hides them visually. To disable them completely go to <a href="%s">Theme Settings</a> .', 'holy-vonsheezy' ),
					admin_url( 'themes.php?page=holy-vonsheezy-theme-settings' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'vonsheezy_footer_logo_display'      => '',
					'vonsheezy_footer_tagline_display'   => '',
					'vonsheezy_footer_menu_display'      => '',
					'vonsheezy_footer_copyright_display' => '',
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_layout',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Layout', 'holy-vonsheezy' ),
				'options'   => array(
					'default'  => esc_html__( 'Default', 'holy-vonsheezy' ),
					'inverted' => esc_html__( 'Inverted', 'holy-vonsheezy' ),
					'stacked'  => esc_html__( 'Centered', 'holy-vonsheezy' ),
				),
				'selector'  => '.site-footer',
				'default'   => 'default',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'vonsheezy_footer_width',
			array(
				'type'     => Controls_Manager::SELECT,
				'label'    => esc_html__( 'Width', 'holy-vonsheezy' ),
				'options'  => array(
					'boxed'      => esc_html__( 'Boxed', 'holy-vonsheezy' ),
					'full-width' => esc_html__( 'Full Width', 'holy-vonsheezy' ),
				),
				'selector' => '.site-footer',
				'default'  => 'boxed',
			)
		);

		$this->add_responsive_control(
			'vonsheezy_footer_custom_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Content Width', 'holy-vonsheezy' ),
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
					'vonsheezy_footer_width' => 'boxed',
				),
				'selectors'  => array(
					'.site-footer .footer-inner' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				),
			)
		);

		$this->add_responsive_control(
			'vonsheezy_footer_gap',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Gap', 'holy-vonsheezy' ),
				'size_units' => array( '%', 'px', 'em ', 'rem', 'vw', 'custom' ),
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
					'.site-footer' => 'padding-inline-end: {{SIZE}}{{UNIT}}; padding-inline-start: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'vonsheezy_footer_layout!' => 'stacked',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'vonsheezy_footer_background',
				'label'    => esc_html__( 'Background', 'holy-vonsheezy' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '.site-footer',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_footer_logo_section',
			array(
				'tab'       => 'vonsheezy-settings-footer',
				'label'     => esc_html__( 'Site Logo', 'holy-vonsheezy' ),
				'condition' => array(
					'vonsheezy_footer_logo_display!' => '',
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_logo_type',
			array(
				'label'              => esc_html__( 'Type', 'holy-vonsheezy' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'logo',
				'options'            => array(
					'logo'  => esc_html__( 'Logo', 'holy-vonsheezy' ),
					'title' => esc_html__( 'Title', 'holy-vonsheezy' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'vonsheezy_footer_logo_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Logo Width', 'holy-vonsheezy' ),
				'description' => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s logo', 'holy-vonsheezy' ),
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
					'vonsheezy_footer_logo_display' => 'yes',
					'vonsheezy_footer_logo_type'    => 'logo',
				),
				'selectors'   => array(
					'.site-footer .site-branding .site-logo img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'holy-vonsheezy' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'vonsheezy_footer_logo_display' => 'yes',
					'vonsheezy_footer_logo_type'    => 'title',
				),
				'selectors' => array(
					'.site-footer h4.site-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'vonsheezy_footer_title_typography',
				'label'     => esc_html__( 'Typography', 'holy-vonsheezy' ),
				'condition' => array(
					'vonsheezy_footer_logo_display' => 'yes',
					'vonsheezy_footer_logo_type'    => 'title',
				),
				'selector'  => '.site-footer h4.site-title',

			)
		);

		$this->add_control(
			'vonsheezy_footer_title_link',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title', 'holy-vonsheezy' ),
					"javascript:\$e.route('panel/global/settings-site-identity')"
				),
				'content_classes' => 'elementor-control-field-description',
				'condition'       => array(
					'vonsheezy_footer_logo_display' => 'yes',
					'vonsheezy_footer_logo_type'    => 'title',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_footer_tagline',
			array(
				'tab'       => 'vonsheezy-settings-footer',
				'label'     => esc_html__( 'Tagline', 'holy-vonsheezy' ),
				'condition' => array(
					'vonsheezy_footer_tagline_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_tagline_color',
			array(
				'label'     => esc_html__( 'Text Color', 'holy-vonsheezy' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'vonsheezy_footer_tagline_display' => 'yes',
				),
				'selectors' => array(
					'.site-footer .site-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'vonsheezy_footer_tagline_typography',
				'label'     => esc_html__( 'Typography', 'holy-vonsheezy' ),
				'condition' => array(
					'vonsheezy_footer_tagline_display' => 'yes',
				),
				'selector'  => '.site-footer .site-description',
			)
		);

		$this->add_control(
			'vonsheezy_footer_tagline_link',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens Elementor's "Site Identity" panel. */
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s tagline', 'holy-vonsheezy' ),
					"javascript:\$e.route('panel/global/settings-site-identity')"
				),
				'content_classes' => 'elementor-control-field-description',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_footer_menu_tab',
			array(
				'tab'       => 'vonsheezy-settings-footer',
				'label'     => esc_html__( 'Menu', 'holy-vonsheezy' ),
				'condition' => array(
					'vonsheezy_footer_menu_display' => 'yes',
				),
			)
		);

		$available_menus = wp_get_nav_menus();

		$menus = array( '0' => esc_html__( '— Select a Menu —', 'holy-vonsheezy' ) );
		foreach ( $available_menus as $available_menu ) {
			$menus[ $available_menu->term_id ] = $available_menu->name;
		}

		if ( 1 === count( $menus ) ) {
			$this->add_control(
				'vonsheezy_footer_menu_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . esc_html__( 'There are no menus in your site.', 'holy-vonsheezy' ) . '</strong><br>' . sprintf( __( 'Go to <a href="%s" target="_blank">Menus screen</a> to create one.', 'holy-vonsheezy' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		} else {
			$this->add_control(
				'vonsheezy_footer_menu',
				array(
					'label'       => esc_html__( 'Menu', 'holy-vonsheezy' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => $menus,
					'default'     => array_keys( $menus )[0],
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'holy-vonsheezy' ), admin_url( 'nav-menus.php' ) ),
				)
			);

			$this->add_control(
				'vonsheezy_footer_menu_warning',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Changes will be reflected in the preview only after the page reloads.', 'holy-vonsheezy' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'vonsheezy_footer_menu_color',
				array(
					'label'     => esc_html__( 'Color', 'holy-vonsheezy' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'footer .footer-inner .site-navigation a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'vonsheezy_footer_menu_typography',
					'label'    => esc_html__( 'Typography', 'holy-vonsheezy' ),
					'selector' => 'footer .footer-inner .site-navigation a',
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_footer_copyright_section',
			array(
				'tab'        => 'vonsheezy-settings-footer',
				'label'      => esc_html__( 'Copyright', 'holy-vonsheezy' ),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'vonsheezy_footer_copyright_display',
							'operator' => '=',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_copyright_text',
			array(
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'All rights reserved', 'holy-vonsheezy' ),
			)
		);

		$this->add_control(
			'vonsheezy_footer_copyright_color',
			array(
				'label'     => esc_html__( 'Text Color', 'holy-vonsheezy' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'vonsheezy_footer_copyright_display' => 'yes',
				),
				'selectors' => array(
					'.site-footer .copyright p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'vonsheezy_footer_copyright_typography',
				'label'     => esc_html__( 'Typography', 'holy-vonsheezy' ),
				'condition' => array(
					'vonsheezy_footer_copyright_display' => 'yes',
				),
				'selector'  => '.site-footer .copyright p',
			)
		);

		$this->end_controls_section();
	}

	public function on_save( $data ) {
		// Save chosen footer menu to the WP settings.
		if ( isset( $data['settings']['vonsheezy_footer_menu'] ) ) {
			$menu_id             = $data['settings']['vonsheezy_footer_menu'];
			$locations           = get_theme_mod( 'nav_menu_locations' );
			$locations['menu-2'] = (int) $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	public function get_additional_tab_content() {
		$content_template = '
			<div class="holy-vonsheezy elementor-nerd-box">
				<img src="%1$s" class="elementor-nerd-box-icon" alt="%2$s">
				<p class="elementor-nerd-box-title">%3$s</p>
				<p class="elementor-nerd-box-message">%4$s</p>
				<a class="elementor-nerd-box-link elementor-button" target="_blank" href="%5$s">%6$s</a>
			</div>';

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return sprintf(
				$content_template,
				get_template_directory_uri() . '/assets/images/go-pro.svg',
				esc_attr__( 'Get Elementor Pro', 'holy-vonsheezy' ),
				esc_html__( 'Create a custom footer with multiple options', 'holy-vonsheezy' ),
				esc_html__( 'Upgrade to Elementor Pro and enjoy free design and many more features', 'holy-vonsheezy' ),
				'https://go.elementor.com/holy-vonsheezy-theme-footer/',
				esc_html__( 'Upgrade', 'holy-vonsheezy' )
			);
		} else {
			return sprintf(
				$content_template,
				get_template_directory_uri() . '/assets/images/go-pro.svg',
				esc_attr__( 'Elementor Pro', 'holy-vonsheezy' ),
				esc_html__( 'Create a custom footer with the Theme Builder', 'holy-vonsheezy' ),
				esc_html__( 'With the Theme Builder you can jump directly into each part of your site', 'holy-vonsheezy' ),
				get_admin_url( null, 'admin.php?page=elementor-app#/site-editor/templates/footer' ),
				esc_html__( 'Create Footer', 'holy-vonsheezy' )
			);
		}
	}
}
