<?php

if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
    	array(
    		'main-menu' 	=> 'Main Menu',
    		'footer-menu' 	=> 'Footer Menu',
    		'mobile-menu' 	=> 'Mobile Menu',
		)
	);
}

if( !function_exists('simple_menu_output') ) {

	function simple_menu_output($args=array()){

		$defaults = array(
			'theme_location'  => '',
			'menu'            => '',
			'container'       => false,
			'container_class' => '',
			'container_id'    => '',
			'menu_class'      => 'menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => '',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		);

		$options = array_merge($defaults, $args);

		echo wp_nav_menu($args);

	}

}

// initial menu registration in admin
add_action('after_setup_theme', 'initial_menu_setup', 10);
function initial_menu_setup() {

	// register menus in dropdown
	$menus = get_registered_nav_menus();
	foreach ( $menus as $key => $value ) {

		$simple_nav_mod = false;

		$main_menu = wp_get_nav_menu_object($value);

		if ( !$main_menu ) {
			$main_menu_id = wp_create_nav_menu($value, array('slug' => $value));
			$simple_nav_mod[$value] = $main_menu_id;
		} else {
			$simple_nav_mod[$value] = $main_menu->term_id;
		}

		if ( $simple_nav_mod ) {
			set_theme_mod('nav_menu_locations', $simple_nav_mod);
		}
	}

}

// Get Nested Menu Array Output
// Usage
// $menu = new NestedMenu('menu-name');
// foreach ( $menu->items as $item ) :
// sp($item);
// $submenu = $menu->get_submenu($item);
// if ( $submenu ) :
// foreach ( $submenu as $subitem ) :
// sp($subitem);
// endforeach;
// endif;
// endforeach;
class NestedMenu {

    private $flat_menu;
    public $items;

    function __construct($name) {

		$this->flat_menu = wp_get_nav_menu_items($name);
		$this->items     = array();

		if ( $this->flat_menu ) {
			foreach ( $this->flat_menu as $item ) {
			    if ( !$item->menu_item_parent ) {
			        array_push($this->items, $item);
			    }
			}
		}

    }

    public function get_submenu($item) {
        $submenu = array();
        foreach ( $this->flat_menu as $subitem ) {
            if ( $subitem->menu_item_parent == $item->ID ) {
                array_push($submenu, $subitem);
            }
        }
        return $submenu;
    }

}



// Return sub menus only, accepts multiple through arrays
// array(
// 	'menu'      => 'mega-menu',
// 	'submenu'   => array('Page Name'),
// 	'container' => '',
// );
add_filter('wp_nav_menu_objects', 'nav_submenu_objects_filter', 10, 2);
function nav_submenu_objects_filter( $items, $args ) {
	
	// $loc should be an array of items. if it's empty, move along
	$loc = isset( $args->submenu ) ? $args->submenu : '';
	
	if ( !isset($loc) || empty($loc) ) {
		return $items;
	}
	
	if ( is_string($loc) ) {
		$loc = split("/", $loc);
	}

	if ( empty($loc) ) {
		return $items;
	}

 	// prepare a slug for every item
	foreach ( $items as $item ) {
		if ( empty($item->slug) ) {
			$item->slug = sanitize_title_with_dashes($item->title);
		}
	}

	//  find the selected parent item ID(s)
	$cursor = 0;
	foreach ( $loc as $slug ) {
		$slug = sanitize_title_with_dashes($slug);
		foreach ( $items as $item ) {
			if ( $cursor == $item->menu_item_parent && $slug == $item->slug ) {
				$cursor = $item->ID;
				continue 2;
			}
		}
		return array();
	}

 	//  walk finding items until all levels are exhausted
	$parents = array($cursor);
	$out     = array();

	while ( !empty($parents) ) {

		$newparents = array();

		foreach ( $items as $item ) {
			if ( in_array( $item->menu_item_parent, $parents ) ) {
				if ( $item->menu_item_parent == $cursor ) {
					$item->menu_item_parent = 0;
				}
				$out[]        = $item;
				$newparents[] = $item->ID;
			}
		}

		$parents = $newparents;
	}

	return $out;
}