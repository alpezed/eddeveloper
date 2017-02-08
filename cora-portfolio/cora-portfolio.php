<?php
/**
 * Plugin Name: CORA Portfolio
 * Plugin URI: http://cogitaxis.com/
 * Description: Simple Portfolio Plugin.
 * Author: @ruth
 * Author URI: http://cogitaxis.com/
 * Version: 1.0.0
 * License: GPLv2.
 **/

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * CONSTANT
 */
define('CPP_DIR_PATH', plugin_dir_path( __FILE__ ));
define('CPP_PLUGIN_BASENAME', plugin_basename( __FILE__ ));
define( 'CPPMB_DIR', trailingslashit( CPP_DIR_PATH . 'vendor/meta-box' ) );

require_once CPP_DIR_PATH . 'inc/post_types.php';
require_once CPP_DIR_PATH . 'inc/shortcodes.php';

require_once CPPMB_DIR . 'meta-box.php';
require_once CPP_DIR_PATH . 'inc/meta-box_settings.php';

function cpp_enqueue_scripts() {
	wp_register_style('motioncss', plugins_url( 'css/motioncss.css', __FILE__ ), array(), '11172016', 'all');
    wp_register_style('motioncss-widgets', plugins_url( 'css/motioncss-widgets.css', __FILE__ ), array(), '11172016', 'all');
	wp_register_style('coracss', plugins_url( 'css/cora-portfolio.css', __FILE__ ), array(), '11172016', 'all');
    wp_enqueue_style('motioncss');
    wp_enqueue_style('motioncss-widgets');
	wp_enqueue_style('coracss');

    wp_enqueue_script('jquery-easing', 'http://cdn.raincross.com/wp-content/themes/Corsa/js/jquery.easing.min.js', array('jquery'), null, true);
    wp_enqueue_script('us_widgets', plugins_url( 'js/us.widgets.js', __FILE__ ), array('jquery'), '', true);
}
add_action( 'wp_enqueue_scripts', 'cpp_enqueue_scripts' );
