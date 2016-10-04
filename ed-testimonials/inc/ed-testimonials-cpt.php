<?php

function edwp_register_post_type() {
	
	$singular = apply_filters( 'edwp_label_single', 'Testimonial' );
	$plural = apply_filters( 'edwp_label_plural', 'Testimonials' );

	//Used for the rewrite slug below.
	$plural_slug = str_replace( ' ', '_', $plural );
	
	//Setup all the labels to accurately reflect this post type.
	$labels = array(
		'name' 					=> $plural,
		'singular_name' 		=> $singular,
		'add_new' 				=> 'Add New',
		'add_new_item' 			=> 'Add New ' . $singular,
		'edit'		        	=> 'Edit',
		'edit_item'	        	=> 'Edit ' . $singular,
		'new_item'	        	=> 'New ' . $singular,
		'view' 					=> 'View ' . $singular,
		'view_item' 			=> 'View ' . $singular,
		'search_term'   		=> 'Search ' . $plural,
		'parent' 				=> 'Parent ' . $singular,
		'not_found' 			=> 'No ' . $plural .' found',
		'not_found_in_trash' 	=> 'No ' . $plural .' in Trash'
	);
        //Define all the arguments for this post type.
	$args = array(
		'labels' 			  => $labels,
		'public'              => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'show_in_nav_menus'   => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-format-quote',
        'can_export'          => true,
        'delete_with_user'    => false,
        'hierarchical'        => false,
        'has_archive'         => true,
        'query_var'           => true,
        'capability_type'     => 'page',
        'map_meta_cap'        => true,
        // 'capabilities' => array(),
        'rewrite'             => array( 
        	'slug' => strtolower( $plural_slug ),
        	'with_front' => true,
        	'pages' => true,
        	'feeds' => false,
        ),
        'supports'            => array( 'title', 'editor', 'thumbnail' )
	);
       
	//Create the post type using the above two varaiables.
	register_post_type( 'testimonial', $args);
	
}
add_action( 'init', 'edwp_register_post_type' );

/*function edwp_register_taxonomy() {
	
	$singular = 'Location';
	$plural = 'Locations';
	
	$labels = array(
		'name'                       => $plural,
		'singular_name'              => $singular,
		'search_items'               => 'Search ' . $plural,
		'popular_items'              => 'Popular ' . $plural,
		'all_items'                  => 'All ' . $plural,
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => 'Edit ' . $singular,
		'update_item'                => 'Update ' . $singular,
		'add_new_item'               => 'Add New ' . $singular,
		'new_item_name'              => 'New ' . $plural . ' Name',
		'separate_items_with_commas' => 'Separate ' . $plural . ' with commas',
		'add_or_remove_items'        => 'Add or remove ' . $plural,
		'choose_from_most_used'      => 'Choose from the most used ' . $plural,
		'not_found'                  => 'No ' . $plural . ' found.',
		'menu_name'                  => $plural,
	);
	
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => strtolower( $singular ) ),
	);
	
	register_taxonomy( 'location', 'testimonial', $args );
}
add_action( 'init', 'edwp_register_taxonomy' );*/