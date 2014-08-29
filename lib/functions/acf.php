<?php

function simple_acf($theme) {

	/**
	 *  Register Field Groups
	 *
	 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
	 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
	 */

	/*
	*	Post Formats
	*/

	if( function_exists("register_field_group") ) {
		register_field_group(array (
			'id' => 'acf_post-formats',
			'title' => 'Post Formats',
			'fields' => array (
				array (
					'key' => 'field_525706d5b8ad3',
					'label' => 'Quote',
					'name' => 'simple-quote',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_52609520ddddd',
					'label' => 'Quote Author',
					'name' => 'simple-quote-author',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_525704196f3b5',
					'label' => 'Link Text',
					'name' => 'simple-link-text',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_5260960bcdbee',
					'label' => 'Link URL',
					'name' => 'simple-link-url',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				// array (
				// 	'key' => 'field_52570473266ae',
				// 	'label' => 'Video URL',
				// 	'name' => 'simple-video-url',
				// 	'type' => 'text',
				// 	'default_value' => '',
				// 	'placeholder' => '',
				// 	'prepend' => '',
				// 	'append' => '',
				// 	'formatting' => 'html',
				// 	'maxlength' => '',
				// ),
				array (
					'key' => 'field_52570491266af',
					'label' => 'Audio URL',
					'name' => 'simple-audio-url',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'work', // added post formats to work cpt
						'order_no' => 1,
						'group_no' => 1,
					),
				),
			),
			'options' => array (
				'position' => 'normal',
				'layout' => 'default',
				'hide_on_screen' => array (
				),
			),
			'menu_order' => 0,
		));
	}

}

add_action( 'init', 'simple_acf', 0 ); // priority hack