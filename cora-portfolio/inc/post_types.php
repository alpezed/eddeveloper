<?php
add_action( 'init', 'create_post_types' );
function create_post_types() {
    global $smof_data;

    // Portfolio post type
    register_post_type( 'us_portfolio',
        array(
            'labels' => array(
                'name' => 'Portfolio',
                'singular_name' => 'Portfolio Item',
                'add_new' => 'Add Portfolio Item',
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
            'can_export' => true,
            'hierarchical' => false,
            'exclude_from_search' => true,
        )
    );
	
	// Portfolio categories
	register_taxonomy(
		'us_portfolio_category', 
		array('us_portfolio'), 
		array(
			'hierarchical' => true, 
			'label' => 'Portfolio Categories',
			'singular_label' => 'Portfolio Category', 
			'rewrite' => true
		)
	);

}



add_action( 'admin_head', 'us_portfolio_icons' );

function us_portfolio_icons() {
    ?>
    <style type="text/css" media="screen">
        #menu-posts-us_portfolio .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-portfolio.png) 6px 6px no-repeat !important;
        }
        #menu-posts-us_portfolio.wp-has-current-submenu .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-portfolio-active.png) no-repeat 6px 6px !important;
        }
        #icon-edit.icon32-posts-us_portfolio {background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-portfolio-big.png) no-repeat;}

        #menu-posts-us_main_page_section .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-sections.png) 6px 6px no-repeat !important;
        }
        #menu-posts-us_main_page_section.wp-has-current-submenu .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-sections-active.png) no-repeat 6px 6px !important;
        }
        #icon-edit.icon32-posts-us_main_page_section {background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-sections-big.png) no-repeat;}

        #menu-posts-us_client .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-client.png) 6px 6px no-repeat !important;
        }
        #menu-posts-us_client.wp-has-current-submenu .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-client-active.png) no-repeat 6px 6px !important;
        }
        #icon-edit.icon32-posts-us_client {background: url(<?php echo get_template_directory_uri(); ?>/admin/assets/images/admin-client-big.png) no-repeat;}
    </style>
<?php }