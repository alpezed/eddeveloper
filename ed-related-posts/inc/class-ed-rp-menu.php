<?php
/**
 * Ed Related Posts Admin
 *
 * @author Ed A.
 */
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Ed_Rp_Admin_Menu' ) ) :

    class Ed_Rp_Admin_Menu {

        /**
         * Hook Action/Filter
         */
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'ed_rp_admin_menu' ) );
            add_action( 'admin_post_ed_rp_save_opts', array( $this, 'ed_rp_save' ) );
        }

        /**
         * Add menu item
         */
        public function ed_rp_admin_menu() {
            add_submenu_page(
                'options-general.php',
                __( 'Ed Related Posts Options' ),
                __( 'Ed Related Posts' ),
                'manage_options',
                'ed-rp-options',
                array( $this, 'ed_related_posts_opt' )
            );
        }

        /**
         * Init Setting Page
        */
        public function ed_related_posts_opt() {
            Ed_Rp_Admin_Settings::admin_view_settings();
        }

        /**
         * Saving Setting Page
         */
        public function ed_rp_save()
        {
            Ed_Rp_Admin_Settings::admin_setting_save();
        }

    }

endif;

return new Ed_Rp_Admin_Menu();