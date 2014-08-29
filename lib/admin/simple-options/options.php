<?php
/**
 * @package 	WordPress
 * @subpackage 	Simple
 * @version 	1.0
 * @author 		Constantine Kiriaze
*/

/*
1. Setup
2. General
	a. logo
	b. login logo
	c. retina logo
	d. text logo
	e. favicon
	f. web app icon
	g. front page displays
		i. home set page
		ii. blog set page
	h. general enabling
		i. smooth scroll
		ii. back to top
		iii. boxed layout
		iv. responsive layout
		v. display breadcrumbs
	i. google analytics
	j. custom css
3. Social
	a. fb
	b. twitter
	c. google
	d. instagram
	e. github
	f. pinterest
	g. linkedin
	h. dribbble
4. Header Enabling
	a. header logo
	b. social checkbox
5. Footer
	a. copyright text
	b. widgets
	c. social
6. Blog
	a. layout
	b. avatar
	c. archive
		i. posts per page
		ii. excerpt length
		iii. excerpt more text
		iv. blog post meta
		v. blog enabling
			a. sharing
			b. pagination
			c. infinite scroll
			d. blog authors
			e. disable comments
7. Contact
	a. default form
	b. contact form
	c. contact form button
*/

// optionsframework_option_name() is set in options.php so it runs before !function_exists('of_get_option') setting the default option id to $themename -> simple_options rather than optionsframework_THEMENAME
// Alternative is to remove function in functions.php that autoloads all function and use the traditonal require_once('file.php');

