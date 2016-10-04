(function($) {
    $("#post_rating").bind('rated', function() {

        $(this).rateit( 'readonly', true );

        var ratingData = {
            action: 'er_save_rating_post',
            pid: $(this).data( 'pid' ),
            rating: $(this).rateit( 'value' ),
            security: er_rating_obj.security
        };

        $.post( er_rating_obj.ajax_url, ratingData, function( data ) {
            console.log( data );
        } );

    });
})(jQuery);
