<?php
/**
 * Ed Related Posts Uninstall
 *
 * Uninstalling Ed Related Posts delete plugin option
 *
 * @author Ed A.
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'ed_rp' );