<?php
/* ============================================
	Global Theme Options
*
*		- Options Check
*		- Define OPTIONS_FRAMEWORK_DIRECTORY and init framework
*		- Setting theme options directory.
*		- Add custom scripts to options panel
*		- Custom options styles.
*		- Customizer styles.
*		- Number of posts (sets reading settings)
*		- Responsive Layout
*		- Back to top link
*		- Enable header logo
*		- Setup options
*
*

============================================ */


/*	Removed from simple-options/option.php to run
	before !function_exists() below to set default
	to simple_options for parent and cross child
	theme option sync
================================================== */
function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	global $themename;

	// if theme is not child theme
	if ( PARENT_URL == CHILD_URL ) {
		$themename = get_option( 'stylesheet' );
		$themename = preg_replace('/\W/', '_', strtolower($themename) );
		$themename = $themename.'_options'; // specific to theme/child
	} else {
		$themename = 'simple_options'; // general
	}

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);

	return $themename;
}


/*	This code allows the theme to work without errors if the Options Framework has been disabled.
================================================== */
if ( ! function_exists( 'of_get_option' ) ) {

	function of_get_option($name, $default = false) {
		$optionsframework_settings = get_option('optionsframework');

		// Gets the unique option id
		$option_name = $optionsframework_settings['id'];

		if ( get_option($option_name) ) {
			$options = get_option($option_name);
		}
		if ( isset($options[$name]) ) {
			return $options[$name];
		} else {
			return $default;
		}
	}
}

/*	Define OPTIONS_FRAMEWORK_DIRECTORY and init framework
================================================== */
if ( ! function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/lib/admin/simple-options/inc/' );
	require_once locate_template( '/lib/admin/simple-options/inc/options-framework.php', true );
}


/*	Setting theme options directory
================================================== */
add_filter('options_framework_location','options_framework_location_override');
function options_framework_location_override() {
	return array( '/lib/admin/simple-options/options.php' );
}


/*	Add custom scripts to options panel
================================================== */
add_action('optionsframework_custom_scripts', 'simple_optionsframework_custom_scripts');
function simple_optionsframework_custom_scripts() {

	// store heading desc values into array
	global $explain_value;
	$explain_value = array();
	$options = optionsframework_options();
	foreach ($options as $option) {

        if( $option['type'] == 'heading' ){

        	if ( isset( $option['desc'] ) ) {
				array_push($explain_value, $option['desc']);
			}
        }

	}

	?>

	<script type="text/javascript">
	jQuery(document).ready(function() {

		// custom wrapper for headings in options panel
		var heading = jQuery('#optionsframework').find('h3'),
			desc 	= jQuery('<div class="explain" />'),
			wrapper = jQuery('<div class="section" />');

		heading.wrap(wrapper).append(desc);

		// iterate through php array to match headings and desc
		var explain_value = <?php echo json_encode($explain_value); ?>;
		jQuery.each(explain_value, function (i, elem) {
		    // console.log(i);
		    // console.log(elem);
			heading.eq(i).find('.explain').html(elem);
		});

		// Front page display selection
		jQuery('#section-front_page_displays input').change(function(){
			// console.log(jQuery(this).val());
			if( jQuery(this).val() == 'static_page' ){
				jQuery('#section-home_set_page, #section-blog_set_page').slideToggle(400);
			} else{
				jQuery('#section-home_set_page, #section-blog_set_page').slideToggle(400);
			}
		});

		if( jQuery('#section-front_page_displays input[value=static_page]').prop('checked') ) {
			jQuery('#section-home_set_page, #section-blog_set_page').show();
		}

	});
	</script>

<?php
}

/*	Custom options styles
================================================== */
function simple_options_styles() {
	wp_enqueue_style( 'options', get_template_directory_uri() . '/lib/admin/simple-options/options.css' );
}
add_action( 'admin_print_styles', 'simple_options_styles' );


/*	Customizer Styles
================================================== */
function custom_customizer() {
	wp_enqueue_style( 'options', get_template_directory_uri() . '/lib/admin/simple-options/options.css' );
}
add_action( 'customize_controls_print_styles', 'custom_customizer', 100 );


