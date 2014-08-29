<?php

/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 * Requires plugin to be installed and activated
 */
function simple_nice_search_redirect() {
	global $wp_rewrite;
	if ( !isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks() ) {
		return;
	}

	$search_base = $wp_rewrite->search_base;
	
	if ( is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false ) {
		wp_redirect( home_url( "/{$search_base}/" . urlencode(get_query_var('s')) ) );
		exit();
	}

}

// Hotfix for http://core.trac.wordpress.org/ticket/13961 for WP versions less than 3.5
if ( version_compare( $wp_version, '3.5', '<=' ) ) {
	function simple_nice_search_urldecode_hotfix( $q ) {
		if ( $q->get( 's' ) && empty( $_GET['s'] ) && is_main_query() )
			$q->set( 's', urldecode( $q->get( 's' ) ) );
	}
	add_action( 'pre_get_posts', 'simple_nice_search_urldecode_hotfix' );
}

if ( current_theme_supports('nice-search') ) {
	add_action('template_redirect', 'simple_nice_search_redirect');
}


/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function simple_request_filter($query_vars) {
	if (isset($_GET['s']) && empty($_GET['s'])) {
		$query_vars['s'] = ' ';
	}

	return $query_vars;
}
add_filter('request', 'simple_request_filter');

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
if ( current_theme_supports('custom_searchform') ) :
	function simple_get_search_form($form) {
		$form = '';
		locate_template('/templates/searchform.php', true, false);
		return $form;
	}
	add_filter('get_search_form', 'simple_get_search_form');
endif;