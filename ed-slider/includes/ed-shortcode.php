<?php

function ed_slider_display( $atts, $content = null ) {

    extract(shortcode_atts( array(
        'id' => ''
    ), $atts ));

    ob_start();

    $slide_meta = esl_get_field( $id, '_esl' );

    echo '<div class="container">';

    foreach ($slide_meta as $slide_key => $slide_val) {
        ?>
            <div class="slides">
                <img src="" alt="">
                <h3><?php echo $slide_val['title']; ?></h3>
                <p><?php echo $slide_val['content']; ?></p>
            </div>
        <?php
    }

    echo '</div>';

    return ob_get_clean();

}

add_shortcode( 'ed_slider', 'ed_slider_display' );