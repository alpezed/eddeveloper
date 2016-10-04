<?php

function er_activate_plugin() {
    global $wp_version, $wpdb;

    if( version_compare( $wp_version, '4.6.1', '<' ) ) {
        wp_die( __( 'This plugin requires WordPress version 4.6.1 or newer,
please run your WordPress upgrade to utilize this plugin.', 'er_rateit' ) );
    }

    $createTableRateit = "
        CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "post_ratings` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `post_id` bigint(20) UNSIGNED NOT NULL,
          `rating` float UNSIGNED NOT NULL,
          `user_ip` varchar(32) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB " . $wpdb->get_charset_collate() . " AUTO_INCREMENT=1 ;
    ";

    require_once ( ABSPATH . '/wp-admin/includes/upgrade.php' );
    dbDelta( $createTableRateit );

}
