<?php
/**
 * Plugin Name: Ed Slider
 * Plugin URI: http://wordpress.org/
 * Description: A simple image slide for wordpress.
 * Author: @alpezed
 * Author URI: http://wordpress.org/
 * Version: 0.0.1
 * License: GPLv2.
 **/

//Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

define('ESL_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('ESL_PLUGIN_BASENAME', plugin_basename(__FILE__));

//Include files
require_once( ESL_PLUGIN_DIR_PATH . 'includes/class-ed-slider.php' );
require_once( ESL_PLUGIN_DIR_PATH . 'includes/ed-sanitizer.php' );
require_once( ESL_PLUGIN_DIR_PATH . 'includes/ed-slider-cpt.php' );
require_once( ESL_PLUGIN_DIR_PATH . 'includes/ed-shortcode.php' );

function load_edSlider() {
    if( is_admin() ) {
        $ed_slider = new Ed_Slider();
    }
}
load_edSlider();