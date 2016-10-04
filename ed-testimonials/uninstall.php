<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
delete_option( 'edwp_testimonials' );
delete_option( 'widget_edwp_testimonial_widget' );