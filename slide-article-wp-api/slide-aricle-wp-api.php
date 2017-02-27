<?php
/*
Plugin Name: Slide Article
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: This plugin allow you have list of article from another website 
			 to your own website using WP REST API from wordpress.
Version:     0.1
Author:      @alpez
License:     GPL2
*/

//exit if access directly
if( ! defined( 'ABSPATH' ) ) exit;

/** ACTIVATION **/

// This plugin require version of wordpress 4.7 or higher
function api_sl_activation() {
	if( version_compare( get_bloginfo( 'version' ), '4.7', '<' ) ) {
		wp_die( __( 'This plugin requires WordPress version 4.7 or higher,
please run your WordPress upgrade to utilize this plugin.', 'api_sl' ) );
	}
}
register_activation_hook( __FILE__, 'api_sl_activation' );

/**
 * Enqueue Scripts
 */
function api_sl_custom_script() {
	global $settings;
	$settings = get_option( 'api_sl_option' );

	wp_enqueue_style( 'owl-carousel-css', plugins_url( '/lib/owlcarousel/assets/owl.carousel.min.css', __FILE__ ) );
	wp_enqueue_style( 'owl-theme-default-css', plugins_url( '/lib/owlcarousel/assets/owl.theme.default.min.css', __FILE__ ) );
	wp_enqueue_style( 'api-slides-style', plugins_url( '/css/slide-aricle-wp-api.css', __FILE__ ) );

	wp_enqueue_script( 'slide-aricle-script', plugins_url( '/js/script.js', __FILE__ ), array( 'jquery' ), '022417', true );
	wp_enqueue_script( 'owl-carousel', plugins_url( '/lib/owlcarousel/owl.carousel.js', __FILE__ ), array('jquery'), '022417', true );
	
	wp_localize_script( 'slide-aricle-script', 'SLIDES_POST_API', array(
		'items' => $settings[ 'api_sl_num_display' ],
		'nav' => $settings[ 'api_sl_display_nav' ],
		'dots' => $settings[ 'api_sl_show_dots' ],
		'loop' => $settings[ 'api_sl_infinite' ],
		'autoplay' => $settings[ 'api_sl_autoplay' ]
	) );
}
add_action( 'wp_enqueue_scripts', 'api_sl_custom_script', 999 );

function api_sl_admin_enqueue_scripts() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'slide-aricle-script', plugins_url( '/js/admin-script.js', __FILE__ ), array( 'wp-color-picker' ), '022717', true );
}
add_action( 'admin_enqueue_scripts', 'api_sl_admin_enqueue_scripts' );

/** SHORTCODE PLUGIN **/
function sla_carousel_article() {

	global $settings;

	ob_start();
	?>
	<!-- Set up your HTML -->
	<div class="owl-carousel sl-api-article">
		<?php

			if( ! empty( $settings[ 'api_sl_rest_url' ] ) ) {
				$remote_posts = get_transient( 'remote_posts' );

				if( empty( $remote_posts ) ) {
					if( ! is_object( $remote_posts ) ) {
						$url = $settings[ 'api_sl_rest_url' ]; //'http://engaoor.com/wp-json/wp/v2/posts';
						$response = wp_safe_remote_get( $url );
						if( ! is_wp_error( $response ) ) {
							$remote_posts = json_decode( wp_remote_retrieve_body( $response ) );
							set_transient( 'remote_posts', $remote_posts, DAY_IN_SECONDS );
						}
					}
				}
			} else {
				echo 'Please enter valid WP API posts data.';
			}

			if( ! empty( $remote_posts ) ) {
				foreach ( $remote_posts as $remote_post ) {
					if( $remote_post->featured_image_src !== null || $remote_post->featured_image_src ) {
					?>
						<div class="item">
							<a target="_blank" href="<?php echo $remote_post->link; ?>">
								<div style="background: url(<?php echo $remote_post->featured_image_src; ?>) center center no-repeat; background-size: cover; width: 340px; height: 240px; max-width: 100%"></div>
								<?php if( $settings[ 'api_sl_display_title' ] === 'true' ) { ?>
									<h3 <?php echo ( $settings[ 'api_sl_heading_color' ] ) ? 'style="color:'.$settings[ 'api_sl_heading_color' ].'"' : ''; ?>><?php echo $remote_post->title->rendered; ?></h3>
								<?php } ?>
							</a>
							<?php if( $settings[ 'api_sl_display_excerpt' ] === 'true' ) { ?>
								<?php echo $remote_post->excerpt->rendered; ?>
							<?php } ?>
						</div>
					<?php
					}
				}
			}
		?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'api-article-slides', 'sla_carousel_article' );

