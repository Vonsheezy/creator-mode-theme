<?php
/**
 * Class Settings_Footer
 *
 * Handles the customization options for the footer section of the Holy Canvas theme.
 *
 * @package CreatorMode\Includes\Settings\Settings_Footer
 */

declare(strict_types=1);

namespace CreatorMode\Includes\Settings;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class responsible for managing and customizing the settings for the footer
 * section in the Holy Canvas theme. This class extends the core functionality
 * of the Tab_Base class to provide specific controls for footer-related
 * appearance and behavior.
 */
class Settings_Footer extends Tab_Base {

	/**
	 * Retrieves the unique identifier for the settings footer.
	 *
	 * @return string The unique identifier for the settings footer.
	 */
	public function get_id() {
		return 'creator-mode-settings-footer';
	}

	/**
	 * Retrieves the title for the Holy Canvas theme footer.
	 *
	 * @return string Returns the escaped HTML title of the Holy Canvas theme footer.
	 */
	public function get_title(): string {
		return esc_html__( 'CreatorMode Theme Footer', 'creator-mode' );
	}

	/**
	 * Retrieves the icon associated with the element.
	 *
	 * @return string Returns the icon as a string identifier.
	 */
	public function get_icon(): string {
		return 'eicon-footer';
	}

	/**
	 * Retrieves the help URL associated with the element.
	 *
	 * @return string Returns the help URL as a string.
	 */
	public function get_help_url(): string {
		return '';
	}

	/**
	 * Retrieves the group associated with the element.
	 *
	 * @return string Returns the group as a string identifier.
	 */
	public function get_group() {
		return 'theme-style';
	}

	/**
	 * Registers and configures the controls necessary for customizing the footer tab settings.
	 *
	 * This method defines various UI controls for managing the visibility, layout, content width,
	 * background, and other appearance attributes of the footer section. Controls include toggles
	 * for displaying specific elements (logo, tagline, menu, copyright), layout options, width,
	 * responsive adjustments, and background settings, among others.
	 *
	 * @return void
	 */
	protected function register_tab_controls() {

		$this->start_controls_section(
			'vonsheezy_footer_section',
			array(
				'tab'   => 'vonsheezy-settings-footer',
				'label' => esc_html__( 'Footer', 'creator-mode' ),
			)
		);

		$this->add_control(
			'vonsheezy_footer_logo_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Site Logo', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
				'selector'  => '.site-footer .site-branding',
			)
		);

