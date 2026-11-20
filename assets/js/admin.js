document.addEventListener( 'click', function ( event ) {
	var link = event.target.closest( '.urm-delete' );

	if ( link && ! window.confirm( 'Delete this redirect?' ) ) {
		event.preventDefault();
	}
} );