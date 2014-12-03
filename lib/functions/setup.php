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


add_action( 'after_setup_theme', 'simple_setup', 10 );
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

	//  Redirect to theme options on theme activation
	if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' == $GLOBALS['pagenow'] ) {
		wp_redirect(admin_url('themes.php?page=options-framework'));
		exit;
	}

}