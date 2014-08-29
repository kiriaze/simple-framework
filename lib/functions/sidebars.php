<?php

//	Register Sidebars
$simple_sidebars = array( 'Default', 'Blog');
$footer_widgets = of_get_option('footer_multi_checkbox')['footer_widgets_checkbox'];
if ( $footer_widgets ) {
	array_push($simple_sidebars, 'Footer Widget 1', 'Footer Widget 2', 'Footer Widget 3', 'Footer Widget 4');
}

foreach ( $simple_sidebars as $sidebar ) {
	$sidebar_args = array(
		'name'			=> $sidebar,
		'id'			=> 'sidebar_'.preg_replace('/\W/', '_', strtolower($sidebar) ),
		'before_widget'	=>	'<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h6 class="widgettitle">',
		'after_title'	=> '</h6>'	
	);
	register_sidebar($sidebar_args);
	
}