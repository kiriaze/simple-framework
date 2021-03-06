<?php

function simple_enqueue_scripts() {

	// Enqueue
    // if ( !is_admin() && current_theme_supports('jquery-cdn') ) {
	if ( current_theme_supports('jquery-cdn') ) {
		wp_deregister_script('jquery'); // Deregister WordPress jQuery
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js', false, '2.1.4', true); // Google CDN jQuery
		// add_filter('script_loader_src', 'simple_jquery_local_fallback', 10, 2);
	} else {
		// Gzip Compression
		global $compress_scripts, $concatenate_scripts;
		$compress_scripts = 1;
		$concatenate_scripts = 1;

		if ( defined('ENFORCE_GZIP') ) {
			define('ENFORCE_GZIP', true);
		}
	}

	wp_enqueue_script('jquery');
}


// // Admin Styles & Scripts
// function simple_enqueue_admin_scripts(){
// 	wp_register_style( 'admin',  get_stylesheet_directory_uri().'/admin/admin.css' );
// 	wp_register_script('admin', get_stylesheet_directory_uri().'/admin/admin.js', array('jquery'));
// 	wp_enqueue_style('admin');
// 	wp_enqueue_script('admin');
// });

// // Page Specific Scripts
// function simple_example_scripts() {
//     if ( is_singular( 'example' ) || is_post_type_archive('example') ) {
//         wp_enqueue_script('example');
//     }
// }


add_action( 'wp_enqueue_scripts', 'simple_enqueue_scripts' );
// add_action('admin_enqueue_scripts', 'simple_enqueue_admin_scripts' );
// add_action('wp_enqueue_scripts', 'simple_example_scripts');


// http://wordpress.stackexchange.com/a/12450
function simple_jquery_local_fallback($src, $handle = null) {
	static $add_jquery_fallback = false;

	if ( $add_jquery_fallback ) {
		echo '<script>window.jQuery || document.write(\'<script src="' . get_stylesheet_directory_uri() . '/assets/js/vendor/jquery-2.0.3.min.js"><\/script>\')</script>' . "\n";
		$add_jquery_fallback = false;
	}

	if ( $handle === 'jquery' ) {
		$add_jquery_fallback = true;
	}

	return $src;
}
add_action('wp_footer', 'simple_jquery_local_fallback');