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
	// Fonts
	wp.customize( 'first_edition_font_pair', function( value ) {
		value.bind( function( to ) {
			switch ( to )
			{
				case 'pair1':
					$( 'h1,h2,h3,h4,h5,h6' ).css( { 'font-family': '"Lustria", serif' } );
					$( 'body' ).css( { 'font-family': '"Lato", "Open Sans", sans-serif', 'font-weight': '300' } );
					break;

				case 'pair2':
					$( 'h1,h2,h3,h4,h5,h6' ).css( { 'font-family': '"Ubuntu", serif' } );
					$( 'body' ).css( { 'font-family': '"Lora", "Open Sans", sans-serif' } );
					break;

				case 'pair3':
					$( 'h1,h2,h3,h4,h5,h6' ).css( { 'font-family': '"Raleway", serif' } );
					$( 'body' ).css( { 'font-family': '"Merriweather", "Open Sans", sans-serif', 'font-weight': '300' } );
					break;

				case 'pair4':
					$( 'h1,h2,h3,h4,h5,h6' ).css( { 'font-family': '"Roboto Slab", serif' } );
					$( 'body' ).css( { 'font-family': '"Roboto", "Open Sans", sans-serif', 'font-weight': '300' } );
					break;

				case 'pair5':
					$( 'h1,h2,h3,h4,h5,h6' ).css( { 'font-family': '"Quattrocento", serif' } );
					$( 'body' ).css( { 'font-family': '"Quattrocento Sans", "Open Sans", sans-serif', 'font-weight': '300' } );
					break;

				default:
					$( 'h1,h2,h3,h4,h5,h6' ).css( { 'font-family': '"Quattrocento Sans", "Open Sans", sans-serif' } );
					$( 'body' ).css( { 'font-family': '"Quattrocento", serif' } );
					break;
			}
		} );
	} );
} )( jQuery );
