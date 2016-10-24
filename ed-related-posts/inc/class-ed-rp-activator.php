<?php
/**
 * Fired during plugin activation
 *
 * @author Ed A.
 */

class Ed_Rp_Activator {

    public static function activate ()
    {
        global $wp_version;

        if( version_compare( $wp_version, '4.6.1', '<' ) ) {
            wp_die( __( 'This plugin requires WordPress version 4.6.1 or newer, please run your WordPress upgrade to utilize this plugin.', 'edrp' ) );
        }
    }

}