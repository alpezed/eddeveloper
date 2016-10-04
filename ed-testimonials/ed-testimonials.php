<?php
/**
 * Plugin Name: Ed Simple Testimonial
 * Plugin URI: http://ed-wpdevelopement.com
 * Description: Ed Simple Testimonial Provide both widget and shortcode to help you display your testimonial page on your website.
 * Author: @alpezed
 * Author URI: http://ed-wpdevelopement.com
 * Version: 0.0.1
 * License: GPLv2
 **/
 
//Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDWP_PLUGIN_DIR', plugin_dir_path(__FILE__) );

require_once( EDWP_PLUGIN_DIR . 'inc/ed-testimonials-cpt.php' );
require_once( EDWP_PLUGIN_DIR . 'inc/ed-testimonials-settings.php' );
require_once( EDWP_PLUGIN_DIR . 'inc/ed-testimonials-fields.php' );
require_once( EDWP_PLUGIN_DIR . 'inc/ed-testimonial-widget.php' );
require_once( EDWP_PLUGIN_DIR . 'inc/ed-shortcode.php' );

/**
 * Class & Method
 **/

$edwptm = new EDwptm();

class EDwptm {

	public function __construct()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'edwp_admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'edwp_frontend_enqueue_script' ) );
		add_filter( 'manage_testimonial_posts_columns', array( $this, 'testimonials_set_custom_column' ) );
		add_action( 'manage_testimonial_posts_custom_column', array( $this, 'testimonials_custom_column' ), 10, 2 );
		add_filter( 'manage_edit-testimonial_sortable_columns', array( $this, 'testimonials_sort_column' ) );
		add_action( 'pre_get_posts', array( $this, 'testimonial_orderby' ) );
	}

    public function edwp_admin_enqueue_scripts() {
		global $pagenow, $typenow;

		if( $pagenow == 'edit.php' || $pagenow == 'edit-tags.php' || $pagenow == 'term.php' ) {
			wp_enqueue_media();
		}

		if( $typenow == 'testimonial' ) {
			wp_enqueue_style( 'edwp-admin-css', plugins_url( 'css/admin-testimonial.css', __FILE__ ) );
		}

		if( $pagenow == 'term.php' && $typenow == 'post' ) {
			wp_enqueue_style( 'edwp-admin-css', plugins_url( 'css/admin-edit-category.css', __FILE__ ) );
		}

		if( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'testimonial' ) {
			wp_enqueue_script( 'edwp-admin-js', plugins_url( 'js/admin-testimonial.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), '20161504', true );
			wp_enqueue_script( 'edwp-image-upload-js', plugins_url( 'js/image-upload.js', __FILE__ ), array( 'jquery' ), '0.0.1', true );

			wp_localize_script( 'edwp-image-upload-js', 'JOB_PROFILE_IMG_UPLOAD', array(
				'imageData' => get_post_meta( get_the_ID(), 'custom_image_data', true )
			) );

			wp_enqueue_style( 'jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css' );
		}

		if( $pagenow == 'edit.php' && $typenow == 'testimonial' ) {
			wp_enqueue_script( 'reorder-js', plugins_url( 'js/reorder.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20161504', true );
            wp_enqueue_script( 'new-js', plugins_url( 'js/new.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20161504', true );

			wp_localize_script( 'reorder-js', 'ED_TESTIMONIAL_LISTING', array(
				'security' => wp_create_nonce( 'ed-job-order' ),
				'success' => 'Testimonial sort order has been saved.',
				'error' => 'There was an error saving the sort order, or you do not have proper permission.'
			) );

		}

	}

	/**
	 * Script for Frontend view
	 */
    public function edwp_frontend_enqueue_script() {
		global $options;

		$options = get_option( 'edwp_testimonials' );

		wp_enqueue_style( 'owl-css', plugins_url( 'frontend/css/owl.carousel.css', __FILE__ ) );
        wp_enqueue_style( 'owl-transitions-css', plugins_url( 'frontend/css/owl.transitions.css', __FILE__ ) );
		wp_enqueue_style( 'font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' );
		wp_enqueue_style( 'owl-theme-default-css', plugins_url( 'frontend/css/owl.theme.default.min.css', __FILE__ ) );
		wp_enqueue_style( 'testimonial-css', plugins_url( 'frontend/css/ed-testimonial-frontend.css', __FILE__ ) );
		wp_enqueue_script( 'owl-js', plugins_url( 'frontend/js/owl.carousel.min.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'ed-frontend-js', plugins_url( 'frontend/js/ed-testimonial-script.js', __FILE__ ), array( 'jquery' ), '06052016', true );

		wp_localize_script( 'ed-frontend-js', 'ED_TESTIMONIAL_OPT', array(
			't_speed' => ( isset( $options[ 't_speed' ] ) || !empty( $options[ 't_speed' ] ) ) ? $options[ 't_speed' ] : 200,
			't_autoplay' => ( isset( $options[ 't_auto' ] ) ) ? $options[ 't_auto' ] : "false",
			't_controls' => ( isset( $options[ 't_controls' ] ) ) ? $options[ 't_controls' ] : "false",
			't_columns' => ( isset( $options[ 't_columns' ] ) ) ? $options[ 't_columns' ] : 1
		));
	}

    public function testimonials_set_custom_column( $columns ) {
		/** Add New Column on Testimonial Table **/
		$new_columns  = array(
			'client_info' => __( 'Client Info' ),
			'email_add' => __( 'Email Address' ),
			'custom_image_data' => __( 'Client Profile' )
		);

		// Remove unwanted publish date column
		unset( $columns['date'] );

		// Combine existing columns with new columns
		$filtered_columns = array_merge( $columns, $new_columns );

		// Return our filtered array of columns
		return $filtered_columns;
	}

    public function testimonials_custom_column( $column, $post_id ) {
		/*
         * Set Email Column.
         **/
		if( $column == 'email_add' ) {
			$email_field = get_post_meta( $post_id, 'email_add', true );
			if( (isset( $email_field ) && !empty( $email_field )) ) {
				if( filter_var( $email_field, FILTER_VALIDATE_EMAIL ) ) {
					echo $email_field;
				} else {
					echo '<em>Invalid Email.</em>';
				}
			} else {
				echo '<em>No email</em>';
			}
		}

		/*
         * Set Client Information Column.
         **/
		if( $column == 'client_info' ) {
			echo get_post_meta( $post_id, 'client_info', true );
		}

		/*
         * Set Image Profile Column.
         **/
		if( $column == 'custom_image_data' ) {
			$client_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
			if( has_post_thumbnail() ) {
				echo '<a href="post.php?post='. get_the_ID() .'&action=edit">
						<img src=" ' . $client_img[0] . ' " width="32" height="32" style="border-radius:100%">
					  </a>';
			} else {
				echo '<img src="http://placehold.it/150x150/F1F1F1/" width="32" height="32" style="border-radius:100%">';
			}
		}
	}

    public function testimonials_sort_column( $columns ) {
		$columns['client_info'] = 'client_info';
		$columns['email_add'] = 'email_add';
		return $columns;
	}

    public function testimonial_orderby( $query ) {
		if( ! is_admin() ) return;

		$orderby = $query->get( 'orderby' );

		if( 'client_info' == $orderby ) {
			$query->set('meta_key','client_info');
			$query->set('orderby','meta_value');
		}

		if( 'email_add' == $orderby ) {
			$query->set('meta_key','email_add');
			$query->set('orderby','meta_value');
		}
	}

}


