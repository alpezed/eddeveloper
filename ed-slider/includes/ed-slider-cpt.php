<?php
function register_ed_slider() {
    $labels = array(
        'name' => 'Sliders',
        'singular_name' => 'Slider',
        'menu_name' => 'Slides',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Slider',
        'edit' => 'Edit',
        'edit_item' => 'Edit Slider',
        'new_item' => 'New Slider',
        'view' => 'View Slider',
        'view_item' => 'View Slider',
        'search_term' => 'Search Sliders',
        'parent' => 'Parent Slider',
        'not_found' => 'No Sliders found',
        'not_found_in_trash' => 'No Sliders in Trash'
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'ed-slider' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'slider' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array( 'title' )
    );

    register_post_type('ed_slider', $args);
}
add_action('init', 'register_ed_slider');

//Adding new custom column on WP Table List
function esl_set_custom_edit_slider_columns( $columns ) {

    $slide_column = array(
        'shortcode' => __( 'Slide Shortcode' )
    );

    //unset( $columns['date'] );

    return array_merge( $columns, $slide_column );

}
add_filter( 'manage_edit-ed_slider_columns', 'esl_set_custom_edit_slider_columns' );

//Adding fields on WP Table List
function esl_custom_slider_column( $column, $post_id ) {
    if( $column === 'shortcode' ) {
        echo '<pre>[ed_slider id="'.$post_id.'"]</pre>';
    }
}
add_action( 'manage_ed_slider_posts_custom_column', 'esl_custom_slider_column', 10, 2 );