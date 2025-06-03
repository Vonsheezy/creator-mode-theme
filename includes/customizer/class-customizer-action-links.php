<?php
/**
 * Class Holy_VonsheezyCustomizer_Action_Links
 *
 * Extends WP_Customize_Control for rendering customizer action links.
 *
 * @package HolyCanvas\Includes\Customizer\Customizer_Action_Links
 */

namespace HolyCanvas\Includes\Customizer;

use HolyCanvas\Elementor_Integration;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class representing a custom WordPress Customizer control to display action links for managing Elementor-related settings.
 *
 * Extends the WP_Customize_Control class to integrate custom logic for rendering specific links and actions
 * based on the availability and status of the Elementor plugin and associated features.
 */
class Customizer_Action_Links extends \WP_Customize_Control {

	/**
	 * Content property of WP_Customize_Control.
	 *
	 * @var string
	 */
	public string $content = '';

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overridden without having to rewrite the wrapper.
	 *
	 * @return void
	 */
	public function render_content() {
		$this->print_customizer_action_links();

		if ( isset( $this->description ) ) {
			echo '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>';
		}
	}

	/**
	 * Print customizer action links.
	 *
	 * @return void
	 */
	private function print_customizer_action_links() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$action_link_data  = array();
		$action_link_type  = '';
		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins['elementor/elementor.php'] ) ) {
			$action_link_type = 'install-elementor';
		} elseif ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			$action_link_type = 'activate-elementor';
		} elseif ( ! Elementor_Integration::header_footer_experiment_active() ) {
			$action_link_type = 'activate-header-footer-experiment';
		} else {
			$action_link_type = 'style-header-footer';
		}

		switch ( $action_link_type ) {
			case 'install-elementor':
				$action_link_data = array(
					'image'   => get_template_directory_uri() . '/assets/images/elementor.svg',
					'alt'     => esc_attr__( 'Elementor', 'holy-vonsheezy' ),
					'title'   => esc_html__( 'Install Elementor', 'holy-vonsheezy' ),
					'message' => esc_html__( 'Create cross-site header & footer using Elementor.', 'holy-vonsheezy' ),
					'button'  => esc_html__( 'Install Elementor', 'holy-vonsheezy' ),
					'link'    => wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => 'elementor',
							),
							admin_url( 'update.php' )
						),
						'install-plugin_elementor'
					),
				);
				break;
			case 'activate-elementor':
				$action_link_data = array(
					'image'   => get_template_directory_uri() . '/assets/images/elementor.svg',
					'alt'     => esc_attr__( 'Elementor', 'holy-vonsheezy' ),
					'title'   => esc_html__( 'Activate Elementor', 'holy-vonsheezy' ),
					'message' => esc_html__( 'Create cross-site header & footer using Elementor.', 'holy-vonsheezy' ),
					'button'  => esc_html__( 'Activate Elementor', 'holy-vonsheezy' ),
					'link'    => wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' ),
				);
				break;
			case 'activate-header-footer-experiment':
				$action_link_data = array(
					'image'   => get_template_directory_uri() . '/assets/images/elementor.svg',
					'alt'     => esc_attr__( 'Elementor', 'holy-vonsheezy' ),
					'title'   => esc_html__( 'Style using Elementor', 'holy-vonsheezy' ),
					'message' => esc_html__( 'Design your cross-site header & footer from Elementor’s "Site Settings" panel.', 'holy-vonsheezy' ),
					'button'  => esc_html__( 'Activate header & footer experiment', 'holy-vonsheezy' ),
					'link'    => wp_nonce_url( 'admin.php?page=elementor#tab-experiments' ),
				);
				break;
			case 'style-header-footer':
				$action_link_data = array(
					'image'   => get_template_directory_uri() . '/assets/images/elementor.svg',
					'alt'     => esc_attr__( 'Elementor', 'holy-vonsheezy' ),
					'title'   => esc_html__( 'Style cross-site header & footer', 'holy-vonsheezy' ),
					'message' => esc_html__( 'Customize your cross-site header & footer from Elementor’s "Site Settings" panel.', 'holy-vonsheezy' ),
					'button'  => esc_html__( 'Start Designing', 'holy-vonsheezy' ),
					'link'    => wp_nonce_url( 'post.php?post=' . get_option( 'elementor_active_kit' ) . '&action=elementor' ),
				);
				break;
		}

		$customizer_content = $this->get_customizer_action_links_html( $action_link_data );

		echo wp_kses_post( $customizer_content );
	}

	/**
	 * Generate the HTML for customizer action links.
	 *
	 * This method constructs HTML markup for a customizer-related action link component,
	 * provided the required data fields are set. If the necessary data is missing,
	 * the method returns without generating HTML.
	 *
	 * @param array $data {
	 *     An associative array containing the necessary data for generating the HTML.
	 *
	 * @type string $image URL for the image to be displayed.
	 * @type string $alt Alt text for the image.
	 * @type string $title Title text displayed in the action links.
	 * @type string $message Message text displayed in the action links.
	 * @type string $link URL for the primary link.
	 * @type string $button The button text to be displayed.
	 * }
	 * @return string|null The generated HTML string for the action links, or null if the required data is incomplete.
	 */
	private function get_customizer_action_links_html( array $data ): ?string {
		if (
			empty( $data )
			|| ! isset( $data['image'] )
			|| ! isset( $data['alt'] )
			|| ! isset( $data['title'] )
			|| ! isset( $data['message'] )
			|| ! isset( $data['link'] )
			|| ! isset( $data['button'] )
		) {
			return '';
		}

		return sprintf(
			'<div class="vonsheezy-action-links">
				<img src="%1$s" alt="%2$s">
				<p class="vonsheezy-action-links-title">%3$s</p>
				<p class="vonsheezy-action-links-message">%4$s</p>
				<a class="button button-primary" target="_blank" href="%5$s">%6$s</a>
			</div>',
			$data['image'],
			$data['alt'],
			$data['title'],
			$data['message'],
			$data['link'],
			$data['button'],
		);
	}
}
