<?php

//Shorthand with get_post_meta function
function esl_get_field( $post_id, $field_name ) {

    $slide_meta = get_post_meta($post_id, $field_name, true);

    if( $slide_meta ) {
        $value = $slide_meta;
    }

    return $value;

}