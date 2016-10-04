<?php
/*
 * A Widget To Display a Testimonial on Widget Area
 *
 * */

class EdWp_Testimonial_Widget extends WP_Widget {

    function __construct() {
        // Instantiate the parent object
        parent::__construct( false, 'Ed Testimonials' );
    }

    function widget( $args, $instance ) {
        // Widget output
        extract($args);

        $title              = apply_filters( 'widget_title', $instance['title'] );
        $testimonial_num    = $instance['testimonial_num'];
        $show_image = isset( $instance['show_image'] ) ? $instance['show_image'] : false;

        // Output HTML Code to show on frontend
        echo $before_widget;
        echo $before_title;
        echo $title;

            $tm_args = array(
                'post_type'              => 'testimonial',
                'post_status'            => 'publish',
                'posts_per_page'         => $testimonial_num,
                'orderby'                => 'menu_order',
                'order'                  => 'DESC',
                'no_found_rows'          => true,
                'update_post_term_cache' => false
            );

            $tm_query = new WP_Query( $tm_args );

            if( $tm_query->have_posts() ) :
                echo '<div id="testimonial_carousel">';
                while( $tm_query->have_posts() ) : $tm_query->the_post();
                    ?>
                    <div class="testimonial_box">
                        <div class="tm_thumb">
                            <?php
                            $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
                            $test = 0;
                            //$tm_img = get_post_meta( get_the_ID(), 'custom_image_data', true );
                            if( $show_image ) {
                                if( has_post_thumbnail()  ) {
                                    echo '<a href=" ' . get_permalink() . ' "><img src=" ' . $image[0] . ' " width="60" height="60"></a>';
                                } else {
                                    echo '<img src=" http://placehold.it/150x150" width="60" height="60">';
                                }
                            }
                            ?>
                        </div>
                        <?php the_title( sprintf( '<h3 class="tm_title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
                        <blockquote><?php the_excerpt(); ?></blockquote>
                        <cite>- <?php echo get_post_meta( get_the_ID(), 'client_info', true ); ?></cite>
                    </div>
                    <?php
                endwhile;
                echo '</div>';
            endif;

        echo $after_title;
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
        $instance = $old_instance;

        $instance['title']              = strip_tags( $new_instance['title'] );
        $instance['testimonial_num']    = strip_tags( $new_instance['testimonial_num'] );
        $instance['show_image']         = $new_instance['show_image'];

        return $instance;
    }

    function form( $instance ) {
        // Output admin widget options form

        $title = esc_attr( $instance['title'] );
        $testimonial_num = esc_attr( ( $instance['testimonial_num'] ) ? $instance['testimonial_num'] : 1 );
        $show_image = $instance['show_image'];

        ?>
            <div class="widget-content">
                <p>
                    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>">
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id( 'testimonial_num' ); ?>"><?php _e( 'Show Testimonial:' ); ?></label>
                    <input class="tiny-text" id="<?php echo $this->get_field_id( 'testimonial_num' ); ?>" name="<?php echo $this->get_field_name( 'testimonial_num' ); ?>" type="number" step="1" min="1" value="<?php echo $testimonial_num; ?>" size="3">
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Show Testimonial Image:' ); ?></label>
                    <input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>"  <?php checked( $show_image, "on" ); ?>>
                </p>
            </div>
        <?php
    }
}

function edwpwidget_register_widgets() {
    register_widget( 'EdWp_Testimonial_Widget' );
}

add_action( 'widgets_init', 'edwpwidget_register_widgets' );
