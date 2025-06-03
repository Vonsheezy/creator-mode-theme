<?php
declare(strict_types=1);

/**
 * Theme functions and definitions
 *
 * @package HolyCanvas
 */
namespace HolyCanvas;

use HolyCanvas\Includes\Customizer\Customizer;
use HolyCanvas\Includes\Elementor_Integration;
use HolyCanvas\Includes\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.0.2' );

if(file_exists(get_template_directory() . '/vendor/autoload.php')) {
    require_once get_template_directory() . '/vendor/autoload.php';
}

Theme::instance();
Elementor_Integration::instance();
Customizer::instance();

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}



// Admin notice.
if ( is_admin() ) {
	//require get_template_directory() . '/includes/admin-functions.php';
}
