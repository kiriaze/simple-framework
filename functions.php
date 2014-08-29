<?php
/**
 * Simple 1.0
 * Simple is a sleek, intuitve approach to wordpress website development designed for uncluttered and sophisticated experiences.
 *
 * This file enables the core features of Simple including sidebars, menus, post thumbnails, post formats, header, backgrounds, and more.
 * Some functions are able to be overridden using child themes. These functions will be wrapped in a function_exists() conditional.
 * They are auto loaded from within functions directory.
 *
 * @package     WordPress
 * @subpackage  Simple
 * @version     1.0
*/

require_once locate_template( '/lib/functions/constants.php', true );
require_once locate_template( '/lib/functions/setup.php', true );

add_action( 'after_setup_theme', 'load_functions', 11 );
function load_functions() {
	require_once locate_template( '/lib/functions/acf.php', true );
	require_once locate_template( '/lib/functions/admin.php', true );
	require_once locate_template( '/lib/functions/breadcrumbs.php', true );
	require_once locate_template( '/lib/functions/comments.php', true );
	require_once locate_template( '/lib/functions/cpt-archive-menu.php', true );
	require_once locate_template( '/lib/functions/custom-templates.php', true );
	require_once locate_template( '/lib/functions/gallery-override.php', true );
	require_once locate_template( '/lib/functions/helpers.php', true );
	require_once locate_template( '/lib/functions/menus.php', true );
	require_once locate_template( '/lib/functions/options.php', true );
	require_once locate_template( '/lib/functions/plugins.php', true );
	require_once locate_template( '/lib/functions/scripts-styles.php', true );
	require_once locate_template( '/lib/functions/search.php', true );
	require_once locate_template( '/lib/functions/sidebars.php', true );
	require_once locate_template( '/lib/functions/simple-actions.php', true );
	require_once locate_template( '/lib/functions/simple-login.php', true );
	require_once locate_template( '/lib/functions/simple-rewrites.php', true );
	require_once locate_template( '/lib/functions/simple-wrapper.php', true );
	require_once locate_template( '/lib/functions/wordpress-resets.php', true );
}