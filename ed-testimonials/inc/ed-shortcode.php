<?php
/**
 * Testimonial Shortcode Plugin on Pages or Widget
 */

function edwp_testimonial_shorcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'show' => '4'
    ), $atts );

    ob_start();

    $page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 0;

    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'posts_per_page' => $atts['show'],
        'no_found_rows' => true,
        'paged' => $page
    );

    $testimonials_query = new WP_Query( $args );
    ?>
    <div id="testimonial_carousel" class="owl-carousel owl-theme">
        <?php
        if( $testimonials_query->have_posts() ) : while( $testimonials_query->have_posts() ) : $testimonials_query->the_post(); ?>
            <div class="testimonial_box">
                <div class="tm_thumb">
                    <?php
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
                    //$tm_img = get_post_meta( get_the_ID(), 'custom_image_data', true );
                    if( has_post_thumbnail() ) {
                        echo '<a href=" ' . get_permalink() . ' "><img src=" ' . $image[0] . ' " width="80" height="80"></a>';
                    } else {
                        echo '<img src=" http://placehold.it/150x150" width="60" height="60">';
                    }
                    ?>
                </div>
                <?php the_title( sprintf( '<h3 class="tm_title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
                <blockquote><?php the_excerpt(); ?></blockquote>
                <cite>- <?php echo get_post_meta( get_the_ID(), 'client_info', true ); ?></cite>
            </div>
            <?php
        endwhile; endif;
        ?>
    </div>
    <?php
    $content = ob_get_clean();

    return $content;

}
add_shortcode( 'ed-testimonial', 'edwp_testimonial_shorcode' );