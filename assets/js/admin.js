jQuery( document ).ready( function( $ ) {

	// Color key

	$( 'body' ).on( 'click', '.wpcs-content-scheduler-color-key', function( e ) {

		e.preventDefault();

		const removeAtIndex = ( arr, index ) => {

			const copy = [ ...arr ];
			copy.splice( index, 1 );
			return copy;

		};

		const toggle = ( arr, item, getValue = item => item ) => {

			const index = arr.findIndex( i => getValue( i ) === getValue( item ) );
			if ( index === -1 ) return [ ...arr, item ];
			return removeAtIndex( arr, index );

		};

		var postStatus = $(this).attr( 'data-post-status' );
		var postStatusOptions = $( '#wpcs-content-scheduler-filter-post-status' ).val();
		postStatusOptions = toggle( postStatusOptions, postStatus ); // Toggles the postStatus in postStatusOptions, e.g. if it exists remove it, if doesn't add it

		$( '#wpcs-content-scheduler-filter-post-status' ).val( postStatusOptions ).trigger( 'change' );

	});

	$( 'body' ).on( 'change', '#wpcs-content-scheduler-filter-post-status', function( e ) {

		var postStatusOptions = $(this).val();

		$( '.wpcs-content-scheduler-color-key' ).removeClass( 'wpcs-content-scheduler-color-key-selected' );

		if ( postStatusOptions.length > 0 ) {

			for ( var i = 0; i < postStatusOptions.length; i++ ) {

				$( '.wpcs-content-scheduler-color-key[data-post-status="' + postStatusOptions[i] + '"' ).addClass( 'wpcs-content-scheduler-color-key-selected' );
			}

		}

	});

	// Color Picker

	$( '.wpcs-content-scheduler-color-picker' ).wpColorPicker();

	// Select2

	$( '.wpcs-content-scheduler-select2' ).select2({
		sorter: data => data.sort( ( a, b ) => a.text.localeCompare( b.text ) ), // Sort options alphabetically
	});

});