<?php
/**
 * Ed Related Posts Settings
 *
 * @author Ed A.
 */
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Ed_Rp_Admin_Settings' ) ) :

    class Ed_Rp_Admin_Settings {

        public static function show_message()
        {
            if ( isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == 1 ) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>Settings saved.</strong></p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
            }
        }

        public static function admin_view_settings()
        {
            $edrp = get_option( 'ed_rp' );

            include 'views/html-admin-settings.php';
        }

        public static function admin_setting_save()
        {
            if( ! current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You don\'t have permission to edit on this page.' ) );
            }

            check_admin_referer( 'ed_rp_options_verify' );

            $edrp = get_option( 'ed_rp' );

            $edrp_opts = array(
                'ed_rp_title'      => sanitize_text_field( $_POST[ 'ed_rp_title' ] ),
                'ed_rp_display'    => $_POST[ 'ed_rp_display' ],
                'ed_rp_show_thumb' => $_POST[ 'ed_rp_show_thumb' ],
                'ed_rp_layout'     => absint( $_POST[ 'ed_rp_layout' ] ),
                'ed_rp_count'      => absint( $_POST[ 'ed_rp_count' ] )
            );

            $edrp_option = array_merge( $edrp, $edrp_opts );

            update_option( 'ed_rp', $edrp_option );

            wp_redirect( admin_url( 'options-general.php?page=ed-rp-options&status=1' ) );
        }

    }

endif;