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

// register them in dropdown
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