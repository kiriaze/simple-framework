<?php

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Simple supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, post thumbnails, featured content, and infinite scroll.
 * @uses flush_rewrite_rules() To reset permalinks on theme activation.
 * @uses set_post_thumbnail_size() and add_image_size To set default custom post thumbnail size and others.
 *
 * @package   WordPress
 * @subpackage  Simple
 * @version   1.0
 * @author    Constantine Kiriaze
 */


add_action( 'after_setup_theme', 'simple_setup', 9 );
function simple_setup() {

	// Text Domain / Localization
	load_theme_textdomain( strtolower(SIMPLE_THEME_SLUG), get_stylesheet_directory() . '/theme/assets/lang' );

	// Automatic Feed Links (RSS)
	add_theme_support('automatic-feed-links');

	// HTML5 -Switches default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	));

	// Post Formats
	add_theme_support( 'post-formats', array(
		'quote',
		'link',
		'video',
		'audio',
		'aside',
		'gallery',
		'image'
	));

	// Post Thumbnails (Featured Images / Sizes)
	add_theme_support( 'post-thumbnails' );

	// Simple Framework Supports
	add_theme_support('simple-relative-urls');  	//  Enable relative URLs
	add_theme_support('simple-rewrites');       	// 	Enable URL rewrites for only parent theme
	add_theme_support('simple-breadcrumbs'); 		//  Enable breadcrumbs
	add_theme_support('debug'); 					//  Enable debug bar
	add_theme_support('admin_bar'); 				//  Enable admin bar
	add_theme_support('jquery-cdn');            	//  Enable to load jQuery from the Google CDN. Issue with infinite scroll if enabled, include migrate

	// remove_theme_support in child theme if undesired, all enabled by default
	add_theme_support('custom_searchform');			//	Enable use of custom searchform template - /templates/searchform.php
	add_theme_support('nice-search');				//	Enables clean search in url; from /?s= to /search/result
	add_theme_support('theme-options-setup');		//	Enable Setup tab in theme options
	add_theme_support('more-themes-link');			//	Enable more theme links under dashboard menu
	add_theme_support('admin-footer-text');			//	Enable extra text in admin footer
	add_theme_support('remove_admin_menu_items');	//	Remove Unwanted Admin Menu Items(left hand side)
	add_theme_support('remove_admin_bar_links');	//	Remove Unwanted Admin Menu Items(from admin bar)

	//  Redirect to theme options on theme activation
	if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' == $GLOBALS['pagenow'] ) {
		wp_redirect(admin_url('themes.php?page=options-framework'));
		exit;
	}

}