/*	Responsive Layout Turned Off
================================================== */
function responsive_layout()   {
	global $content_width;
	if ( of_get_option('general_multi_checkbox')['responsive_layout'] == '0' ) { ?>
		<style>
			[data-layout="grid"],
			body.boxed main[role="main"],
	        body.boxed header[role="banner"],
	        body.boxed footer[role="contentinfo"] {
				max-width: <?php echo $content_width; ?>px;
				min-width: <?php echo $content_width; ?>px;
			}
		</style>
	<?php }
}
add_action( 'wp_head', 'responsive_layout', 100 );


/*	Back to Top Link
================================================== */
function simple_back_to_top()   {
	if ( of_get_option('general_multi_checkbox')['back_to_top'] == '1' ) {
		echo '<a href="#" class="btn" data-scroll-to="top"><i class="fa fa-angle-up"></i>'. __('', SIMPLE_THEME_SLUG) .'</a>';
	}
}
add_action( 'wp_footer', 'simple_back_to_top' );



/*	Enable Header Logo
================================================== */
function simple_header_logo_styles() {
	if ( of_get_option('header_multi_checkbox')['enable_header_logo'] == '1' ) { ?>
	<style>
		.logo {
			text-indent: -9999em;
			background: url(<?php echo of_get_option('logo'); ?>) no-repeat center;
			height: 50px;
			position: relative;
			display: inline-block;
			vertical-align: middle;
			background-size: contain;
		}
	</style>
<?php }
}
add_action( 'wp_head', 'simple_header_logo_styles', 110 );

function simple_header_logo() {

	if ( of_get_option( 'text_logo' ) ) {
		echo '<a class="logo" href="'. home_url( '/' ) .'" title="'. esc_attr( of_get_option( 'text_logo' ) ) .'" rel="home">'. of_get_option( 'text_logo' ) .'</a>';
	} else {
		echo '<a class="logo" href="'. home_url( '/' ) .'" title="'. esc_attr( get_bloginfo( 'name', 'display' ) ) .'" rel="home">'. get_bloginfo('name') .'</a>';
	}
}
add_action( 'simple_header_logo', 'simple_header_logo' );



/*	Front Page Displays (updates reading settings value)
================================================== */
function simple_update_front_page_displays() {
	$front_page_displays = of_get_option('front_page_displays');
	if( $front_page_displays == 'static_page' ) {
		update_option('show_on_front', 'page');
	} else {
		update_option('show_on_front', 'posts');
	}
}
add_action( 'admin_init', 'simple_update_front_page_displays' );



/*	Home Page (updates reading settings value)
================================================== */
function simple_update_home_page() {
	$home_page = of_get_option('home_set_page');
	if ( $home_page ) {
		update_option('page_on_front', $home_page);
	}
}
add_action( 'admin_init', 'simple_update_home_page' );



/*	Blog Page (updates reading settings value)
================================================== */
function simple_update_blog_page() {
	$blog_page = of_get_option('blog_set_page');
	if ( $blog_page ) {
		update_option('page_for_posts', $blog_page);
	}
}
add_action( 'admin_init', 'simple_update_blog_page' );


/*	Number of posts (updates reading settings value)
================================================== */
function simple_update_posts_per_page()   {
	$number_of_posts = of_get_option('posts_per_page');
	if ( $number_of_posts ) {
		update_option('posts_per_page', $number_of_posts);
	}
}
add_action( 'admin_init', 'simple_update_posts_per_page' );





