<?php


/* Testimonial Reorder */
function edwp_add_submenu_page() {
	add_submenu_page ( 
		'edit.php?post_type=testimonial',
		'Reorder Testimonial',
		'Reorder Testimonial',
		'manage_options', 
		'reorder_testimonial', 
		'edwp_reorder_admin_testimonial_callback' 
	);
}
add_action( 'admin_menu', 'edwp_add_submenu_page' );

function edwp_reorder_admin_testimonial_callback() {
	
	$args = array(
		'post_type'				 => 'testimonial',
		'post_status'			 => 'publish',
		'orderby'				 => 'menu_order',
		'order'					 => 'ASC',
		'no_found_rows'			 => true,
		'update_post_term_cache' => false,
		'post_per_page'			 => 50
	);
	
	$job_listing = new WP_Query( $args );
	
	?>
    	<div id="job-sort" class="wrap">
        	<div id="icon-tools" class="icon32"></div>
        	<h2><?php _e( 'Sort Testimonials Positions', 'edwp-job-listing' ); ?> <img src="<?php echo esc_url( admin_url() . 'images/loading.gif' ); ?>" id="loading-animation"></h2>
            <?php if( $job_listing->have_posts() ) : ?>
            	<ul id="custom-type-list">
                	<?php while( $job_listing->have_posts() ) : $job_listing->the_post(); ?>
                    	<li id="<?php esc_attr( the_id() ); ?>"><?php esc_html( the_title() ); ?></li>
                    <?php endwhile; ?>
                </ul>
             <?php else: ?>
             	<p><?php _e( 'You have no Jobs listed to sort.', 'edwp-job-listing' ); ?></p>
            <?php endif; ?>
        </div>
    <?php
	
}

function edwp_save_reorder() {
	
	if( !check_ajax_referer( 'ed-job-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}
	
	if( !current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'You are not allow to do this.' );
	}
	
	$reorder = $_POST['reorder'];
	$counter = 0;
	
	foreach($reorder as $item_id) {

		$post = array(
			'ID' => (int)$item_id,
			'menu_order' => $counter,
		);
		
		wp_update_post( $post );
		
		$counter++;
	}
	
	wp_send_json_success( 'Post Saved.' );
	
}
add_action( 'wp_ajax_save_sort', 'edwp_save_reorder' );
add_action( 'wp_ajax_nopriv_save_sort', 'edwp_save_reorder' );

/* Testimonial Option Page */
function edwp_add_submenu_setting_page() {
	add_submenu_page (
		'edit.php?post_type=testimonial',
		'Testimonial Options',
		'Settings',
		'manage_options',
		'testimonial_option',
		'edwp_option_admin_testimonial_callback'
	);
}
add_action( 'admin_menu', 'edwp_add_submenu_setting_page' );

function edwp_option_admin_testimonial_callback() {
	global $options;

	if( isset( $_POST[ 'testimonial_form_submitted' ] ) ) {
		$hidden_field = $_POST[ 'testimonial_form_submitted' ];

		if( $hidden_field == 'Y' ) {

			$testimonial_speed = esc_html( $_POST[ 't_speed' ] );
			$testimonial_controls = $_POST[ 't_controls' ];
			$testimonial_auto = $_POST[ 't_auto' ];
			$testimonial_rounded = $_POST[ 't_show_image' ];
			$testimonial_column = $_POST[ 't_columns' ];

			$options[ 't_speed' ] = $testimonial_speed;
			$options[ 't_controls' ] = $testimonial_controls;
			$options[ 't_auto' ] = $testimonial_auto;
			$options[ 't_show_image' ] = $testimonial_rounded;
			$options[ 't_columns' ] = $testimonial_column;

			update_option( 'edwp_testimonials', $options );

		}
	}

	$options = get_option( 'edwp_testimonials' );

	if( $options != '' ) {
		$testimonial_speed = $options['t_speed'];
		$testimonial_controls = $options['t_controls'];
		$testimonial_auto = $options['t_auto'];
		$testimonial_rounded = $options['t_show_image'];
		$testimonial_column = $options[ 't_columns' ];
	}

	//$checked = $selected = $disabled = $value = NULL;

	if ( isset( $_REQUEST['testimonial_option_submit'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible"><p><strong>Settings saved.</strong></p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}
	if( !current_user_can( 'manage_options' ) ) {
		echo '<div id="message" class="error"><p><strong>There was an error saving the setting, or you do not have proper permission.</strong></p></div>';
	}
	?>
	<div id="testimonial-options" class="wrap">
		<div id="icon-tools" class="icon32"></div>
		<h2><?php _e( 'Testimonials Options', 'edwp-job-listing' ); ?></h2>

		<div class="form">

			<form method="post" action="">

				<input type="hidden" name="testimonial_form_submitted" value="Y">

				<table class="form-table">
					<tr>
						<th><label for="t_speed">Speed</label></th>
						<td>
							<input type="text" name="t_speed" id="t_speed" class="all-options" placeholder="200" value="<?php echo $testimonial_speed; ?>">
							<p class="description">Slide tarnsition duration (in ms) (default 200)</p>
						</td>
					</tr>

					<tr>
						<th><label for="t_columns">Columns</label></th>
						<td>
							<select name="t_columns" id="t_columns">
								<option value="1" <?php selected( $testimonial_column, '1', TRUE ); ?>>1 Column</option>
								<option value="2" <?php selected( $testimonial_column, '2', TRUE ); ?>>2 Columns</option>
								<option value="3" <?php selected( $testimonial_column, '3', TRUE ); ?>>3 Columns</option>
								<option value="4" <?php selected( $testimonial_column, '4', TRUE ); ?>>4 Columns</option>
							</select>
							<p class="description">Number of columns to display (default 1)</p>
						</td>
					</tr>

					<tr>
						<th><label for="t_controls">Controls</label></th>
						<td>
							<label for="t_controls">
								<input type="checkbox" name="t_controls" id="t_controls" value="true" <?php checked( $testimonial_controls, 'true' ); ?>>
								If true, pager will be added
							</label>
						</td>
					</tr>

					<tr>
						<th><label for="t_auto">Auto</label></th>
						<td>
							<label for="t_auto">
								<input type="checkbox" name="t_auto" id="t_auto" value="true" <?php checked( $testimonial_auto, 'true' ); ?>>
								Slides will be automatically transition
							</label>
						</td>
					</tr>

					<tr>
						<th><label for="t_show_image">Show Image</label></th>
						<td>
							<select name="t_show_image" id="t_show_image">
								<option value="yes" <?php selected( $testimonial_rounded, 'yes', TRUE ); ?>>Yes</option>
								<option value="no" <?php selected( $testimonial_rounded, 'no', TRUE ); ?>>No</option>
							</select>
						</td>
					</tr>
				</table>

				<p>
					<input class="button-primary" type="submit" name="testimonial_option_submit" value="Save Changes" />
				</p>

			</form>

		</div><!-- form div ends here -->
	</div>
	<?php
}

