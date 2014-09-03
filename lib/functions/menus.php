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