<?php

// Change login page style adding your custom css in $output
function simple_login_head() {
    
    wp_enqueue_style( 'simple_login_head', get_stylesheet_directory_uri() . '/assets/css/login.css', false );
	
    wp_enqueue_script('jquery');
	wp_enqueue_script( 'login', get_stylesheet_directory_uri() . '/assets/login/login.js' );

    if ( of_get_option( 'login_logo') ) :
        $output = '
            .login h1 a {
                background: url('. of_get_option( 'login_logo') .') no-repeat center !important;
                text-indent: -9999em !important;
            }
        ';
        echo "\n<style>\n" . preg_replace( '/\s+/', ' ', $output ) . "\n</style>\n";
    endif;

}

function simple_login_url() {
    return home_url();
}

function simple_login_title() {
    // return 'Simple';
    // return get_bloginfo('site');
    return get_option( 'blogname' );
}

// Enable custom login style uncommenting the line below
add_action('login_enqueue_scripts', 'simple_login_head', 10);
// Change url of logo for login screen uncommenting the line below
add_filter( 'login_headerurl', 'simple_login_url' );
// Change title for login screen uncommenting the line below
add_filter( 'login_headertitle', 'simple_login_title' );