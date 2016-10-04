(function( $ ) {
    var addBtn = $( "#cat-img-upload-btn" );
    var removeBtn = $( "#cat-img-remove-btn" );
    var hiddenField = $( "#catImg_data" );
    var catImg = $( "#catImg" );

    var catimgUploader = wp.media({
        title: 'Select Category Image',
        button: { text: 'Select Category Image' },
        multiple: false
    });

    var toogleVisibility = function( action ) {
        if( action === "ADD" ) {
            addBtn.hide();
            removeBtn.show();
            catImg.attr( 'style', 'display:block;margin-bottom:5px' );
        }
        if( action === "DELETE" ) {
            addBtn.show();
            removeBtn.hide();
            catImg.attr( 'style', 'display:block' );
        }
    }

    addBtn.on("click", function() {
        if(catimgUploader) {
            catimgUploader.open();
        }
    });

    catimgUploader.on("select", function() {
        var attachment = catimgUploader.state().get('selection').first().toJSON();
        var test = 0;
        catImg.attr( 'src', attachment.sizes.thumbnail.url );
        hiddenField.val( attachment.id );
        toogleVisibility("ADD");
    });

    removeBtn.on("click", function() {
        catImg.removeAttr( 'src' );
        hiddenField.removeAttr( 'value' );
        toogleVisibility("DELETE");
    });

    window.addEventListener( 'DOMContentLoaded', function() {
        //catImgEdit.attr( 'src', CAT_IMG_UPLOAD.catImgData.src );
        if( CAT_IMG_UPLOAD.catImgData === "" || CAT_IMG_UPLOAD.catImgData.length === 0 ) {
            toogleVisibility("DELETE");
        } else {
            toogleVisibility("ADD");
        }
    } );

})(jQuery);