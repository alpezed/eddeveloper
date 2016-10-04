jQuery(document).ready(function($) {
    $("#testimonial_carousel").owlCarousel({
        //Basic Speeds
        slideSpeed: $.parseJSON(ED_TESTIMONIAL_OPT.t_speed),
        paginationSpeed: 400,
        items : $.parseJSON(ED_TESTIMONIAL_OPT.t_columns),
        loop: true,
        autoPlay: $.parseJSON(ED_TESTIMONIAL_OPT.t_autoplay),
        autoPlaySpeed: 5000,
        autoPlayTimeout: 5000,
        autoPlayHoverPause: true,
        pagination: false,
        navigation: $.parseJSON(ED_TESTIMONIAL_OPT.t_controls),
        navigationText:	["prev","next"],
        autoHeight : true,
        transitionStyle:"fade"
    });
});