function optionsframework_options() {

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/lib/admin/simple-options/images/';

	// Layout Options
	$layout_options = array(
		'no_sidebar_layout' 	=> $imagepath . '1col.png',
		'left_sidebar_layout' 	=> $imagepath . '2cl.png',
		'right_sidebar_layout' 	=> $imagepath . '2cr.png'
	);

	// Blog post meta defaults
	$blog_postmeta_defaults = array(
		'post_author' 	=> '1',
		'post_tags' 	=> '1'
	);

	// Blog post meta array
	$blog_postmeta_array = array(
		'post_author' 			=> __('Author', SIMPLE_THEME_SLUG),
		'post_date' 			=> __('Date', SIMPLE_THEME_SLUG),
		'post_category'			=> __('Category', SIMPLE_THEME_SLUG),
		'post_comments' 		=> __('Comments', SIMPLE_THEME_SLUG),
		'post_tags'	 			=> __('Tags', SIMPLE_THEME_SLUG),
		'post_wordcount'	 	=> __('Word Count', SIMPLE_THEME_SLUG),
		'post_reading_time'	 	=> __('Reading Time', SIMPLE_THEME_SLUG),
		'post_view_count'	 	=> __('Post Views', SIMPLE_THEME_SLUG)
	);

	// Pull all the pages into an array
	$simple_pages = array();
	$simple_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$simple_pages[''] = 'Select a page:';
	foreach ($simple_pages_obj as $page) {
		$simple_pages[$page->ID] = $page->post_title;
	}

	$options = array();

	/*==============================
	S E T U P
	==============================*/
	if ( current_theme_supports('theme-options-setup') ) :

		$options[] = array(
			'name' => __('Setup', SIMPLE_THEME_SLUG),
			'type' => 'heading',
			'desc' => __('Initial site setup. WIP.', SIMPLE_THEME_SLUG)
		);

		// Custom Setup on theme activation
		$options['theme_activation_setup'] = array(
			'name' 		=> __('Theme Activation Setup', SIMPLE_THEME_SLUG),
			'desc' 		=> __('Pick which options you would like for quickly setting up your website.', SIMPLE_THEME_SLUG),
			'id' 		=> 'theme_activation_setup',
			'std' 		=> array(
				''	=>	''
			),
			'type' 		=> 'multicheck',
			'options' 	=> array(
				'create_front_page'			=>	'Create static front page titled Home',
				'set_permalink_structure'	=>	'Set permalink structure to /&#37;postname&#37;/',
				'change_uploads_directory'	=>	'Change uploads directory to /media/',
				'create_main_menu'			=>	'Create main navigation menu',
				'add_pages_to_main_menu'	=>	'Add published pages to the main menu'
			)
		);

	endif;


	/*==============================
	G E N E R A L
	==============================*/
	$options[] = array(
		'name' => __('General', SIMPLE_THEME_SLUG),
		'type' => 'heading',
		'desc' => __('General site settings.', SIMPLE_THEME_SLUG)
	);



	// Info Break
	$options[] = array(
		'name' => 'Logos',
		'desc' => 'Site logo, admin logo, and alternative text logo settings.',
		'type' => 'info'
	);

	// Logo
	$options['logo'] = array(
		'name' 	=> __('Logo', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Upload a logo for your website.', SIMPLE_THEME_SLUG),
		'id' 	=> 'logo',
		'type' 	=> 'upload'
	);

	// Login Logo
	$options['login_logo'] = array(
		'name' 	=> __('Login Logo', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Upload a logo for your admin login section.', SIMPLE_THEME_SLUG),
		'id' 	=> 'login_logo',
		'type' 	=> 'upload'
	);

	// Retina Logo
	$options['retina_logo'] = array(
		'name' 	=> __('Retina Logo', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Upload a retina logo for your website.', SIMPLE_THEME_SLUG),
		'id' 	=> 'retina_logo',
		'type' 	=> 'upload'
	);

	// Text Logo
	$options['text_logo'] = array(
		'name' 	=> __('Text Logo', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Text logo for your website. If set, overrides general settings site title (will still be used as meta title)', SIMPLE_THEME_SLUG),
		'id' 	=> 'text_logo',
		'type' 	=> 'text',
		'std'	=> 'Awesome Site'
	);


	// Info Break
	$options[] = array(
		'name' => 'Icons',
		'desc' => 'Favicon and Web App icons.',
		'type' => 'info'
	);

	// Favicon
	$options['favicon'] = array(
		'name' 	=> __('Favicon', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Upload an image to be set as your favicon. Preferred dimensions are 16x16.', SIMPLE_THEME_SLUG),
		'id' 	=> 'favicon',
		'type' 	=> 'upload'
	);

	// Web App Icon
	$options['web_app_icon'] = array(
		'name' 	=> __('Web App Icon', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Upload an image to be set as your web app icon. Preferred dimensions are 144x144.', SIMPLE_THEME_SLUG),
		'id' 	=> 'web_app_icon',
		'type' 	=> 'upload'
	);


	// Info Break
	$options[] = array(
		'name' => 'Other',
		'desc' => 'All other general options. Front page, blog page, analytics, custom css, etc.',
		'type' => 'info'
	);


	// Front Page Displays
	$options['front_page_displays'] = array(
		'name' 	=> __('Front Page Displays', SIMPLE_THEME_SLUG),
		'desc' 	=> __('A static page. Defaults to your latest posts.', SIMPLE_THEME_SLUG),
		'id' 	=> 'front_page_displays',
		'std' 	=> 'latest_posts',
		'type' 	=> 'radio',
		'options'	=>	array(
			'latest_posts'	=>	'Your latest posts',
			'static_page'	=>	'A static page',
		)
	);

		// Select page to be set as home, updating reading settings
		$options['home_set_page'] = array(
			'name' 		=> __('Set Home Page', SIMPLE_THEME_SLUG),
			'desc' 		=> __('Select a page to be set as your home page, updating your reading settings.', SIMPLE_THEME_SLUG),
			'id' 		=> 'home_set_page',
			'type' 		=> 'select',
			'class'		=> 'hidden',
			'options' 	=> $simple_pages
		);

		// Select page to be set as blog, updating reading settings
		$options['blog_set_page'] = array(
			'name' 		=> __('Set Blog Page', SIMPLE_THEME_SLUG),
			'desc' 		=> __('Select a page to be set as your blog page, updating your reading settings.', SIMPLE_THEME_SLUG),
			'id' 		=> 'blog_set_page',
			'type' 		=> 'select',
			'class'		=> 'hidden',
			'options' 	=> $simple_pages
		);

	// General Multi Checkboxes
	$options['general_multi_checkbox'] = array(
		'name' 		=> __('General Enabling', SIMPLE_THEME_SLUG),
		'desc' 		=> __('Pick which options you would like for quickly setting up your website.', SIMPLE_THEME_SLUG),
		'id' 		=> 'general_multi_checkbox',
		'std' 		=> array(
			'smooth_scroll'			=>	'0',
			'back_to_top'			=>	'0',
			'boxed_layout'			=>	'0',
			'responsive_layout'		=>	'1',
			'display_breadcrumbs'	=>	'1',
			// 'enable_retina'			=>	'0'
		),
		'type' 		=> 'multicheck',
		'options' 	=> array(
			'smooth_scroll'			=>	__('Enable smooth scrolling sitewide. Defaults to false.', SIMPLE_THEME_SLUG),
			'back_to_top'			=>	__('Check to enable a link that scrolls the body back to the top. Defaults to false.', SIMPLE_THEME_SLUG),
			'boxed_layout'			=>	__('Enable boxed layout. Defaults to false.', SIMPLE_THEME_SLUG),
			'responsive_layout'		=>	__('Enable responsive layout. Defaults to true.', SIMPLE_THEME_SLUG),
			'display_breadcrumbs'	=>	__('Display breadcrumbs. Defaults to true.', SIMPLE_THEME_SLUG),
			// 'enable_retina'			=>	__('Enable Retina.js. Defaults to true.', SIMPLE_THEME_SLUG)
		)
	);

	// Google Analytics
	$options['google_analytics'] = array(
		'name' 	=> __('Google Analytics', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Enter your Google Analytics code here. Change UA-XXXXX-X to be your site\'s ID.', SIMPLE_THEME_SLUG),
		'id' 	=> 'google_analytics',
		'std' 	=> '',
		'type' 	=> 'textarea'
	);

	// Custom CSS
	$options['custom_css'] = array(
		'name' 	=> __('Custom CSS', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Enter any custom styles here.', SIMPLE_THEME_SLUG),
		'id' 	=> 'custom_css',
		'std' 	=> '',
		'type' 	=> 'textarea'
	);


	/*==============================
	S O C I A L
	==============================*/
	$options[] = array(
		'name' => __('Social', SIMPLE_THEME_SLUG),
		'type' => 'heading',
		'desc' => __('Social Channel info.', SIMPLE_THEME_SLUG)
	);

	// Facebook
	$options['social_facebook_url'] = array(
		'name' 	=> __('Facebook', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Facebook username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_facebook_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// Twitter
	$options['social_twitter_url'] = array(
		'name' 	=> __('Twitter', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Twitter username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_twitter_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// Google Plus
	$options['social_google_plus_url'] = array(
		'name' 	=> __('Google Plus', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Google Plus username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_google_plus_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// Instagram
	$options['social_instagram_url'] = array(
		'name' 	=> __('Instagram', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Instagram username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_instagram_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// Github
	$options['social_github_url'] = array(
		'name' 	=> __('Github', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Github username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_github_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// Pinterest
	$options['social_pinterest_url'] = array(
		'name' 	=> __('Pinterest', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Pinterest username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_pinterest_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// LinkedIn
	$options['social_linkedin_url'] = array(
		'name' 	=> __('LinkedIn', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your LinkedIn username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_linkedin_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);

	// Dribbble
	$options['social_dribbble_url'] = array(
		'name' 	=> __('Dribbble', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Your Dribbble username.', SIMPLE_THEME_SLUG),
		'id' 	=> 'social_dribbble_url',
		'std' 	=> '',
		'type' 	=> 'text'
	);



	/*==============================
	H E A D E R
	==============================*/
	$options[] = array(
		'name' => __('Header', SIMPLE_THEME_SLUG),
		'type' => 'heading',
		'desc' => __('Header content settings.', SIMPLE_THEME_SLUG)
	);

	// Header Multi Checkboxes
	$options['header_multi_checkbox'] = array(
		'name' 		=> __('General Enabling', SIMPLE_THEME_SLUG),
		'desc' 		=> __('Pick which options you would like for quickly setting up your header.', SIMPLE_THEME_SLUG),
		'id' 		=> 'header_multi_checkbox',
		'std' 		=> array(
			'enable_header_logo'		=>	'0',
			'header_social_checkbox'	=>	'0',
		),
		'type' 		=> 'multicheck',
		'options' 	=> array(
			'enable_header_logo'		=>	__('Enable an image as a logo, otherwise sitename will be used as the logo. (or text logo if set) Logo set in general options.', SIMPLE_THEME_SLUG),
			'header_social_checkbox'	=>	__('Enable social icons in header, defaults to false.', SIMPLE_THEME_SLUG)
		)
	);


	/*==============================
	F O O T E R
	==============================*/
	$options[] = array(
		'name' => __('Footer', SIMPLE_THEME_SLUG),
		'type' => 'heading',
		'desc' => __('Footer content settings.', SIMPLE_THEME_SLUG)
	);

	// Copyright Text
	$options['footer_copyright_text'] = array(
		'name' 	=> __('Copyright Text', SIMPLE_THEME_SLUG),
		'desc' 	=> __('What copyright text displays in the footer.', SIMPLE_THEME_SLUG),
		'id' 	=> 'footer_copyright_text',
		'std' 	=> 'Copyright Details',
		'type' 	=> 'text'
	);

	// Footer Multi Checkboxes
	$options['footer_multi_checkbox'] = array(
		'name' 		=> __('General Enabling', SIMPLE_THEME_SLUG),
		'desc' 		=> __('Pick which options you would like for quickly setting up your footer.', SIMPLE_THEME_SLUG),
		'id' 		=> 'footer_multi_checkbox',
		'std' 		=> array(
			'footer_widgets_checkbox'	=>	'1'
		),
		'type' 		=> 'multicheck',
		'options' 	=> array(
			'footer_widgets_checkbox'	=>	__('Enable widgets in footer, defaults to true.', SIMPLE_THEME_SLUG),
			'footer_social_checkbox'	=>	__('Enable social icons in footer, defaults to false.', SIMPLE_THEME_SLUG)
		)
	);

	/*==============================
	B L O G
	==============================*/
	$options[] = array(
		'name' => __('Blog', SIMPLE_THEME_SLUG),
		'type' => 'heading',
		'desc' => __('Manage blog and single post view settings.', SIMPLE_THEME_SLUG)
	);

	// Blog Layout: Grid, full, left sidebar, right sidebar
	$options['blog_layout'] = array(
		'name' 		=> __('Blog Layout', SIMPLE_THEME_SLUG),
		'desc' 		=> __('Full width, left sidebar, or right sidebar.', SIMPLE_THEME_SLUG),
		'id' 		=> 'blog_layout',
		'std' 		=> 'right_sidebar_layout',
		'type'		=> 'images',
		'options' 	=> $layout_options
	);

	// Avatar
	$options['avatar'] = array(
		'name' 	=> __('Avatar', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Upload your custom avatar.', SIMPLE_THEME_SLUG),
		'id' 	=> 'avatar',
		'type' 	=> 'upload'
	);

	// Info Break
	$options[] = array(
		'name' => 'Archive',
		'desc' => 'This is just some example information you can put in the panel.',
		'type' => 'info'
	);

	// Posts per page
	$options['posts_per_page'] = array(
		'name' 	=> __('Posts per page', SIMPLE_THEME_SLUG),
		'desc'	=> __('Set number of posts to be displayed.', SIMPLE_THEME_SLUG),
		'id' 	=> 'posts_per_page',
		'std' 	=> '10',
		'type' 	=> 'text'
	);

	// Excerpt Length
	$options['excerpt_length'] = array(
		'name' 	=> __('Excerpt Length', SIMPLE_THEME_SLUG),
		'desc'	=> __('Set number of words for post excerpts.', SIMPLE_THEME_SLUG),
		'id' 	=> 'excerpt_length',
		'std' 	=> '20',
		'type' 	=> 'text'
	);
		// More Text
		$options['excerpt_more_text'] = array(
			'name' 	=> __('Excerpt More Text', SIMPLE_THEME_SLUG),
			'desc'	=> __('Change the default [...] with something else (leave empty if you want to remove it).', SIMPLE_THEME_SLUG),
			'id' 	=> 'excerpt_more_text',
			'std' 	=> '...',
			'type' 	=> 'text'
		);

	// Post Meta
	$options['blog_postmeta_checkbox'] = array(
		'name' 		=> __('Post Meta Options', SIMPLE_THEME_SLUG),
		'desc' 		=> __('Select which post meta you would like to display under each post.', SIMPLE_THEME_SLUG),
		'id' 		=> 'blog_postmeta_checkbox',
		'std' 		=> $blog_postmeta_defaults,
		'type' 		=> 'multicheck',
		'options' 	=> $blog_postmeta_array
	);

	// Blog multi checkboxes
	$options['blog_multi_checkbox'] = array(
		'name' 		=> __('General Enabling', SIMPLE_THEME_SLUG),
		'desc' 		=> __('Pick which options you would like for quickly setting up your website.', SIMPLE_THEME_SLUG),
		'id' 		=> 'blog_multi_checkbox',
		'std' 		=> array(
			'blog_sharing_checkbox'		=>	'1',
			'post_pagination_checkbox'	=>	'1'
		),
		'type' 		=> 'multicheck',
		'options' 	=> array(
			'blog_sharing_checkbox'		=>	__('Enables social sharing on blog posts, defaults to true.', SIMPLE_THEME_SLUG),
			'post_pagination_checkbox'	=>	__('Show pagination on blog posts, defaults to true.', SIMPLE_THEME_SLUG),
			'infinite_scroll'			=>	__('Enable Infinite Scroll.', SIMPLE_THEME_SLUG),
			'blog_authors_checkbox'		=>	__('Includes authors bio on every post, defaults to false.', SIMPLE_THEME_SLUG),
			'disable_comments'			=>	__('Disable Comments sitewide, defaults to false.', SIMPLE_THEME_SLUG)
		)
	);


	/*==============================
	C O N T A C T
	==============================*/
	$options[] = array(
		'name' => __('Contact', SIMPLE_THEME_SLUG),
		'type' => 'heading',
		'desc' => __('Contact Page Settings.', SIMPLE_THEME_SLUG)
	);

	// Enable Custom Contact Form
	$options['enable_default_form'] = array(
		'name' 	=> __('Enable Default Contact Form', SIMPLE_THEME_SLUG),
		'desc' 	=> __('Check to enable and show custom contact form. Defaults to false.', SIMPLE_THEME_SLUG),
		'id' 	=> 'enable_default_form',
		'std' 	=> '1',
		'type' 	=> 'checkbox'
	);

	// Contact Form Email
	$options['contact_form_email'] = array(
		'name' => __('Contact Form Email', SIMPLE_THEME_SLUG),
		'desc' => __('Set your email address for contact form. None set by default.', SIMPLE_THEME_SLUG),
		'id' => 'contact_form_email',
		'std' => 'Email Address',
		'type' => 'text'
	);

	// Contact Button Text
	$options['contact_form_button'] = array(
		'name' => __('Contact Button Text', SIMPLE_THEME_SLUG),
		'desc' => __('Set your email address for contact form. None set by default.', SIMPLE_THEME_SLUG),
		'id' => 'contact_form_button',
		'std' => 'Send Message',
		'type' => 'text'
	);

	return $options;
}

/**
 * Front End Customizer
 *
 * WordPress < 3.4 Required
 */

add_action( 'customize_register', 'options_theme_customizer_register' );

function options_theme_customizer_register($wp_customize) {

	/**
	 * This is optional, but if you want to reuse some of the defaults
	 * or values you already have built in the options panel, you
	 * can load them into $options for easy reference
	 */

	$options = optionsframework_options();
	$themename = optionsframework_option_name();


	// Testing live update with js customizer script
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';


	/*==============================
	G E N E R A L
	==============================*/
	$wp_customize->add_section( 'options_theme_customizer_basic', array(
		'title' 	=> __( 'General', SIMPLE_THEME_SLUG ),
		'priority' 	=> 100
	) );


	// Logo
	$wp_customize->add_setting( $themename.'[logo]', array(
		'type' => 'option'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo', array(
		'label' => $options['logo']['name'],
		'section' => 'options_theme_customizer_basic',
		'settings' => $themename.'[logo]'
	) ) );


	// Favicon
	$wp_customize->add_setting( $themename.'[favicon]', array(
		'type' => 'option'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'favicon', array(
		'label' => $options['favicon']['name'],
		'section' => 'options_theme_customizer_basic',
		'settings' => $themename.'[favicon]'
	) ) );


	//
	// Hack for multicheck support
	//
	// sp($options['general_multi_checkbox']);

	foreach ($options['general_multi_checkbox']['options'] as $key => $value) {

        // sp(of_get_option('general_multi_checkbox')[$key]);

        // currently doesnt save any..

		$wp_customize->add_setting( $themename.'[general_multi_checkbox]['.$key.']', array(
			'default' => boolval(of_get_option('general_multi_checkbox')[$key]),
			'type' => 'checkbox'
		) );

		$wp_customize->add_control( 'options_theme_customizer_' . $key, array(
			'label' => $value,
			'section' => 'options_theme_customizer_basic',
			'settings' => $themename.'[general_multi_checkbox]['.$key.']',
			'type' => 'checkbox'
		) );

	}





	/*==============================
	H E A D E R
	==============================*/
	$wp_customize->add_section( 'options_theme_customizer_header', array(
		'title' 	=> __( 'Header', SIMPLE_THEME_SLUG ),
		'priority' 	=> 130
	) );

	//
	// Hack for multicheck support
	//
	// sp($options['header_multi_checkbox']);

	foreach ($options['header_multi_checkbox']['options'] as $key => $value) {

        // sp(of_get_option('header_multi_checkbox')[$key]);

        // currently doesnt save any..

		$wp_customize->add_setting( $themename.'[header_multi_checkbox]['.$key.']', array(
			'default' => boolval(of_get_option('header_multi_checkbox')[$key]),
			'type' => 'checkbox'
		) );

		$wp_customize->add_control( 'options_theme_customizer_' . $key, array(
			'label' => $value,
			'section' => 'options_theme_customizer_header',
			'settings' => $themename.'[header_multi_checkbox]['.$key.']',
			'type' => 'checkbox'
		) );

	}



	/*==============================
	F O O T E R
	==============================*/
	$wp_customize->add_section( 'options_theme_customizer_footer', array(
		'title' 	=> __( 'Footer', SIMPLE_THEME_SLUG ),
		'priority' 	=> 140
	) );

	// Footer Copyright Text
	$wp_customize->add_setting( $themename.'[footer_copyright_text]', array(
		'default' 	=> $options['footer_copyright_text']['std'],
		'type' 		=> 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_footer_copyright_text', array(
		'label' 	=> $options['footer_copyright_text']['name'],
		'section' 	=> 'options_theme_customizer_footer',
		'settings' 	=> $themename.'[footer_copyright_text]',
		'type' 		=> $options['footer_copyright_text']['type']
	) );


	//
	// Hack for multicheck support
	//
	// sp($options['footer_multi_checkbox']);

	foreach ($options['footer_multi_checkbox']['options'] as $key => $value) {

        // sp(of_get_option('footer_multi_checkbox')[$key]);

        // currently doesnt save any..

		$wp_customize->add_setting( $themename.'[footer_multi_checkbox]['.$key.']', array(
			'default' => boolval(of_get_option('footer_multi_checkbox')[$key]),
			'type' => 'checkbox'
		) );

		$wp_customize->add_control( 'options_theme_customizer_' . $key, array(
			'label' => $value,
			'section' => 'options_theme_customizer_footer',
			'settings' => $themename.'[footer_multi_checkbox]['.$key.']',
			'type' => 'checkbox'
		) );

	}


	/*==============================
	B L O G
	==============================*/
	$wp_customize->add_section( 'options_theme_customizer_blog', array(
		'title' 	=> __( 'Blog', SIMPLE_THEME_SLUG ),
		'priority' 	=> 170
	) );

	// Posts per page
	$wp_customize->add_setting( $themename.'[posts_per_page]', array(
		'default' => $options['posts_per_page']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_posts_per_page', array(
		'label' => $options['posts_per_page']['name'],
		'section' => 'options_theme_customizer_blog',
		'settings' => $themename.'[posts_per_page]',
		'type' => $options['posts_per_page']['type']
	) );

	// Avatar
	$wp_customize->add_setting( $themename.'[avatar]', array(
		'type' => 'option'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'avatar', array(
		'label' => $options['avatar']['name'],
		'section' => 'options_theme_customizer_blog',
		'settings' => $themename.'[avatar]'
	) ) );

	// Blog Layout
	$wp_customize->add_setting( $themename.'[blog_layout]', array(
		'default' => $options['blog_layout']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_blog_layout', array(
		'label' => $options['blog_layout']['name'],
		'section' => 'options_theme_customizer_blog',
		'settings' => $themename.'[blog_layout]',
		'choices' => $options['blog_layout']['options'],
		'type'	=>	'select'
	) );


	//
	// Hack for multicheck support
	//
	// sp($options['blog_postmeta_checkbox']);

	foreach ($options['blog_postmeta_checkbox']['options'] as $key => $value) {

        // sp(of_get_option('blog_postmeta_checkbox')[$key]);

        // currently doesnt save any..

		$wp_customize->add_setting( $themename.'[blog_postmeta_checkbox]['.$key.']', array(
			'default' => boolval(of_get_option('blog_postmeta_checkbox')[$key]),
			'type' => 'checkbox'
		) );

		$wp_customize->add_control( 'options_theme_customizer_' . $key, array(
			'label' => $value,
			'section' => 'options_theme_customizer_blog',
			'settings' => $themename.'[blog_postmeta_checkbox]['.$key.']',
			'type' => 'checkbox'
		) );

	}



	//
	// Hack for multicheck support
	//
	// sp($options['blog_postmeta_checkbox']);

	foreach ($options['blog_multi_checkbox']['options'] as $key => $value) {

        // sp(of_get_option('blog_multi_checkbox')[$key]);

        // currently doesnt save any..

		$wp_customize->add_setting( $themename.'[blog_multi_checkbox]['.$key.']', array(
			'default' => boolval(of_get_option('blog_multi_checkbox')[$key]),
			'type' => 'checkbox'
		) );

		$wp_customize->add_control( 'options_theme_customizer_' . $key, array(
			'label' => $value,
			'section' => 'options_theme_customizer_blog',
			'settings' => $themename.'[blog_multi_checkbox]['.$key.']',
			'type' => 'checkbox'
		) );

	}





	/*==============================
	C O N T A C T
	==============================*/
	$wp_customize->add_section( 'options_theme_customizer_contact', array(
		'title' 	=> __( 'Contact', SIMPLE_THEME_SLUG ),
		'priority' 	=> 170
	) );

	// Enable default jquery form using template contact.php
	$wp_customize->add_setting( $themename.'[enable_default_form]', array(
		'default' => $options['enable_default_form']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_enable_default_form', array(
		'label' => $options['enable_default_form']['name'],
		'section' => 'options_theme_customizer_contact',
		'settings' => $themename.'[enable_default_form]',
		'type' => $options['enable_default_form']['type']
	) );

	// Contact form email address
	$wp_customize->add_setting( $themename.'[contact_form_email]', array(
		'default' => $options['contact_form_email']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_contact_form_email', array(
		'label' => $options['contact_form_email']['name'],
		'section' => 'options_theme_customizer_contact',
		'settings' => $themename.'[contact_form_email]',
		'type' => $options['contact_form_email']['type']
	) );

	// Contact form button text
	$wp_customize->add_setting( $themename.'[contact_form_button]', array(
		'default' => $options['contact_form_button']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_contact_form_button', array(
		'label' => $options['contact_form_button']['name'],
		'section' => 'options_theme_customizer_contact',
		'settings' => $themename.'[contact_form_button]',
		'type' => $options['contact_form_button']['type']
	) );




	/*==============================
	S O C I A L
	==============================*/
	$wp_customize->add_section( 'options_theme_customizer_social', array(
		'title' 	=> __( 'Social', SIMPLE_THEME_SLUG ),
		'priority' 	=> 170
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_facebook_url]', array(
		'default' => $options['social_facebook_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_facebook_url', array(
		'label' => $options['social_facebook_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_facebook_url]',
		'type' => $options['social_facebook_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_twitter_url]', array(
		'default' => $options['social_twitter_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_twitter_url', array(
		'label' => $options['social_twitter_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_twitter_url]',
		'type' => $options['social_twitter_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_google_plus_url]', array(
		'default' => $options['social_google_plus_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_google_plus_url', array(
		'label' => $options['social_google_plus_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_google_plus_url]',
		'type' => $options['social_google_plus_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_instagram_url]', array(
		'default' => $options['social_instagram_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_instagram_url', array(
		'label' => $options['social_instagram_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_instagram_url]',
		'type' => $options['social_instagram_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_github_url]', array(
		'default' => $options['social_github_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_github_url', array(
		'label' => $options['social_github_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_github_url]',
		'type' => $options['social_github_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_pinterest_url]', array(
		'default' => $options['social_pinterest_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_pinterest_url', array(
		'label' => $options['social_pinterest_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_pinterest_url]',
		'type' => $options['social_pinterest_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_linkedin_url]', array(
		'default' => $options['social_linkedin_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_linkedin_url', array(
		'label' => $options['social_linkedin_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_linkedin_url]',
		'type' => $options['social_linkedin_url']['type']
	) );

	// Facebook
	$wp_customize->add_setting( $themename.'[social_dribbble_url]', array(
		'default' => $options['social_dribbble_url']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_social_dribbble_url', array(
		'label' => $options['social_dribbble_url']['name'],
		'section' => 'options_theme_customizer_social',
		'settings' => $themename.'[social_dribbble_url]',
		'type' => $options['social_dribbble_url']['type']
	) );


}