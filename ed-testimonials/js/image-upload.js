var addButton = document.getElementById( 'img-upload-button' );
var removeButton = document.getElementById( 'img-delete-button' );
var image = document.getElementById( 'image-profile' );
var hidden = document.getElementById( 'img-hidden-field' );
var customUploader = wp.media({
	title: 'Choose Image',	
	button: {
		text: 'Choose Image'
	},
	multiple: false
});

var toogleVisibility = function( action ) {
	if( action === 'ADD' ) {
		addButton.style.display = 'none';
		removeButton.style.display = '';
	}
	
	if( action === 'DELETE' ) {
		addButton.style.display = '';
		removeButton.style.display = 'none';
	}
}

addButton.addEventListener( 'click', function() {
	if( customUploader ) {
		customUploader.open();
	}	
} );

customUploader.on( 'select', function() {
	var attachment = customUploader.state().get('selection').first().toJSON();
	var test = 0;
	image.setAttribute( 'src', attachment.url );
	hidden.setAttribute( 'value', JSON.stringify( [ { id: attachment.id, url: attachment.url } ] ) );
	toogleVisibility( 'ADD' );
} );

removeButton.addEventListener( 'click', function() {
	image.removeAttribute( 'src' );
	hidden.removeAttribute( 'value' );
	toogleVisibility( 'DELETE' );
} );

window.addEventListener( 'DOMContentLoaded', function() {
	if( JOB_PROFILE_IMG_UPLOAD.imageData === "" || JOB_PROFILE_IMG_UPLOAD.imageData.length === 0 ) {
		toogleVisibility( 'DELETE' );
	} else {
		image.setAttribute( 'src', JOB_PROFILE_IMG_UPLOAD.imageData.url );
		hidden.setAttribute( 'value', JSON.stringify( [ JOB_PROFILE_IMG_UPLOAD.imageData ] ) );
		toogleVisibility( 'ADD' );
	}
} );