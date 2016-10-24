<?php

class Ed_Related_Ajax {

    public static function cat_ids( $postID ) {

        $terms = get_the_terms( $postID, 'category' );

        $cats_id = array();

        foreach ($terms as $term) {
            $cats_id[] = $term->term_id;
        }

        return $cats_id;

    }

    public static function ed_rp_load_more() {

        $plugin_option = get_option( 'ed_rp' );

        $id = $_POST[ 'id' ];
        $paged = $_POST[ 'paged' ]+1;

        $posts_num = ( $plugin_option[ 'ed_rp_count' ] ) ? $plugin_option[ 'ed_rp_count' ] : 2;

        $args = array(
            'post_status' => 'publish',
            'posts_per_page' => $posts_num,
            'category__in' => self::cat_ids( $id ),
            'post__not_in' => array( $id ),
            'paged' => $paged
        );

        $more_posts = new WP_Query( $args );

        $ed_rp_col = ( isset( $plugin_option[ 'ed_rp_layout' ] ) ) ? $plugin_option[ 'ed_rp_layout' ] : 2;

        $i = 1;
        if( $more_posts->have_posts() ) :

            while( $more_posts->have_posts() ) :
                $more_posts->the_post();

                /**
                 * Check if post thumbnail enabled and if there's a post thumbnail on it's post
                 */
                if( $plugin_option[ 'ed_rp_show_thumb' ] == true ) {
                    if( has_post_thumbnail() ) {
                        $thumbnail = wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), 'relatedPostThumbnail' );
                    }
                }

                $more = sprintf(
                    __( '<div class="eder-col-%1$d">%2$s<h4><a href="%3$s">%4$s</a></h4>%5$s<a href="%6$s">%7$s</a></div>', 'edrp' ),
                    esc_attr( $ed_rp_col ),
                    $thumbnail,
                    esc_url( get_permalink() ),
                    esc_html( get_the_title() ),
                    get_the_excerpt(),
                    esc_url( get_permalink() ),
                    __( '<br>Read more &rarr;' )
                );

                echo $more;

                if( $i % $plugin_option[ 'ed_rp_layout' ] == 0 ) {
                    echo '<div class="clear"></div>';
                }

                $i++;

            endwhile;

            wp_reset_query();

        endif;

        die();

    }

}