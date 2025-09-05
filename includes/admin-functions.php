<?php
/**
 *  File that contains functions for elementor admin notices
 *
 * @package CreatorMode\Includes
 */

namespace CreatorMode\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Displays an admin notice prompting the user to activate or install the Elementor plugin if it is not currently active or installed.
 *
 * The notice is shown only to users with proper permissions (install or activate plugins) and under specific conditions:
 * - Elementor Pro is not already managing the plugin.
 * - The notice has not been previously dismissed by the user.
 *
 * The notice includes a message about the compatibility of the CreatorMode Theme with the Elementor plugin. It also provides actionable buttons to install or activate Elementor, depending on its installation status. If dismissed, it triggers an AJAX request to mark the notice as viewed.
 *
 * @return void
 */
function creator_mode_fail_load_admin_notice() {
	// Leave to Elementor Pro to manage this.
	if ( function_exists( 'elementor_pro_load_plugin' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	if ( 'true' === get_user_meta( get_current_user_id(), '_creator_mode_install_notice', true ) ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	$installed_plugins = get_plugins();

	$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

	$message = esc_html__( 'The CreatorMode Theme is a lightweight starter theme that works perfectly with the Elementor award-winning site builder plugin.', 'creator-mode' );

	if ( $is_elementor_installed ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message .= ' ' . esc_html__( 'Once you activate the plugin, you are only one click away from building an amazing website.', 'creator-mode' );

		$button_text = esc_html__( 'Activate Elementor', 'creator-mode' );
		$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$message .= ' ' . esc_html__( 'Once you download and activate the plugin, you are only one click away from building an amazing website.', 'creator-mode' );

		$button_text = esc_html__( 'Install Elementor', 'creator-mode' );
		$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
	}

	?>
	<style>
		.notice.creator-mode-notice {
			border: 1px solid #ccd0d4;
			border-inline-start: 4px solid #9b0a46 !important;
			box-shadow: 0 1px 4px rgba(0,0,0,0.15);
			display: flex;
			padding: 0;
		}
		.notice.creator-mode-notice.creator-mode-install-elementor {
			padding: 0;
		}
		.notice.creator-mode-notice .creator-mode-notice-aside {
			display: flex;
			align-items: start;
			justify-content: center;
			padding: 20px 10px;
			background: rgba(215,43,63,0.04);
		}
		.notice.creator-mode-notice .creator-mode-notice-aside img {
			width: 1.5rem;
		}
		.notice.creator-mode-notice .creator-mode-notice-content {
			display: flex;
			flex-direction: column;
			gap: 5px;
			padding: 20px;
			width: 100%;
		}
		.notice.creator-mode-notice .creator-mode-notice-content h3,
		.notice.creator-mode-notice .creator-mode-notice-content p {
			padding: 0;
			margin: 0;
		}
		.notice.creator-mode-notice .creator-mode-information-link {
			align-self: start;
		}
		.notice.creator-mode-notice .creator-mode-install-button {
			align-self: start;
			background-color: #127DB8;
			border-radius: 3px;
			color: #fff;
			text-decoration: none;
			height: auto;
			line-height: 20px;
			padding: 0.4375rem 0.75rem;
			margin-block-start: 15px;
		}
		.notice.creator-mode-notice .creator-mode-install-button:active {
			transform: translateY(1px);
		}
		@media (max-width: 767px) {
			.notice.creator-mode-notice .creator-mode-notice-aside {
				padding: 10px;
			}
			.notice.creator-mode-notice .creator-mode-notice-content {
				gap: 10px;
				padding: 10px;
			}
		}
	</style>
	<script>
		window.addEventListener( 'load', () => {
			const dismissNotice = document.querySelector( '.notice.creator-mode-install-elementor button.notice-dismiss' );
			dismissNotice.addEventListener( 'click', async ( event ) => {
				event.preventDefault();

				var formData = new FormData();
				formData.append( 'action', 'creator_mode_set_admin_notice_viewed' );
				formData.append( 'dismiss_nonce', '<?php echo esc_js( wp_create_nonce( 'creator_mode_dismiss_install_notice' ) ); ?>' );

				await fetch( ajaxurl, { method: 'POST', body: formData } );
			} );
		} );
	</script>
	<div class="notice updated is-dismissible creator-mode-notice creator-mode-install-elementor">
		<div class="creator-mode-notice-aside">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/elementor-notice-icon.svg' ); ?>" alt="<?php echo esc_attr__( 'Get Elementor', 'creator-mode' ); ?>" />
		</div>
		<div class="creator-mode-notice-content">
			<h3><?php echo esc_html__( 'Thanks for installing the CreatorMode Theme!', 'creator-mode' ); ?></h3>
			<p><?php echo esc_html( $message ); ?></p>
			<a class="creator-mode-information-link" href="https://vonsheezy.com/" target="_blank"><?php echo esc_html__( 'Explore Elementor Site Builder Plugin', 'creator-mode' ); ?></a>
			<a class="creator-mode-install-button" href="<?php echo esc_attr( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</div>
	</div>
	<?php
}

/**
 * Set the dismissed admin notice as viewed.
 *
 * @return void
 */
function ajax_creator_mode_set_admin_notice_viewed() {
	check_ajax_referer( 'creator_mode_dismiss_install_notice', 'dismiss_nonce' );

	update_user_meta( get_current_user_id(), '_creator_mode_install_notice', 'true' );
	die;
}
add_action( 'wp_ajax_creator_mode_set_admin_notice_viewed', 'ajax_creator_mode_set_admin_notice_viewed' );

if ( ! did_action( 'elementor/loaded' ) ) {
	add_action( 'admin_notices', __NAMESPACE__ . '\creator_mode_fail_load_admin_notice' );
}
