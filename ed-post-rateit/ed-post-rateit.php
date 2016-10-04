<?php
/**
 * Plugin Name: Ed's Post Rateit
 * Plugin URI: http://wordpress.org/
 * Description: A simple image slide for wordpress.
 * Author: Ed A.
 * Author URI: http://wordpress.org/
 * Version: 0.0.1
 * License: GPLv2.
 **/

if( !defined( 'ABSPATH' ) ) exit;

//define function
define( 'ER_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );

//include files
require_once ( ER_PLUGIN_DIR_PATH . '/inc/activate.php' );
require_once ( ER_PLUGIN_DIR_PATH . '/inc/registered-scripts.php' );

//Hooks
register_activation_hook( __FILE__, 'er_activate_plugin' );
add_action( 'wp_enqueue_scripts', 'er_equeueue_scripts', 9999 );
add_action( 'admin_enqueue_scripts', 'er_admin_equeueue_scripts' );
add_action( 'init', 'er_register_equeueue_scripts' );
add_filter( 'the_content', 'er_add_rating' );
add_action( 'wp_ajax_er_save_rating_post', 'er_save_rating_post' );
add_action( 'wp_ajax_nopriv_er_save_rating_post', 'er_save_rating_post' );
add_action( 'wp_dashboard_setup', 'er_post_rating_dashboard_widget' );

//Enqueue Scripts
function er_equeueue_scripts() {
    wp_enqueue_style( 'er-rateit' );
    wp_enqueue_style( 'er-style' );

    wp_localize_script( 'er-script', 'er_rating_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'er_rating_security' )
    ) );

    wp_enqueue_script( 'er-rateit-min' );
    wp_enqueue_script( 'er-script' );
}
function er_admin_equeueue_scripts() {
    wp_enqueue_style( 'er-rateit' );
    wp_enqueue_script( 'er-rateit-min' );
}

//Functions
function er_add_rating( $content ) {

    global $post, $wpdb;

    if( ! is_singular( 'post' ) ) {
        return $content;
    }

    $user_ip = $_SERVER['REMOTE_ADDR'];

    $rating_count = $wpdb->get_var( "SELECT COUNT(*) FROM `" . $wpdb->prefix . "post_ratings` 
                                    WHERE post_id='" . $post->ID . "' AND user_ip='" . $user_ip . "'" );

    if( $rating_count > 0 ) {
        $readonly_placeholder = "true";
    } else {
        $readonly_placeholder = "false";
    }

    $rating_data = get_post_meta( $post->ID, '_post_rating_data', true );

    if( isset( $rating_data['rating'] ) ) {
        $rating_val = $rating_data['rating'];
    }

    $content .= '
        <div class="er_rateit">
            <strong>Rating: </strong>
            <div class="rateit" id="post_rating" data-rateit-resetable="false" data-rateit-ispreset="true" data-pid="' . $post->ID . '" 
            data-rateit-value="'.$rating_val.'" data-rateit-readonly="'.$readonly_placeholder.'"></div>
        </div>
    ';

    return $content;

}

function er_save_rating_post() {

    global $wpdb;

    if( ! check_ajax_referer( 'er_rating_security', 'security' ) ) {
        return wp_send_json_error( 'Invalid Nonce' );
    }

    $response = array( 'status' => 1 );
    $post_id = absint( $_POST['pid'] );
    $rating = round( $_POST['rating'], 1 );
    $user_ip = $_SERVER['REMOTE_ADDR'];

    $rating_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->prefix}post_ratings` 
                                    WHERE post_id = %d AND user_ip = %s", $post_id, $user_ip ) );

    if( $rating_count > 0 ) {
        wp_send_json( $response );
    }

    $wpdb->insert(
        $wpdb->prefix . 'post_ratings',
        array(
            'post_id' => $post_id,
            'rating' => $rating,
            'user_ip' => $user_ip
        ),
        array(
            '%d', '%f', '%s'
        )
    );

    $rating_data = get_post_meta( $post_id, '_post_rating_data', true );
    $rating_data['rating_count']++;

    $rating_data_query = $wpdb->get_var( $wpdb->prepare( "SELECT AVG(`rating`) FROM `{$wpdb->prefix}post_ratings` 
    WHERE post_id = %s", $post_id ) );

    $rating_data['rating'] = round( $rating_data_query, 1 );

    update_post_meta( $post_id, '_post_rating_data', $rating_data );

    $response['status'] = 2;
    wp_send_json( $response );

}

//Dasboard Widget Setup
function er_post_rating_dashboard_widget() {
    wp_add_dashboard_widget(
        'er_post_rating_dashboard_widget',
        'Latest Post Ratings',
        'er_post_rating_dashboard_func'
    );
}

function er_post_rating_dashboard_func() {
    global $wpdb, $post;

    $latest_ratings = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}post_ratings` ORDER BY `id` DESC LIMIT 5");

    if( $latest_ratings ) {
        echo '<ul>';
            foreach ( $latest_ratings as $rating) {
                echo '<li>';
                echo '<h4 class="meta"><a target="_blank" href="'.get_the_permalink( $rating->post_id ).'">'.get_the_title( $rating->post_id ).'</a>';
                echo ' <span style="float:right" class="rateit" id="dashboard_post_rating" data-rateit-ispreset="true" data-rateit-resetable="false" data-rateit-value="'.$rating->rating.'" data-rateit-readonly="true"></span> </h4>';
                echo get_the_excerpt( $rating->post_id );
                echo '</li>';
            }
        echo '</ul>';
    } else {
        echo 'No review post.';
    }

}