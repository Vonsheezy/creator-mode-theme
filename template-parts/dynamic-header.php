<?php
/**
 * The template for displaying header.
 *
 * @package HolyCanvas
 */

use HolyCanvas\Includes\Elementor_Integration;
use function HolyCanvas\Includes\show_or_hide;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! Elementor_Integration::get_header_display() ) {
	return;
}

$is_editor       = isset( $_GET['elementor-preview'] );
$site_name       = get_bloginfo( 'name' );
$tagline         = get_bloginfo( 'description', 'display' );
$header_nav_menu = wp_nav_menu(
	array(
		'theme_location' => 'menu-1',
		'fallback_cb'    => false,
		'echo'           => false,
	)
);
?>
<header id="site-header" class="site-header dynamic-header <?php echo esc_attr( Elementor_Integration::get_header_layout_class() ); ?>">
	<div class="header-inner">
		<div class="site-branding show-<?php echo esc_attr( Elementor_Integration::get_setting( 'vonsheezy_header_logo_type' ) ); ?>">
			<?php if ( has_custom_logo() && ( 'title' !== Elementor_Integration::get_setting( 'vonsheezy_header_logo_type' ) || $is_editor ) ) : ?>
				<div class="site-logo <?php echo esc_attr( Elementor_Integration::show_or_hide( 'vonsheezy_header_logo_display' ) ); ?>">
					<?php the_custom_logo(); ?>
				</div>
				<?php
			endif;

			if ( $site_name && ( 'logo' !== Elementor_Integration::get_setting( 'vonsheezy_header_logo_type' ) || $is_editor ) ) :
				?>
				<h1 class="site-title <?php echo esc_attr( Elementor_Integration::show_or_hide( 'vonsheezy_header_logo_display' ) ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr__( 'Home', 'holy-vonsheezy' ); ?>" rel="home">
						<?php echo esc_html( $site_name ); ?>
					</a>
				</h1>
				<?php
			endif;

			if ( $tagline && ( Elementor_Integration::get_setting( 'vonsheezy_header_tagline_display' ) || $is_editor ) ) :
				?>
				<p class="site-description <?php echo esc_attr( Elementor_Integration::show_or_hide( 'vonsheezy_header_tagline_display' ) ); ?>">
					<?php echo esc_html( $tagline ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $header_nav_menu ) : ?>
			<nav class="site-navigation <?php echo esc_attr( show_or_hide( 'vonsheezy_header_menu_display' ) ); ?>">
				<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu".
				echo $header_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>
			<div class="site-navigation-toggle-holder <?php echo esc_attr( show_or_hide( 'vonsheezy_header_menu_display' ) ); ?>">
				<div class="site-navigation-toggle" role="button" tabindex="0">
					<i class="eicon-menu-bar" aria-hidden="true"></i>
					<span class="screen-reader-text"><?php echo esc_html__( 'Menu', 'holy-vonsheezy' ); ?></span>
				</div>
			</div>
			<nav class="site-navigation-dropdown <?php echo esc_attr( show_or_hide( 'vonsheezy_header_menu_display' ) ); ?>">
				<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu".
				echo $header_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>
		<?php endif; ?>
	</div>
</header>
