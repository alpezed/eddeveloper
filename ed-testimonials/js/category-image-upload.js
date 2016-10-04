jQuery(function($) {
	var addButton = $( '#cat-img-upload-button' );
	var image = $( '#image-category' );
	var hidden = $( '#img-cat-hidden-field' );
	var removeButton = $( '#cat-img-remove-button' );

	/*Edit Category Image*/
	var catImgEdit = $( '.cat-thumbnail' );

	var customUploader = wp.media({
		title: 'Select Category Image',	
		button: {
			text: 'Select Image'
		},
		multiple: false
	});

	var toogleVisibility = function( action ) {
		if( action === 'ADD' ) {
			addButton.css('display', 'none');
			removeButton.css( 'display', 'block' );
			catImgEdit.css( 'display', '' );
			image.attr( 'width', '110' );
		}
		
		if( action === 'DELETE' ) {
			addButton.css( 'display', 'block' );
			removeButton.css('display', 'none');
			catImgEdit.css( 'display', 'none' );
		}
	}
	
	addButton.on( 'click', function() {
		if( customUploader ) {
			customUploader.open();
		}	
	} );

	catImgEdit.on( 'click', function() {
		if( customUploader ) {
			customUploader.open();
		}
	} );
	
	customUploader.on( 'select', function() {
		var attachment = customUploader.state().get('selection').first().toJSON();
		image.attr( 'src', attachment.sizes.thumbnail.url );
		image.attr( 'width', '110' );
		hidden.attr( 'value', JSON.stringify( [
			{
				id: attachment.id,
				url: attachment.url,
				sizes: { thumbnail: attachment.sizes.thumbnail.url }
			}
		] ) );
		toogleVisibility( 'ADD' );
	} );

	removeButton.on( 'click', function() {
		image.removeAttr( 'src' );
		hidden.removeAttr( 'value' );
		$( '.cat-thumbnail' ).css( 'display', 'none' );
		toogleVisibility( 'DELETE' );
	} );

	window.addEventListener( 'DOMContentLoaded', function() {
		if( CAT_IMG_UPLOAD.CatImageData === "" || CAT_IMG_UPLOAD.CatImageData.length === 0 ) {
			toogleVisibility( 'DELETE' );
		} else {
			image.attr( 'src', CAT_IMG_UPLOAD.CatImageData.sizes.thumbnail.url );
			hidden.attr( 'value', JSON.stringify( [ CAT_IMG_UPLOAD.CatImageData ] ) );
			toogleVisibility( 'ADD' );
		}
	} );

});