/*	SETUP TAB - Experimental - add_theme_support
================================================== */
function simple_theme_options_setup() {

	$theme_activation_setup   = of_get_option('theme_activation_setup');

	$create_front_page        = $theme_activation_setup['create_front_page'];
	$set_permalink_structure  = $theme_activation_setup['set_permalink_structure'];
	$change_uploads_directory = $theme_activation_setup['change_uploads_directory'];
	$create_main_menu         = $theme_activation_setup['create_main_menu'];
	$add_pages_to_main_menu   = $theme_activation_setup['add_pages_to_main_menu'];

	//	Create home page and set it as front page
	if( $create_front_page ) {
		$default_pages = array('Home');
		$existing_pages = get_pages();
		$temp = array();

		foreach ($existing_pages as $page) {
			$temp[] = $page->post_title;
		}

		$pages_to_create = array_diff($default_pages, $temp);

		foreach ($pages_to_create as $new_page_title) {
			$add_default_pages = array(
				'post_title' => $new_page_title,
				'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum consequat, orci ac laoreet cursus, dolor sem luctus lorem, eget consequat magna felis a magna. Aliquam scelerisque condimentum ante, eget facilisis tortor lobortis in. In interdum venenatis justo eget consequat. Morbi commodo rhoncus mi nec pharetra. Aliquam erat volutpat. Mauris non lorem eu dolor hendrerit dapibus. Mauris mollis nisl quis sapien posuere consectetur. Nullam in sapien at nisi ornare bibendum at ut lectus. Pellentesque ut magna mauris. Nam viverra suscipit ligula, sed accumsan enim placerat nec. Cras vitae metus vel dolor ultrices sagittis. Duis venenatis augue sed risus laoreet congue ac ac leo. Donec fermentum accumsan libero sit amet iaculis. Duis tristique dictum enim, ac fringilla risus bibendum in. Nunc ornare, quam sit amet ultricies gravida, tortor mi malesuada urna, quis commodo dui nibh in lacus. Nunc vel tortor mi. Pellentesque vel urna a arcu adipiscing imperdiet vitae sit amet neque. Integer eu lectus et nunc dictum sagittis. Curabitur commodo vulputate fringilla. Sed eleifend, arcu convallis adipiscing congue, dui turpis commodo magna, et vehicula sapien turpis sit amet nisi.',
				'post_status' => 'publish',
				'post_type' => 'page'
			);

			$result = wp_insert_post($add_default_pages);
		}

		$home = get_page_by_title('Home');
		update_option('show_on_front', 'page');
		update_option('page_on_front', $home->ID);

		$home_menu_order = array(
			'ID' => $home->ID,
			'menu_order' => -1
		);
		wp_update_post($home_menu_order);
	}

	//	Change permalinks to pretty
	if( $set_permalink_structure ) {
		// if permalinks arent set to custom, set em, then flush
		if (get_option('permalink_structure') !== '/%postname%/') {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure('/%postname%/');
			flush_rewrite_rules();
		}
	}

	//	Change uploads directory to media/
	if( $change_uploads_directory ) {
		update_option('uploads_use_yearmonth_folders', 0);
		if (!is_multisite()) {
			update_option('upload_path', 'media');
		} else {
			update_option('upload_path', '');
		}
	}

	//	Create navigation menu
	if( $create_main_menu ) {
		$simple_nav_mod = false;

		$main_menu = wp_get_nav_menu_object('Main Menu');

		if (!$main_menu) {
			$main_menu_id = wp_create_nav_menu('Main Menu', array('slug' => 'main-menu'));
			$simple_nav_mod['main-menu'] = $main_menu_id;
		} else {
			$simple_nav_mod['main-menu'] = $main_menu->term_id;
		}

		if ($simple_nav_mod) {
			set_theme_mod('nav_menu_locations', $simple_nav_mod);
		}
	}

	//	Add published pages to menu
	if( $add_pages_to_main_menu ) {

		$primary_nav = wp_get_nav_menu_object('Main Menu');
		$primary_nav_term_id = (int) $primary_nav->term_id;
		$menu_items= wp_get_nav_menu_items($primary_nav_term_id);

		if (!$menu_items || empty($menu_items)) {
			$pages = get_pages();
			foreach($pages as $page) {
				$item = array(
					'menu-item-object-id' => $page->ID,
					'menu-item-object' => 'page',
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish'
					);
				wp_update_nav_menu_item($primary_nav_term_id, 0, $item);
			}
		}
	}

}
add_action('optionsframework_after_validate', 'simple_theme_options_setup');