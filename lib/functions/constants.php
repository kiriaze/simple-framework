<?php

/**
 * Sets up global theme constants.
 *
 * @package   WordPress
 * @subpackage  Simple
 * @version   1.0
 * @author    Constantine Kiriaze
 */


/* DEFINE THEME DIRECTORY LOCATION CONSTANTS */
define( 'PARENT_DIR', get_template_directory() );
define( 'CHILD_DIR', get_stylesheet_directory() );

/* DEFINE THEME URL LOCATION CONSTANTS */
define( 'PARENT_URL', get_template_directory_uri() );
define( 'CHILD_URL', get_stylesheet_directory_uri() );

/* DEFINE THEME INFO CONSTANTS */
$theme 			= wp_get_theme();
$theme_title 	= $theme->name; 
$theme_version 	= $theme->version;

define( 'SIMPLE_THEME_SLUG', get_template() ); 	// theme folder. else, strntolower($theme_title)
define( 'SIMPLE_THEME_NAME', $theme_title ); 	// style.css name
define( 'SIMPLE_THEME_VER', $theme_version );

/*	DEFINE GENERAL CONSTANTS
================================================== */
define( 'SIMPLE_IMAGES_DIR', PARENT_DIR . '/assets/images' );
define( 'SIMPLE_LIB_DIR', PARENT_DIR . '/lib' );
define( 'SIMPLE_JS_DIR', PARENT_DIR . '/assets/js' );
define( 'SIMPLE_CSS_DIR', PARENT_DIR . '/assets/css' );
define( 'SIMPLE_FUNCTIONS_DIR', SIMPLE_LIB_DIR . '/functions' );
define( 'SIMPLE_CONTENT_DIR', PARENT_DIR . '/content' );
define( 'SIMPLE_LANGUAGES_DIR', PARENT_DIR . '/assets/lang' );

define( 'SIMPLE_IMAGES_URL', PARENT_URL . '/assets/images' );
define( 'SIMPLE_LIB_URL', PARENT_URL . '/lib' );
define( 'SIMPLE_JS_URL', PARENT_URL . '/assets/js' );
define( 'SIMPLE_CSS_URL', PARENT_URL . '/assets/css' );
define( 'SIMPLE_FUNCTIONS_URL', SIMPLE_LIB_URL . '/functions' );


/*	DEFINE ADMIN CONSTANTS
================================================== */
define( 'SIMPLE_ADMIN_DIR', SIMPLE_LIB_DIR . '/admin' );
define( 'SIMPLE_ADMIN_IMAGES_DIR', SIMPLE_LIB_DIR . '/admin/assets/images' );
define( 'SIMPLE_ADMIN_CSS_DIR', SIMPLE_LIB_DIR . '/admin/assets/css' );
define( 'SIMPLE_ADMIN_JS_DIR', SIMPLE_LIB_DIR . '/admin/assets/js' );
		
define( 'SIMPLE_ADMIN_URL', SIMPLE_LIB_URL . '/admin' );
define( 'SIMPLE_ADMIN_IMAGES_URL', SIMPLE_LIB_URL . '/admin/assets/images' );
define( 'SIMPLE_ADMIN_CSS_URL', SIMPLE_LIB_URL . '/admin/assets/css' );
define( 'SIMPLE_ADMIN_JS_URL', SIMPLE_LIB_URL . '/admin/assets/js' );
	
define( 'SIMPLE_FRAMEWORK_VERSION', '1.0' );