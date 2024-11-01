jQuery( document ).ready( function( $ ) {

	// Translation

	const { __, _x, _n, _nx } = wp.i18n;

	// Remove toolbar class as if this remains it pushes content down to make way for toolbar that has been hidden via CSS

	$( 'html' ).removeClass( 'wp-toolbar' );

});