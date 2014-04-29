/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );
	// Logo
	wp.customize( 'first_edition_logo', function( value ) {
		value.bind( function( to ) {

			if ( '' != to ) {
				$( '.site-branding' ).css( {
					'background': 'url(' + to + ') no-repeat',
					'padding-left': '90px'
				} );
			}
		} );
	} );
	// Colors
	wp.customize( 'first_edition_colors[link]', function( value ) {
		value.bind( function( to ) {

				$( 'a' ).css( {
					'color': to,
				} );
		} );
	} );
	wp.customize( 'first_edition_colors[text]', function( value ) {
		value.bind( function( to ) {

				$( 'body, a:hover, a:focus, a:active, .main-navigation ul .current_page_item > a' ).css( {
					'color': to,
				} );
				// Using .css() with :hover doesn't seem to work in the customizer, this is a workaround.
				$( '#commentform' ).before(
					'<style type="text/css"> .comment-form input[type="submit"]:hover { background: ' + to + '; } </style>'
				);
		} );
	} );
} )( jQuery );
