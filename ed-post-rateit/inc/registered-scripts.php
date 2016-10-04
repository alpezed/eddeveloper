<?php

function er_register_equeueue_scripts() {
    //Styles
    wp_register_style( 'er-rateit', plugins_url( '../styles/rateit.css', __FILE__ ) );
    wp_register_style( 'er-style', plugins_url( '../styles/style.css', __FILE__ ) );

    //Scripts
    wp_register_script( 'er-rateit-min', plugins_url( '../scripts/jquery.rateit.min.js', __FILE__ ), array(), '09102016', true );
    wp_register_script( 'er-script', plugins_url( '../scripts/script.js', __FILE__ ), array(), '09102016', true );
}