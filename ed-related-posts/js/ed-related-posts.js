(function($) {

    $(document).on( 'click', '.load-more', function() {

        var dis = $(this);
        var paged = dis.data( 'page' );
        var newPage = paged+1;
        var id = dis.data( 'id' );

        dis.attr( 'data-page', newPage );
        dis.text( 'Loading...' );

        $.ajax({
            type: 'POST',
            url: ED_RP_SCRIPT.ajax_url,
            data: {
                paged: paged,
                id: id,
                action: 'edrp_load_more'
            },
            success: function ( response ) {
                if( response == 0 ) {
                    $('.ed-rp').append( '<div class="no-more-posts"><h3>You reached the end of the line!</h3><p>No more posts to load.</p></div>' );
                    dis.slideUp(320);
                } else {
                    dis.data( 'page', newPage );
                    dis.text( 'Show More Related Posts' );
                    $('.ed-rp').append( response );
                }
            },
            error: function ( response ) {
                console.log( response );
            }
        });

    } );

})(jQuery);