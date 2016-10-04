<?php

function edwp_add_custom_metabox() {
	
	add_meta_box(
		'edwp_meta',
		__( 'Testimonial Setting' ),
		'edwp_meta_callback',
		'testimonial',
		'normal',
		'high'
	);
	
}
add_action( 'add_meta_boxes', 'edwp_add_custom_metabox' );

function edwp_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'edwp_job_nonce' );
	$edwp_stored_meta = get_post_meta($post->ID);
	?>
    	<div>
    		<div class="client-row">
    			<div class="meta-th">
                	<label for="client-name" class="edwp-row-title"><?php _e( 'Client Name', 'edwp-job-listing' ); ?></label>
                </div>
    			<div class="meta-td">
                	<input type="text" name="client_name" id="client-name" value="<?php if( ! empty( $edwp_stored_meta['client_name'] ) ) echo esc_attr( $edwp_stored_meta['client_name'][0] ); ?>">
                </div>
                <div class="tm_field_scription">
                	<em>Name of the client giving the testimonial. Appear below the Testimonial.</em>
                </div>
    		</div>
    	</div>
        <div>
    		<div class="client-row">
    			<div class="meta-th">
                	<label for="email-add" class="edwp-row-title"><?php _e( 'Email Address', 'edwp-job-listing' ); ?></label>
                </div>
    			<div class="meta-td">
                	<input type="text" name="email_add" id="email-add" value="<?php if( ! empty( $edwp_stored_meta['email_add'] ) ) echo esc_attr( $edwp_stored_meta['email_add'][0] ); ?>">
                </div>
                <div class="tm_field_scription">
                	<em>The client's email address. This field is used to check for Gravatar. if that option is enabled in your setting.</em>
                </div>
    		</div>
    	</div>
        
        <div>
    		<div class="client-row">
    			<div class="meta-th">
                	<label for="client-info" class="edwp-row-title"><?php _e( 'Position/Location/Other', 'edwp-job-listing' ); ?></label>
                </div>
    			<div class="meta-td">
                	<input type="text" name="client_info" id="client-info" value="<?php if( ! empty( $edwp_stored_meta['client_info'] ) ) echo esc_attr( $edwp_stored_meta['client_info'][0] ); ?>">
                </div>
                <div class="tm_field_scription">
                	<em>The information that ppear below the client's name.</em>
                </div>
    		</div>
    	</div>
        <?php /*?><div>
    		<div class="meta-row">
    			<div class="meta-th">
                	<label for="date-listed" class="edwp-row-title"><?php _e( 'Date Listed', 'edwp-job-listing' ); ?></label>
                </div>
    			<div class="meta-td">
                	<input type="text" class="datepicker" name="date_listed" id="date-listed" value="<?php if( ! empty( $edwp_stored_meta['date_listed'] ) ) echo esc_attr( $edwp_stored_meta['date_listed'][0] ); ?>">
                </div>
    		</div>
    	</div><?php */?>
        <!--<div>
    		<div class="client-row">
    			<div class="meta-th">
                    <span><?php /*_e( 'Client Profile Picture', 'edwp-job-listing' ); */?></span>
                </div>
                <div class="client_profile">
                	<span class="dashicons dashicons-no" id="img-delete-button"></span>
                	<img id="image-profile">
                </div>

                <input type="hidden" id="img-hidden-field" name="custom_image_data">
                <input type="button" id="img-upload-button" class="button" value="<?php /*_e( 'Add Image', 'edwp-job-listing' ); */?>">
    		</div>
    	</div>-->
        <?php /*?><div class="meta">
        	<div class="meta-th">
            	<span><?php _e( 'Principle Duties', 'edwp-job-listing' ); ?></span>
            </div>
        </div>
        <div class="meta-editor">
        	<?php
				$content = get_post_meta( $post->ID, 'principle_duties', true );
				$editor = 'principle_duties';
				$setting = array(
					'textarea_rows' => 8,
					'media_buttons' => false
				);

				wp_editor( $content, $editor, $setting );
			?>
        </div><?php */?>
        
    <?php	
}

function edwp_meta_save( $post_id ) {
	//Check save status
	$is_autosave = wp_is_post_autosave( $post_id ); //defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['edwp_job_nonce'] ) && wp_verify_nonce( $_POST['edwp_job_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	//Exit script depending on save status
	if( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
	
	if( isset( $_POST['client_name'] ) ) {
		update_post_meta( $post_id, 'client_name', sanitize_text_field( $_POST['client_name'] ) );
	}
	if( isset( $_POST['email_add'] ) ) {
		update_post_meta( $post_id, 'email_add', sanitize_text_field( $_POST['email_add'] ) );
	}
	if( isset( $_POST['client_info'] ) ) {
		update_post_meta( $post_id, 'client_info', sanitize_text_field( $_POST['client_info'] ) );
	}
	
	/*if( isset( $_POST['custom_image_data'] ) ) {
		$image_data = json_decode( stripslashes( $_POST[ 'custom_image_data' ] ) );
		if( is_object( $image_data[0] ) ) {
			$image_data  = array( 
				'id' => intval( $image_data[0]->id ), 
				'url' => esc_url_raw( $image_data[0]->url )
			);
		} else {
			$image_data = [];
		}
		update_post_meta( $post_id, 'custom_image_data', $image_data );
	}*/
}
add_action( 'save_post', 'edwp_meta_save' );
