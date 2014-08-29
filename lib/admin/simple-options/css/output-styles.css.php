<?php

	$output = '';
	$input = '';

	if ( of_get_option('enable_custom_typography') ) :

		function options_typography_font_styles($option, $selectors) {
			$output = $selectors . ' {';
			$output .= ' color:' . $option['color'] .'; ';
			$output .= 'font-family:' . $option['face'] . '; ';
			$output .= 'font-weight:' . $option['style'] . '; ';
			$output .= 'font-size:' . $option['size'] . '; ';
			$output .= '}';
			$output .= "\n";
			return $output;
		}

		if ( of_get_option( 'enable_custom_typography' )['body'] ) {
			$input = of_get_option( 'custom_body_typography' );
			$output .= options_typography_font_styles( of_get_option( 'custom_body_typography' ) , 'html, body');
		}

		if ( of_get_option( 'enable_custom_typography' )['header'] ) {
			$input = of_get_option( 'custom_header_typography' );
			$output .= options_typography_font_styles( of_get_option( 'custom_header_typography' ) , 'h1,h2,h3,h4,h5,h6');
		}

		if ( of_get_option( 'enable_custom_typography' )['subheader'] ) {
			$input = of_get_option( 'custom_subheader_typography' );
			$output .= options_typography_font_styles( of_get_option( 'custom_subheader_typography' ) , '.subheader');
		}

	endif;

	if ( of_get_option( 'theme_color') ) :

		// .sbg(simple-background-color), .sbgo(simple-background-color-opaque), .sc(simple-color)
		// either manually attach classes to elements or by js

		$themeColor = HexToRGB(of_get_option('theme_color'));
		$themeColorOpaque = HexToRGB(of_get_option('theme_color'), .7);

		$themeColor = 'rgba(' . implode(",", $themeColor) .')';
		$themeColorOpaque = 'rgba(' . implode(",", $themeColorOpaque) .')';

		$output .='
			.sbg,
			::selection,
			.btn,
			.comments #respond form .form-submit input[type="submit"],

			.pace .pace-progress,

			.mejs-controls .mejs-time-rail .mejs-time-current,
			.mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current,
			.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
			.mejs-overlay:hover .mejs-overlay-button
			{
				background-color: '.$themeColor.';
			}

			.sbgo,
			.btn:hover,
			.comments #respond form .form-submit input[type="submit"]:hover{
				background-color: '.$themeColorOpaque.';
			}
			[data-modal-id] {
				background-color: '.$themeColorOpaque.';
			}
			[data-modal-style="effect-8"] {
				background-color: '.$themeColor.';
			}

			.sc,
			a:not(.btn),
			.reveal-overlay.ro-effect-1:hover figcaption .project-container-info h2 {
				color: '.$themeColor.';
			}

			#page-loader .loader{
				border-color: '.$themeColor.';
			}
			#page-loader .loader.alt {
				border: 4px solid '.$themeColorOpaque.';
				border-top: 4px solid '.$themeColor.';
			}
			.pace .pace-activity {
				border-top-color: '.$themeColor.';
				border-left-color: '.$themeColor.';
			}
		';

	endif;

	if ( of_get_option( 'custom_css') ) :

        echo of_get_option( 'custom_css');

    endif;

	if ( $output != '' ) {
		$output = "\n<style>\n" . preg_replace( '/\s+/', ' ', $output ) . "\n</style>\n";
		echo $output;
	}