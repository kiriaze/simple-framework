<?php

/*
 * Remove Unwanted Admin Menu Items(from admin bar)
 */
if ( current_theme_supports('remove_admin_bar_links') ) :
	function remove_admin_bar_links() {
	    global $wp_admin_bar;
	    $elements = array('wp-logo', 'about', 'wporg', 'documentation', 'support-forums', 'feedback', 'updates', 'comments', 'new-content');
	    foreach ($elements as $element) {
	      $wp_admin_bar->remove_menu($element);
	    }
	}
	add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
endif;


/*
 * Remove Unwanted Admin Menu Items(left hand side)
 *
 * Populate $menu_items array to exclude Admin Menu Items. There's a list of common elements:
 * Appearance, Comments, Links, Media, Pages, Plugins, Posts, Settings, Tools, Users
 * ToDo: make into an array to include/exclude from child
 */

if ( current_theme_supports('remove_admin_menu_items') ) :
	function remove_admin_menu_items() {

		$menu_items = array(
			__('Comments', SIMPLE_THEME_SLUG),
			__('Links', SIMPLE_THEME_SLUG),
			__('Posts', SIMPLE_THEME_SLUG),
			__('Appearance', SIMPLE_THEME_SLUG),
			__('Plugins', SIMPLE_THEME_SLUG),
			__('Tools', SIMPLE_THEME_SLUG),
			__('Settings', SIMPLE_THEME_SLUG),
			__('Media', SIMPLE_THEME_SLUG)
		);
		global $menu;
		end( $menu );
		while( prev($menu) ){
			$item = explode( ' ', $menu[key($menu)][0] );
			if( in_array( $item[0] != NULL ? $item[0] : "" ,$menu_items ) ){
				unset( $menu[key($menu)] );
			}
		}
	}
	add_action('admin_menu', 'remove_admin_menu_items');
endif;