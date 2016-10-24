<?php
/**
 * Plugin Name: Ed Related Posts
 * Plugin URI: http://wordpress.org/
 * Description: Display a list of related posts on your website.
 * Author: @alpezed
 * Author URI: http://wordpress.org/
 * Version: 1.1.0
 * License: GPLv2.
 **/

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Ed_Related_Posts Class.
 */
if( ! class_exists( 'Ed_Related_Posts' ) ) :

    class Ed_Related_Posts {

        protected $plugin_option;

        public function __construct()
        {
            $this->define_constants();
            $this->include_files();
            $this->init_hooks();
        }

        private function init_hooks()
        {
            //Actions and  Filters
            register_activation_hook( __FILE__, array( $this, 'ed_rp_activation' ) );
            register_deactivation_hook( __FILE__, array( $this, 'ed_rp_deactivation' ) );
            add_action( 'init', array( $this, 'ed_rp_enable' ) );
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'ed_rp_enqueue_scripts' ), 9999 );
            add_action( 'wp_ajax_edrp_load_more', array( $this, 'load_more' ) );
            add_action( 'wp_ajax_nopriv_edrp_load_more', array( $this, 'load_more' ) );
        }

        private function define_constants()
        {
            /**
             * Define Ed RP Constants
             */
            $this->define( 'ED_RP_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
        }

        private function define( $name, $value )
        {
            if( ! defined( $name ) )
                define( $name, $value );
        }

        private function include_files()
        {
            /**
             * Include Files
             */
            require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-menu.php';
            require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-settings.php';
            require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-activator.php';
            require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-deactivator.php';
            require_once ED_RP_PLUGIN_DIR_PATH . '/inc/class-ed-rp-ajax.php';
        }



        public function ed_rp_enqueue_scripts()
        {
            /**
             * enqueue styles
             */
            wp_enqueue_style( 'ed-rp-style', plugins_url( 'css/ed-related-posts.css', __FILE__ ) );

            /**
             * enqueue scripts
             */
            wp_enqueue_script( 'ed-rp-script', plugins_url( 'js/ed-related-posts.js', __FILE__ ), array(), '10102016', true );

            /**
             * localize scripts
             */
            wp_localize_script( 'ed-rp-script', 'ED_RP_SCRIPT', array(
                'ajax_url' => admin_url( 'admin-ajax.php' )
            ) );
        }

        public function ed_rp_enable()
        {
            $this->plugin_option = get_option( 'ed_rp' );

            if( $this->plugin_option[ 'ed_rp_display' ] ) {
                add_filter( 'the_content', array( $this, 'ed_rp_display_func' ) );
            }

            add_image_size( 'relatedPostThumbnail', 400, 300, true );
        }

        public static function ed_rp_display()
        {
            $plugin_option = get_option( 'ed_rp' );

            /**
             * Related Posts Columns Layout
             */
            $ed_rp_col = ( isset( $plugin_option[ 'ed_rp_layout' ] ) ) ? $plugin_option[ 'ed_rp_layout' ] : 2;

            /**
             * Check if post thumbnail enabled and if there's a post thumbnail on it's post
             */
            if( $plugin_option[ 'ed_rp_show_thumb' ] == true ) {
                if( has_post_thumbnail() ) {
                    $edrp_thumbnail = wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), 'relatedPostThumbnail' );
                }
            }

            $content = sprintf(
                                __( '<div class="eder-col-%1$d">%2$s<h4><a href="%3$s">%4$s</a></h4>%5$s<a href="%6$s">%7$s</a></div>', 'edrp' ),
                                esc_attr( $ed_rp_col ),
                                $edrp_thumbnail,
                                esc_url( get_permalink() ),
                                esc_html( get_the_title() ),
                                get_the_excerpt(),
                                esc_url( get_permalink() ),
                                __( '<br>Read more &rarr;' )
                            );

            $content = $content;

            return $content;
        }

        public static function cats_id() {
            $terms = get_the_category();

            $cats = array();
            foreach ( $terms as $term ) {
                $cats[] = $term->term_id;
            }

            return $cats;
        }

        public function ed_rp_display_func( $content )
        {
            $this->plugin_option = get_option( 'ed_rp' );

            $id = get_the_ID();

            if( ! is_singular( 'post' ) ) {
                return $content;
            }

            $posts_num = ( $this->plugin_option[ 'ed_rp_count' ] ) ? $this->plugin_option[ 'ed_rp_count' ] : 2;

            $args = array(
                'post_status' => 'publish',
                'posts_per_page' => (int) $posts_num,
                'category__in' => self::cats_id(),
                'post__not_in' => array( $id ),
                //'orderby' => 'rand'
            );

            $related_posts = new WP_Query( $args );

            /**
             * Related Posts Title
             */
            $ed_rp_title = ( ! empty( $this->plugin_option[ 'ed_rp_title' ] ) ) ? $this->plugin_option[ 'ed_rp_title' ] : 'Related Posts';

            $i = 1;
            if( $related_posts->have_posts() ) :
                $content .= '
                <div class="ed-rp">
                    <h1>' . $ed_rp_title . '</h1>';

                    while( $related_posts->have_posts() ) :
                        $related_posts->the_post();

                        $content .= self::ed_rp_display();

                        if( $i % $this->plugin_option[ 'ed_rp_layout' ] == 0 ) {
                            $content .= '<div class="clear"></div>';
                        }

                        $i++;
                    endwhile;

                $content .= '</div>';

                $content .= '<button class="load-more" data-id="'.$id.'" data-page="1">Show More Related Posts</button>';

                wp_reset_query();

            endif;

            return $content;
        }

        public function load_more() {
            Ed_Related_Ajax::ed_rp_load_more();
        }

        private function ed_rp_activation()
        {
            Ed_Rp_Activator::activate();
        }

        private function ed_rp_deactivation()
        {
            Ed_Rp_Deactivator::deactivate();
        }

    }

endif;

return new Ed_Related_Posts();