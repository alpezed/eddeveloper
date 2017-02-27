(function($) {
	$(document).ready(function() {
  		$('.owl-carousel').owlCarousel({
		    loop: eval( SLIDES_POST_API.loop ),
		    margin: 10,
    		dots: eval( SLIDES_POST_API.dots ),
    		autoplay: eval( SLIDES_POST_API.autoplay ),
		    autoplayTimeout: 4000,
		    autoplayHoverPause: true,
    		responsiveClass: true,
		    responsive: {
		        0:{
		            items: 1,
		            nav: eval( SLIDES_POST_API.nav ),
		            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
		        },
		        600:{
		            items: SLIDES_POST_API.items,
		            nav: eval( SLIDES_POST_API.nav ),
		            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
		        },
		        1000:{
		            items: SLIDES_POST_API.items,
		            nav: eval( SLIDES_POST_API.nav ),
		            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
		        }
		    }
		});
	});
})(jQuery);