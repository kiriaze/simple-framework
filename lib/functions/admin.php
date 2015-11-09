<?php


/*	Show Admin bar for admins if theme supports it and add body class
**  Otherwise hide it
================================================== */

if ( current_theme_supports('admin_bar') && current_user_can( 'manage_options' ) ) {
	add_filter('show_admin_bar', 'remove_admin_bar');
	function remove_admin_bar() {
		return true;
	}
	add_filter( 'body_class', 'admin_bar_body_class', 20 );
	function admin_bar_body_class( $classes ){
		$classes[] = 'show-admin-bar'; // so as to add top/margin on elems
		return $classes;
	}
} else {
	add_filter('show_admin_bar', 'remove_admin_bar');
	function remove_admin_bar() {
		return false;
	}
}



/*
 * Remove Unwanted Admin Menu Items(from admin bar)
 * Filterable: $args['users'] = array(); $args['links'] = array(); return $args;
 */
if ( current_theme_supports('remove_admin_bar_links') ) :
	
	add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
	
	function remove_admin_bar_links() {
		
		global $wp_admin_bar;
		
		$args  = apply_filters('sf_remove_admin_bar_links', array());
		$users = array_key_exists('users', $args) ? $args['users'] : [];
		$links = array_key_exists('links', $args) ? $args['links'] : [];

		// users to exclude. e.g. multiple admins, exclude targeting main admin
		if ( in_array( wp_get_current_user()->ID, $users ) ) return;

		// not removing any by default, since no clean way to unset them from child. $elements[] passed from child
		$elements = array(
			// 'wp-logo',
			// 'about',
			// 'wporg',
			// 'documentation',
			// 'support-forums',
			// 'feedback',
			// 'updates',
			// 'comments',
			// 'new-content'
		);

		$elements = array_merge($elements, $links);
		foreach ( $elements as $element ) {
			$wp_admin_bar->remove_menu($element);
		}
	}
endif;


/*
 * Remove Unwanted Admin Menu Items(left hand side)
 *
 * Populate $menu_items array to exclude Admin Menu Items. There's a list of common elements:
 * Appearance, Comments, Links, Media, Pages, Plugins, Posts, Settings, Tools, Users
 * Filterable: $args['users'] = array(); $args['items'] = array(); return $args;
 */

if ( current_theme_supports('remove_admin_menu_items') ) :

	add_action('admin_menu', 'remove_admin_menu_items', 100); // ran after everything to access menu items added late from plugins

	function remove_admin_menu_items() {

		$args  = apply_filters('sf_remove_admin_menu_items', array());
		$users = array_key_exists('users', $args) ? $args['users'] : [];
		$items = array_key_exists('items', $args) ? $args['items'] : [];

		// users to exclude. e.g. multiple admins, exclude targeting main admin
		if ( in_array( wp_get_current_user()->ID, $users ) ) return;

		// not removing any by default, since no clean way to unset them from child. $menu_items[] passed from child
		$menu_items = array(
			// __('Comments', SIMPLE_THEME_SLUG),
			// __('Links', SIMPLE_THEME_SLUG),
			// __('Posts', SIMPLE_THEME_SLUG),
			// __('Appearance', SIMPLE_THEME_SLUG),
			// __('Plugins', SIMPLE_THEME_SLUG),
			// __('Tools', SIMPLE_THEME_SLUG),
			// __('Settings', SIMPLE_THEME_SLUG),
			// __('Media', SIMPLE_THEME_SLUG)
		);

		$menu_items = array_merge( $menu_items, $items );

		global $menu;
		
		foreach ( $menu as $key => $item ) {
			$item_name = $item[0] != NULL ? $item[0] : '';
			// if html tags in name, strip em. e.g. Comments/Plugins
			if ( strpos($item_name,'<') !== false ) {
				$item_name = strstr($item_name, ' <', true);
			}
			if ( in_array( $item_name, $menu_items ) ) {
				unset( $menu[$key] );
			}
		}

	}

endif;