/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			// class specific
			$( 'a.logo' ).text( to );
		});
	});
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.hero-title' ).text( to );
			$( '.hero-title' ).slabText(); // loaded in child theme, live editing!
		});
	});

	// alert('foo'); // run this to reset cache..
	
	//	Update site theme color in real time
	wp.customize( 'simple_options[theme_color]', function( value ) {
		value.bind( function( newval ) {
			$('body, .btn, .btn, .comments #respond form .form-submit input[type="submit"], .comments #respond form .form-submit input[type="submit"]:hover, .pricing-tables .package.recommended .package-name div, .pricing-tables .package.recommended .package-price, .mejs-overlay:hover .mejs-overlay-button, .mejs-controls .mejs-time-rail .mejs-time-current, .mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current, .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .grid-framework-example .center-block, .package.type-featured .package-title, ::selection').css('background-color', newval );

			$('a:not(.btn), .reveal-overlay.ro-effect-1:hover figcaption .project-container-info h2').css('color', newval );
		});
	});


} )( jQuery );