		$this->add_control(
			'vonsheezy_footer_tagline_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Tagline', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
				'selector'  => '.site-footer .site-description',
			)
		);

		$this->add_control(
			'vonsheezy_footer_menu_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Menu', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
				'selector'  => '.site-footer .site-navigation',
			)
		);

		$this->add_control(
			'vonsheezy_footer_copyright_display',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Copyright', 'creator-mode' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'creator-mode' ),
				'label_off' => esc_html__( 'Hide', 'creator-mode' ),
				'selector'  => '.site-footer .copyright',
			)
		);

		$this->add_control(
			'vonsheezy_footer_disable_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					/* translators: %s: Link that opens the theme settings page. */
					__( 'Note: Hiding all the elements, only hides them visually. To disable them completely go to <a href="%s">Theme Settings</a> .', 'creator-mode' ),
					admin_url( 'themes.php?page=creator-mode-theme-settings' )
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
				'label'     => esc_html__( 'Layout', 'creator-mode' ),
				'options'   => array(
					'default'  => esc_html__( 'Default', 'creator-mode' ),
					'inverted' => esc_html__( 'Inverted', 'creator-mode' ),
					'stacked'  => esc_html__( 'Centered', 'creator-mode' ),
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
				'label'    => esc_html__( 'Width', 'creator-mode' ),
				'options'  => array(
					'boxed'      => esc_html__( 'Boxed', 'creator-mode' ),
					'full-width' => esc_html__( 'Full Width', 'creator-mode' ),
				),
				'selector' => '.site-footer',
				'default'  => 'boxed',
			)
		);

		$this->add_responsive_control(
			'vonsheezy_footer_custom_width',
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
				'label'      => esc_html__( 'Gap', 'creator-mode' ),
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
				'label'    => esc_html__( 'Background', 'creator-mode' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '.site-footer',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_footer_logo_section',
			array(
				'tab'       => 'vonsheezy-settings-footer',
				'label'     => esc_html__( 'Site Logo', 'creator-mode' ),
				'condition' => array(
					'vonsheezy_footer_logo_display!' => '',
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_logo_type',
			array(
				'label'              => esc_html__( 'Type', 'creator-mode' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'logo',
				'options'            => array(
					'logo'  => esc_html__( 'Logo', 'creator-mode' ),
					'title' => esc_html__( 'Title', 'creator-mode' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'vonsheezy_footer_logo_width',
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
				'label'     => esc_html__( 'Text Color', 'creator-mode' ),
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
				'label'     => esc_html__( 'Typography', 'creator-mode' ),
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
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title', 'creator-mode' ),
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
				'label'     => esc_html__( 'Tagline', 'creator-mode' ),
				'condition' => array(
					'vonsheezy_footer_tagline_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'vonsheezy_footer_tagline_color',
			array(
				'label'     => esc_html__( 'Text Color', 'creator-mode' ),
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
				'label'     => esc_html__( 'Typography', 'creator-mode' ),
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
					__( 'Go to <a href="%s">Site Identity</a> to manage your site\'s tagline', 'creator-mode' ),
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
				'label'     => esc_html__( 'Menu', 'creator-mode' ),
				'condition' => array(
					'vonsheezy_footer_menu_display' => 'yes',
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
				'vonsheezy_footer_menu_notice',
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
				'vonsheezy_footer_menu',
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
				'vonsheezy_footer_menu_warning',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Changes will be reflected in the preview only after the page reloads.', 'creator-mode' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'vonsheezy_footer_menu_color',
				array(
					'label'     => esc_html__( 'Color', 'creator-mode' ),
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
					'label'    => esc_html__( 'Typography', 'creator-mode' ),
					'selector' => 'footer .footer-inner .site-navigation a',
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'vonsheezy_footer_copyright_section',
			array(
				'tab'        => 'vonsheezy-settings-footer',
				'label'      => esc_html__( 'Copyright', 'creator-mode' ),
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
				'default' => esc_html__( 'All rights reserved', 'creator-mode' ),
			)
		);

		$this->add_control(
			'vonsheezy_footer_copyright_color',
			array(
				'label'     => esc_html__( 'Text Color', 'creator-mode' ),
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
				'label'     => esc_html__( 'Typography', 'creator-mode' ),
				'condition' => array(
					'vonsheezy_footer_copyright_display' => 'yes',
				),
				'selector'  => '.site-footer .copyright p',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Handles the save action for the footer menu settings.
	 *
	 * @param array $data The data containing settings for the footer menu.
	 * @return void
	 */
	public function on_save( $data ): void {
		// Save chosen footer menu to the WP settings.
		if ( isset( $data['settings']['vonsheezy_footer_menu'] ) ) {
			$menu_id             = $data['settings']['vonsheezy_footer_menu'];
			$locations           = get_theme_mod( 'nav_menu_locations' );
			$locations['menu-2'] = (int) $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/**
	 * Generates additional tab content dynamically based on Elementor Pro version status.
	 *
	 * @return string Returns the formatted HTML content as a string based on the availability of Elementor Pro.
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
				esc_html__( 'Create a custom footer with multiple options', 'creator-mode' ),
				esc_html__( 'Upgrade to Elementor Pro and enjoy free design and many more features', 'creator-mode' ),
				'https://go.elementor.com/creator-mode-theme-footer/',
				esc_html__( 'Upgrade', 'creator-mode' )
			);
		} else {
			return sprintf(
				$content_template,
				get_template_directory_uri() . '/assets/images/go-pro.svg',
				esc_attr__( 'Elementor Pro', 'creator-mode' ),
				esc_html__( 'Create a custom footer with the Theme Builder', 'creator-mode' ),
				esc_html__( 'With the Theme Builder you can jump directly into each part of your site', 'creator-mode' ),
				get_admin_url( null, 'admin.php?page=elementor-app#/site-editor/templates/footer' ),
				esc_html__( 'Create Footer', 'creator-mode' )
			);
		}
	}
}
