/**
 * keyboard-shortcuts.js
 *
 * Handles keyboard shortcuts for things like post and image navigation.
 */
jQuery( document ).ready( function( $ ) {
	$( document ).keyup( function( e ) {
		var url = false;
		if ( e.which == 37 ) {  // Left arrow key code
			url = $( 'a[rel="prev"], .prev a' ).attr( 'href' );
		}
		else if ( e.which == 39 ) {  // Right arrow key code
			url = $( 'a[rel="next"], .next a' ).attr( 'href' );
		}
		else if ( e.which == 191 ) {  // Forward slash key code
			$( '.site-header .search-form .search-field' ).focus();
		}
		if ( url && ( !$( 'textarea, input' ).is( ':focus' ) ) ) {
			window.location = url;
		}
		// Find key codes at http://www.scripttheweb.com/js/ref/javascript-key-codes/
	} );
} );
