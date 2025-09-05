<?php
/**
 * Theme functions and definitions
 *
 * @package CreatorMode
 */

declare(strict_types=1);

namespace CreatorMode;

use CreatorMode\Includes\Customizer\Customizer;
use CreatorMode\Includes\Elementor_Integration;
use CreatorMode\Includes\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require_once get_template_directory() . '/vendor/autoload.php';
} else {
	return;
}
define( 'CREATOR_MODE_VERSION', '1.0.1' );
define( 'CREATOR_MODE_THEME_URL', get_template_directory_uri() );
define( 'CREATOR_MODE_ASSETS_URL', get_template_directory_uri() . '/assets' );
define( 'CREATOR_MODE_ASSETS_PATH', get_template_directory() . '/assets' );
define( 'CREATOR_MODE_STYLE_URL', CREATOR_MODE_ASSETS_URL . '/css' );

Theme::instance();
Elementor_Integration::instance();
Customizer::instance();

