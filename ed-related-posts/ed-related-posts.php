<?php
/**
 * Plugin Name: Ed Related Posts
 * Plugin URI: http://wordpress.org/
 * Description: Display a list of related posts on your website.
 * Author: @alpezed
 * Author URI: http://wordpress.org/
 * Version: 1.0.0
 * License: GPLv2.
 **/

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Constant
 */
define( 'ED_RP_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );

/**
 * Include Files
 */
require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-menu.php';
require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-settings.php';

/**
 * Ed_Related_Posts Class.
 */
if( ! class_exists( 'Ed_Related_Posts' ) ) :

    class Ed_Related_Posts {

        public function __construct()
        {
            //Actions and  Filter
            register_activation_hook( __FILE__, array( $this, 'ed_rp_activation' ) );
            add_action( 'init', array( $this, 'ed_rp_enable' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'ed_rp_enqueue_scripts' ), 9999 );
        }

        // Enqueue Scripts
        public function ed_rp_enqueue_scripts()
        {
            /**
             * enqueue css file
             */
            wp_enqueue_style( 'ed-rp-style', plugins_url( 'css/ed-related-posts.css', __FILE__ ) );

            /**
             * enqueue scripts
             */
            wp_enqueue_script( 'ed-rp-script', plugins_url( 'js/ed-related-posts.js', __FILE__ ), array(), '10102016', true );
        }

        public function ed_rp_enable()
        {
            $edrp = get_option( 'ed_rp' );

            if( $edrp[ 'ed_rp_display' ] == 'true' ) {
                add_filter( 'the_content', array( $this, 'ed_rp_display_func' ) );
            }

            add_image_size( 'relatedPostThumbnail', 400, 300, false );
        }

        public function ed_rp_display()
        {
            $edrp = get_option( 'ed_rp' );

            /**
             * Related Posts Columns Layout
             */
            $ed_rp_col = ( isset( $edrp[ 'ed_rp_layout' ] ) ) ? $edrp[ 'ed_rp_layout' ] : 2;

            /**
             * Check if post thumbnail enabled and if there's a post thumbnail on it's post
             */
            if( $edrp[ 'ed_rp_show_thumb' ] == "true" ) {
                if( has_post_thumbnail() ) {
                    $edrp_thumbnail = wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), 'relatedPostThumbnail' );
                }
            }

            $edrp_content = sprintf(
                                __( '<div class="eder-col-%1$d">%2$s<h4><a href="%3$s">%4$s</a></h4>%5$s<a href="%6$s">%7$s</a></div>', 'edrp' ),
                                esc_attr( $ed_rp_col ),
                                $edrp_thumbnail,
                                esc_url( get_permalink() ),
                                esc_html( get_the_title() ),
                                get_the_excerpt(),
                                esc_url( get_permalink() ),
                                __( '<br>Read more &rarr;' )
                            );

            $content = $edrp_content;

            return $content;
        }

        public function cats_id() {
            $terms = get_the_category();

            $cats = array();
            foreach ( $terms as $term ) {
                $cats[] = $term->term_id;
            }

            return $cats;
        }

        public function ed_rp_display_func( $content )
        {

            $edrp = get_option( 'ed_rp' );

            $id = get_the_ID();

            if( ! is_singular( 'post' ) ) {
                return $content;
            }

            $posts_num = ( $edrp[ 'ed_rp_count' ] ) ? $edrp[ 'ed_rp_count' ] : 2;

            $args = array(
                'post_status' => 'publish',
                'posts_per_page' => (int) $posts_num,
                'category__in' => $this->cats_id(),
                'post__not_in' => array( $id ),
                'orderby' => 'rand'
            );

            $related_posts = new WP_Query( $args );

            /**
             * Related Posts Title
             */
            $ed_rp_title = ( ! empty( $edrp[ 'ed_rp_title' ] ) ) ? $ed_rp_title = $edrp[ 'ed_rp_title' ] : 'Related Posts';

            $i = 1;
            if( $related_posts->have_posts() ) :
                $content .= '
                <div class="ed-rp">
                    <h1>' . $ed_rp_title . '</h1>';

                    while( $related_posts->have_posts() ) :
                        $related_posts->the_post();

                        $content .= $this->ed_rp_display();

                        if( $i % $edrp[ 'ed_rp_layout' ] == 0 ) {
                            $content .= '<div class="clear"></div>';
                        }

                        $i++;

                    endwhile;

                $content .= '</div>';
                wp_reset_query();
            endif;

            return $content;

        }

        public function ed_rp_activation()
        {
            global $wp_version;

            if( version_compare( $wp_version, '4.6.1', '<' ) ) {
                wp_die( __( 'This plugin requires WordPress version 4.6.1 or newer,
please run your WordPress upgrade to utilize this plugin.', 'edrp' ) );
            }

        }

    }

endif;

return new Ed_Related_Posts();