<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}post_ratings" );
$wpdb->query( "DELETE FROM `{$wpdb->prefix}postmeta` WHERE `meta_key`='_post_rating_data'" );