/** PLUGIN SETTINGS PAGE **/
function api_slides_settings_menu() {
	add_menu_page( 'Slide Article Options', 'Slide Article', 'manage_options', 'slide-article', 'api_slide_setting_page', false, 50 );
}
add_action( 'admin_menu', 'api_slides_settings_menu', 10, 1 );

function api_slide_setting_page() {

	$settings = get_option( 'api_sl_option' );

	if( isset( $_POST[ 'api_sl_option_submitted' ] ) && $_POST[ 'api_sl_option_submitted' ] === 'Y' ) {

		$settings[ 'api_sl_rest_url' ] 			= $_POST[ 'api_sl_rest_url' ];
		$settings[ 'api_sl_display_nav' ] 		= $_POST[ 'api_sl_display_nav' ];
		$settings[ 'api_sl_show_dots' ] 		= $_POST[ 'api_sl_show_dots' ];
		$settings[ 'api_sl_autoplay' ] 			= $_POST[ 'api_sl_autoplay' ];
		$settings[ 'api_sl_num_display' ] 		= $_POST[ 'api_sl_num_display' ];
		$settings[ 'api_sl_display_title' ] 	= $_POST[ 'api_sl_display_title' ];
		$settings[ 'api_sl_display_excerpt' ] 	= $_POST[ 'api_sl_display_excerpt' ];
		$settings[ 'api_sl_infinite' ] 			= $_POST[ 'api_sl_infinite' ];
		$settings[ 'api_sl_heading_color' ] 	= $_POST[ 'api_sl_heading_color' ];

		update_option( 'api_sl_option', $settings );

	}
                      
	if( $settings !== '' ) {
		$api_sl_rest_url 		= $settings[ 'api_sl_rest_url' ];
		$api_sl_display_nav 	= $settings[ 'api_sl_display_nav' ];
		$api_sl_show_dots 		= $settings[ 'api_sl_show_dots' ];
		$api_sl_autoplay 		= $settings['api_sl_autoplay'];
		$api_sl_num_display 	= $settings['api_sl_num_display'];
		$api_sl_display_title 	= $settings['api_sl_display_title'];
		$api_sl_display_excerpt = $settings['api_sl_display_excerpt'];
		$api_sl_infinite 		= $settings['api_sl_infinite'];
		$api_sl_heading_color 	= $settings['api_sl_heading_color'];
	}

	?>
		<div class="wrap">
			<h1>Slide Article Option</h1>
			<?php
				if ( isset( $_REQUEST['api_sl_submit'] ) ) {
					echo '<div id="message" class="updated notice is-dismissible"><p><strong>Settings saved.</strong></p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
				}
				if( ! current_user_can( 'manage_options' ) ) {
					echo '<div id="message" class="error"><p><strong>There was an error saving the setting, or you do not have proper permission.</strong></p></div>';
				}
			?>
		</div>

		<form accept="" method="post" class="">

			<input type="hidden" name="api_sl_option_submitted" value="Y">

			<table class="form-table">

				<tr>
					<th>Posts URL</th>
					<td>
						<div class="api_sl_rest_url">
							<input type="text" name="api_sl_rest_url" class="regular-text" value="<?php echo $api_sl_rest_url; ?>">
							<p class="description"><strong><span style="color:red">*</span>Important: </strong>Put the wordpress rest api url to get the post data<br>eg. (http://samplesite.com/wp-json/wp/v2/posts). <br><strong>Note:</strong> Get only the posts data</p>
						</div>
					</td>
				</tr>
				
				<tr>
					<th>Show Next/Prev Buttons</th>
					<td>
						<div class="api_sl_display_nav">
							<label>
								<input type="radio" name="api_sl_display_nav" value="true" <?php checked( $api_sl_display_nav, 'true' ); ?> <?php if ( ! isset( $settings['api_sl_display_nav'] ) ) echo 'checked="checked"'; ?> />
								<span><?php esc_attr_e( 'Enable', 'api_sl' ); ?></span>
							</label>&nbsp;
							<label>
								<input type="radio" name="api_sl_display_nav" value="false" <?php checked( $api_sl_display_nav, 'false' ); ?> />
								<span><?php esc_attr_e( 'Disable', 'api_sl' ); ?></span>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th>Show Dots Navigation</th>
					<td>
						<div class="api_sl_show_dots">
							<label>
								<input type="radio" name="api_sl_show_dots" value="true" <?php checked( $api_sl_show_dots, 'true' ); ?> />
								<span><?php esc_attr_e( 'Enable', 'api_sl' ); ?></span>
							</label>&nbsp;
							<label>
								<input type="radio" name="api_sl_show_dots" value="false" <?php checked( $api_sl_show_dots, 'false' ); ?> <?php if ( ! isset( $settings['api_sl_show_dots'] ) ) echo 'checked="checked"'; ?> />
								<span><?php esc_attr_e( 'Disable', 'api_sl' ); ?></span>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th>Auto Slide</th>
					<td>
						<div class="api_sl_autoplay">
							<label>
								<input type="radio" name="api_sl_autoplay" value="true" <?php checked( $api_sl_autoplay, 'true' ); ?> />
								<span><?php esc_attr_e( 'Enable', 'api_sl' ); ?></span>
							</label>&nbsp;
							<label>
								<input type="radio" name="api_sl_autoplay" value="false" <?php checked( $api_sl_autoplay, 'false' ); ?> <?php if ( ! isset( $settings['api_sl_show_dots'] ) ) echo 'checked="checked"'; ?> />
								<span><?php esc_attr_e( 'Disable', 'api_sl' ); ?></span>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th>Number of items you want to see on the screen.</th>
					<td>
						<div class="api_sl_num_display">
							<select name="api_sl_num_display" id="api_sl_num_display">
								<option value="3" <?php selected( $api_sl_num_display, 3 ); ?>>3</option>
								<option value="4" <?php selected( $api_sl_num_display, 4 ); ?>>4</option>
								<option value="4" <?php selected( $api_sl_num_display, 5 ); ?>>5</option>
								<option value="4" <?php selected( $api_sl_num_display, 6 ); ?>>6</option>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<th>Infinite</th>
					<td>
						<div class="api_sl_infinite">
							<label>
								<input type="radio" name="api_sl_infinite" value="true" <?php checked( $api_sl_infinite, 'true' ); ?> />
								<span><?php esc_attr_e( 'Enable', 'api_sl' ); ?></span>
							</label>&nbsp;
							<label>
								<input type="radio" name="api_sl_infinite" value="false" <?php checked( $api_sl_infinite, 'false' ); ?> <?php if ( ! isset( $settings['api_sl_infinite'] ) ) echo 'checked="checked"'; ?>/>
								<span><?php esc_attr_e( 'Disable', 'api_sl' ); ?></span>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th>Display Title</th>
					<td>
						<div class="api_sl_display_title">
							<label>
								<input type="radio" name="api_sl_display_title" value="true" <?php checked( $api_sl_display_title, 'true' ); ?> <?php if ( ! isset( $settings['api_sl_display_title'] ) ) echo 'checked="checked"'; ?> />
								<span><?php esc_attr_e( 'Enable', 'api_sl' ); ?></span>
							</label>&nbsp;
							<label>
								<input type="radio" name="api_sl_display_title" value="false" <?php checked( $api_sl_display_title, 'false' ); ?> />
								<span><?php esc_attr_e( 'Disable', 'api_sl' ); ?></span>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th>Display Excerpt</th>
					<td>
						<div class="api_sl_display_excerpt">
							<label>
								<input type="radio" name="api_sl_display_excerpt" value="true" <?php checked( $api_sl_display_excerpt, 'true' ); ?> />
								<span><?php esc_attr_e( 'Enable', 'api_sl' ); ?></span>
							</label>&nbsp;
							<label>
								<input type="radio" name="api_sl_display_excerpt" value="false" <?php checked( $api_sl_display_excerpt, 'false' ); ?> <?php if ( ! isset( $settings['api_sl_display_excerpt'] ) ) echo 'checked="checked"'; ?> />
								<span><?php esc_attr_e( 'Disable', 'api_sl' ); ?></span>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th>Heading Color</th>
					<td>
						<div class="api_sl_heading_color">
							<input id="color_field" type="text" name="api_sl_heading_color" value="<?php echo $api_sl_heading_color; ?>">
							<p class="description">Choose color for heading text.</p>
						</div>
					</td>
				</tr>
				
			</table>

			<p>
				<input type="submit" name="api_sl_submit" class="button-primary" value="Save Changes">
			</p>
		</form>
	<?php
}