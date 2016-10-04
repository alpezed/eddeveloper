<?php
/**
 * Plugin Name: Ed's Custom Taxonomy Meta
 * Plugin URI: http://wordpress.org/
 * Description: The Custom taxonomy Meta Plugin allow you to add meta to the wordpress categories and taxonomies.
 * Author: Ed A.
 * Author URI: http://wordpress.org/
 * Version: 0.0.1
 * License: GPLv2.
 **/

//Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

define( 'PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );

require_once( PLUGIN_DIR_PATH . 'inc/ed-taxonomy-meta-setting.php' );
require_once( PLUGIN_DIR_PATH . 'inc/ed-form-fields.php' );

if( is_admin() ) {
    $edTaxtMeta = new Ed_Taxonomy_Meta();
    $edTaxtMetaFields = new Ex_Tax_Meta_Fields();
}

function edci_sanitize_input( $inputName ) {
    return strtolower( 'edci_' . str_replace( ' ', '_', $inputName ) );
}

/*function edci_setup() {
    add_action( 'category_add_form_fields', 'ed_new_category_image_metadata' );
    add_action( 'category_edit_form_fields', 'ed_edit_category_image_metadata' );
    add_action( 'admin_enqueue_scripts', 'edci_enqueue_scripts' );
    add_action( 'create_category', 'save_category_image' );
    add_action( 'edit_category', 'save_category_image' );
}
edci_setup();*/

/*function ed_new_category_image_metadata() {
    wp_nonce_field( basename(__FILE__), 'cat_img_nonce' );
    ?>
        <th scope="row" valign="top" colspan="2">
            <h3><?php echo esc_html__( 'Category Image', 'edci' ) ?></h3>
        </th>

        <div class="form-field cat-img">
            <img id="catImg">
            <input type="hidden" id="catImg_data" name="catImg_data">
            <input type="button" id="cat-img-upload-btn" class="button" value="<?php echo esc_html__( 'Add Category Image', 'edci' ) ?>">
            <input type="button" id="cat-img-remove-btn" class="button" value="<?php echo esc_html__( 'Remove Category Image', 'edci' ) ?>">
            <p class="description">Displaying image to your category page.</p>
        </div>
    <?php
}*/

/*function ed_edit_category_image_metadata( $term ) {
    wp_nonce_field( basename(__FILE__), 'cat_img_nonce' );
    $cat_meta = get_term_meta( $term->term_id, 'cat_image_data', true );
    ?>
        <tr class="form-field term-category-image-wrap">
            <th scope="row"><?php echo esc_html__( 'Category Image', 'edci' ) ?></th>
            <td>
                <div class="form-field cat-img">
                    <img id="catImg" src="<?php echo ( !empty( $cat_meta['src'] ) ) ? esc_attr( $cat_meta['sizes']['thumbnail'] ) : '' ?>">
                    <input type="hidden" id="catImg_data" name="catImg_data">
                    <input type="button" id="cat-img-upload-btn" class="button" value="<?php echo esc_html__( 'Add Category Image', 'edci' ) ?>">
                    <input type="button" id="cat-img-remove-btn" class="button" value="<?php echo esc_html__( 'Remove Category Image', 'edci' ) ?>">
                    <p class="description">Displaying image to your category page.</p>
                </div>
            </td>
        </tr>
    <?php
}*/

/*function save_category_image( $term_id ) {
    if( !isset( $_POST['cat_img_nonce'] ) ) {
        return;
    }
    if( !wp_verify_nonce( $_POST['cat_img_nonce'], basename(__FILE__) ) ) {
        return;
    }

    if( isset( $_POST['catImg_data'] ) ) {
        $catImg_data = json_decode( stripslashes( $_POST['catImg_data'] ) );

        if( is_object( $catImg_data[0] ) ) {
            $catImg_data = array(
                'id' => intval( $catImg_data[0]->id ),
                'src' => esc_url_raw( $catImg_data[0]->url ),
                'sizes' => array( 'thumbnail' => $catImg_data[0]->sizes->thumbnail )
            );
        } else {
            $catImg_data = [];
        }

        $test = 0;

        update_term_meta( $term_id, 'cat_image_data', $catImg_data );
    }
}*/


