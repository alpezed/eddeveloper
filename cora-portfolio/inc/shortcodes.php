<?php
add_shortcode('portfolio', 'portfolio');
function portfolio($attributes, $content = null)
{
    $attributes = shortcode_atts(
        array(
            'items' => null,
            'category' => null,
        ), $attributes);

    if (is_numeric($attributes['items']) AND $attributes['items'] > 0)
    {
        $attributes['items'] = ceil($attributes['items']);
    } else
    {
        $attributes['items'] = -1;
    }

    $args = array(
        'post_type' => 'us_portfolio',
        'posts_per_page' => $attributes['items'],
        'post__not_in' => get_option('sticky_posts')
    );

    if ( ! empty($attributes['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'us_portfolio_category',
                'field' => 'slug',
                'terms' => $attributes['category']
            )
        );
    }

    $portfolio = new WP_Query($args);

    $output = 	'<div class="w-portfolio">
						<div class="w-portfolio-h">
							<div class="w-portfolio-list">
								<div class="w-portfolio-list-h">';
    while($portfolio->have_posts())
    {
        $portfolio->the_post();
        $post = get_post();

        if (has_post_thumbnail()) {
            $the_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-list');
            $the_thumbnail = $the_thumbnail[0];
            $the_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            $the_image = '<img src="'.$the_image[0].'" alt="">';
        } else {
            $the_thumbnail =  get_template_directory_uri() .'/img/placeholder/500x500.gif';
            $the_image = '<img src="'.get_template_directory_uri().'/img/placeholder/1200x800.gif" alt="">';
        }

        if (rwmb_meta('us_preview_image') != '')
        {
            $preview_img_id = preg_replace('/[^\d]/', '', rwmb_meta('us_preview_image'));
            $preview_img = wp_get_attachment_image_src($preview_img_id, 'full');

            if ( $preview_img != NULL )
            {
                $the_image = '<img src="'.$preview_img[0].'" alt="">';
            }
        } elseif (rwmb_meta('us_preview_video') != '') {
            $the_image = do_shortcode('[video link="'.rwmb_meta('us_preview_video').'"]');
        }

        remove_shortcode('subsection');
        add_shortcode('subsection', array($this, 'subsection_dummy'));

        $content = get_the_content();
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        remove_shortcode('subsection');
        add_shortcode('subsection', array($this, 'subsection'));

        $output .= 					'<div class="w-portfolio-item">
											<div class="w-portfolio-item-h">
												<a class="w-portfolio-item-anchor" href="javascript:void(0);" data-id="'.$post->ID.'">
													<div class="w-portfolio-item-image" style="background-image:url('.$the_thumbnail.');"></div>
													<div class="w-portfolio-item-meta">
														<h2 class="w-portfolio-item-title">'.get_the_title().'</h2>
													</div>
													<div class="w-portfolio-item-hover"><i class="fa fa-plus"></i></div>
												</a>
												<div class="w-portfolio-item-details" style="display: none;">
													<div class="w-portfolio-item-details-h">
														<div class="w-portfolio-item-details-content">
															<div class="w-portfolio-item-details-content-preview">
																'.$the_image.'
															</div>
															<div class="w-portfolio-item-details-content-text">
																<h3>'.get_the_title().'</h3>
																'.$content.'
															</div>
														</div>

														<div class="w-portfolio-item-details-close"></div>
														<div class="w-portfolio-item-details-arrow to_prev"><i class="fa fa-angle-left"></i></div>
														<div class="w-portfolio-item-details-arrow to_next"><i class="fa fa-angle-right"></i></div>

													</div>
												</div>
											</div>
										</div>';
    }
    $output .= 				'</div>
							</div>
						</div>
					</div>';

    return $output;
}