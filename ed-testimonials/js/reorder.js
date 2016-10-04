jQuery(document).ready(function($) {
    
	var sortList = $( 'ul#custom-type-list' );
	var animation = $( '#loading-animation' );
	var pageTitle = $( 'div h2' );
	
	sortList.sortable({
		update: function() {
			animation.show();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'save_sort',
					reorder: sortList.sortable( 'toArray' ),
					security: ED_TESTIMONIAL_LISTING.security
				},
				success: function( response ) {
					animation.hide();
					$( '#message' ).remove();
					if(response.success === true) {
						pageTitle.after( '<div id="message" class="updated"><p>' + ED_TESTIMONIAL_LISTING.success + '</p></div>' );
					} else {
						pageTitle.after( '<div id="message" class="error"><p>' + ED_TESTIMONIAL_LISTING.error + '</p></div>' );
					}
				},
				error: function( error ) {
					animation.hide();
					$( '#message' ).remove();
					pageTitle.after( '<div id="message" class="error"><p>' + ED_TESTIMONIAL_LISTING.error + '</p></div>' );
				}
			});

		}
	});
	
});
