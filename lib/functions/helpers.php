<?php
/*
*
*	Helper Functions
*
*	This file is broken in the following areas:
*
*	1. CSS Classes
*		- Body Class
*		- Remove Injected Classes
*		- Post Container Class
*		- Change markup of images inserted in post content
*		- Retina.js Images
*		- Create Retina Image
*		- Delete Retina Image
*
* 	3. Debugging
*		- Debug Bar ( now includes template check in it )
*		- Pretty Print pp() Extending print_r()
*
* 	3. Removals
*		- Remove Admin Bar
*		- Remove 'text/css' from our enqueued stylesheet
*		- Remove <p> tags in Dynamic Sidebars
*		- Remove invalid rel attribute
*		- Remove wp_head() injected Recent Comment styles
*		- Remove thumbnail width and height dimensions
*		- <p> tag removal from images and iframes
*
* 	4. Text Mods
*		- Truncate Text
*		- Truncate Words
*		- Excerpts
*		- Simple title for pages
*
* 	5. Search & Pagination
*		- Redirect to result if only one found
*		- Search only posts, not pages
*		- Pagination
*
* 	6. Commenting
*		- Comment Form Placeholders
*		- Threaded Comments
*		- Custom Gravatar in Settings
*
* 	7. Shortcodes
*		- Allow shortcodes in Dynamic Sidebar
*		- Fix Empty <p> tags in shortcodes
*		- Call a shortcode function by tag name.
*
* 	8. Meta
*		- Get all meta of attachments (url, caption, etc)
*		- List the terms for taxonomy
*		- Add custom js for admin meta post formats
*		- Wordcount
*		- Reading Time
*		- Post Views
*
*	9. Add custom columns to admin
*		- Add Columns
*		- Add thumbnail to column
*		- Register column as sortable
*
*	10. Other
*		- Boolval
*		- Widget Admin Styles
*		- Is Login Screen
*		- Get More Themes Link
*		- Footer Admin Extra Text
*
*
*	@package	Simple
*	@since		1.0
*	@version	1.0
*/


/* ============================================
	1. CSS Classes
============================================ */

/*	Body Classes : Remove wp defaults, add clean classes
================================================== */
add_filter( 'body_class', 'simple_body_class' );
function simple_body_class( $classes ){

	global $post;

	//	Conditional Checking
	$template 			  	= !is_tax() && !is_category() ? basename( get_page_template() ) : '';
	$loggedIn 			  	= is_user_logged_in() ? 'logged-in' : '';
	$blog 	  			  	= ( is_archive() || is_search() || is_home() ) ? 'archive blog list-view' : '';
	$archive				= is_archive() ? strtolower(post_type_archive_title('',false)) : '';
	// Grab layout options
	$presentation_options 	= of_get_option( 'blog_layout' );
	// if no_sidebar_layout option is not selected and sidebar is active, and template is not full width
	$sidebar 				= ( $presentation_options != 'no_sidebar_layout' &&
							( is_active_sidebar( 'sidebar_blog' ) || is_active_sidebar( 'sidebar_default' ) )
							&& simple_display_sidebar() ) ? 'has-sidebar' : '';
	$boxed					= ( of_get_option('general_multi_checkbox')['boxed_layout'] == '1' ) ? 'boxed' : '';
	$single 				= is_single() ? 'single '.sanitize_html_class($post->post_name) : '';
	$one_page				= ( of_get_option('child_general_multi_checkbox')['enable_one_page'] == '1' ) ? 'one-page' : '';

	$page_slug 				= !is_search() && !is_404() && isset( $post->ID ) ? $post->post_type . '-' . $post->post_name : '';
	// sanitize_html_class($post->post_name), // slug


	//	Output classes
	return array(
		$blog,
		$archive,
		$page_slug,
		substr($template, 0, -4), // template name
		$loggedIn, // logged-in class
		$sidebar,
		$boxed,
		get_post_type(),
		$single,
		$one_page,
		// 'sbg' // themeoptions styling class
	);

	return $classes;

}


/*	Remove Injected classes, ID's and Page ID's from Navigation <li> items
================================================== */
add_filter('nav_menu_css_class', 'simple_css_attributes_filter', 100, 2);
add_filter('nav_menu_item_id', 'remove_simple_css_attributes_filter', 100, 2);
add_filter('page_css_class', 'remove_simple_css_attributes_filter', 100, 2);
function remove_simple_css_attributes_filter($var) {
	$var = is_array($var) ? array_intersect( $var,
		array(
			'current-menu-item',
			'menu-parent-item',
			'current_page_ancestor',
		)
	) : '';
	return $var;
}
function simple_css_attributes_filter($classes, $item) {
	$var = is_array($item->classes) ? array_intersect( $item->classes,
		array(
			'current-menu-item',
			'menu-parent-item',
			'current_page_ancestor',
			 $item->classes['0']
		)
	) : '';
	return $var;
}

// REPLACE "current_page_" WITH CLASS "active"
function current_to_active($text){
	$replace = array(
		// List of classes to replace with "active"
		'current_page_item' => 'active',
		'current_page_parent' => 'active',
		'current_page_ancestor' => 'active',
		// 'current-menu-item' => 'active'
	);
	$text = str_replace(array_keys($replace), $replace, $text);
	return $text;
}
add_filter ('wp_nav_menu','current_to_active');


/*	Post Container Class
================================================== */
add_filter( 'post_class', 'simple_post_class' );
function simple_post_class( $classes ){
	global $post;

	$classes[] = 'post';

	return $classes;
}


/*	Change markup of images inserted in post content
================================================== */
function simple_html5_image($html, $id, $caption, $title, $align, $url, $size, $alt = '') {
	$src  			= wp_get_attachment_image_src( $id, $size, false );
	$figureClasses 	= 'mfp-image'; // separated by spaces, e.g. 'img image-link'
	$imgClasses 	= 'lazy';
	$html 			= '';

	// check if there are already classes assigned to the anchor
	// bug in fist conditional preg_replace question mark closing tag breaks out php
	/*
	if ( preg_match('/<a.*? class=".*?">/', $html) ) {
		$html = preg_replace('/(<a.*? class=".*?)(".*?>)/', '$1 ' . $classes . '$2', $html);
	} else {
		$html = preg_replace('/(<a.*?)>/', '$1 class="' . $classes . '" >', $html);
	}
	*/

	$html			.= "<figure id='post-$id media-$id' class='align$align ".$figureClasses."'>";
	if ( $url ) {
		$html 		.= "<a href='".$url."' data-effect='mfp-fade-in-up'>";
	}
		$html 		.= "<img src='".get_stylesheet_directory_uri()."/assets/images/gray.png' data-original='$src[0]' alt='$title' class='".$imgClasses."' />";
		// $html 		.= "<img src='".$src[0]."' alt='$title' class='".$imgClasses."' />";
	if ( $url ) {
		$html 		.= "</a>";
	}
	$html 			.= "</figure>";

	return $html;
}
// add_filter( 'image_send_to_editor', 'simple_html5_image', 10, 9 );


/*	Retina.js Images
================================================== */
function retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }

    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'retina_support_attachment_meta', 10, 2 );


/*	Create retina-ready images
================================================== */
function retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );

            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );

            $info = $resized_file->get_size();

            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}


/*	Delete retina-ready images
================================================== */
function delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = isset($meta['file']) ? pathinfo( $meta['file'] ) : '';
    if ( is_array( $meta ) ) {
	    foreach ( $meta as $key => $value ) {
	        if ( 'sizes' === $key ) {
	            foreach ( $value as $sizes => $size ) {
	                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
	                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
	                if ( file_exists( $retina_filename ) )
	                    unlink( $retina_filename );
	            }
	        }
	    }
	}
}
add_filter( 'delete_attachment', 'delete_retina_support_images' );






/* ============================================
	2. Debugging
============================================ */

/*	Debug Bar
============================================ */
add_action('wp_footer', 'simple_debug');
function simple_debug($current_user){

	// if theme enables it
	if ( current_theme_supports('debug')  )


	// if user is currently logged in and its in local env
	if ( is_user_logged_in() && $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {

		global $template;
		$template 		= basename( simple_template_path() );
		$template 		= explode( '/', $template );
		$array_count 	= count( $template );
		$array_count 	= $array_count - 1;
		$template 		= $template[$array_count];

		global $current_user;
		get_currentuserinfo();

	?>

	<script type="text/javascript">
		console.log( 'Template: <?php echo $template; ?>' );
	</script>

	<?php

		$debug_bar = '<style>
						#debug-bar{
							position: fixed;
							max-width: 40px;
							width: auto;
							height: 40px;
							z-index: 100;
							bottom: 10px;
							left: 10px;
							background: #FFFFFF;
							border: 1px solid #ddd;
							font-family: "Open Sans", sans-serif;
							padding: 10px 13px;
							color: #535353;
							text-transform: uppercase;
							transition: max-width .35s linear;
							white-space: nowrap;
							cursor: pointer;
							overflow: hidden;
						}
						#debug-bar i {
							vertical-align: middle;
						}
						#debug-bar p {
							font-size: 12px;
							display: inline-block;
							text-indent: 12px;
							vertical-align: top;
							padding: 0;
						}
						#debug-bar:hover {
							max-width: 500px;
						}
						</style>';
		$debug_bar .= '<div id="debug-bar">';
		$debug_bar .= '<i class="fa fa-gear"></i><p>';
		$debug_bar .= 'User: <a href="/admin">' . $current_user->display_name . '</a>';
		$debug_bar .= ' | Role: ' . $current_user->roles[0];
		$debug_bar .= ' | Template: ' . $template;
		$debug_bar .= '</p>';
		$debug_bar .= '</div>';

		echo $debug_bar;
	}

}




/*	Simple Print
================================================== */
function sp( $var, $args = array() ){

	$defaults = array(
		'strip_tags'  	=> false,
		'allow_tags'	=> null
	);

	$options = array_merge($defaults, $args);

	if( $options['strip_tags'] ){
		$var = strip_tags($var, $options['allow_tags']);
	}

	echo '<pre>';
	print_r($var);
	echo '</pre>';

}





/* ============================================
	3. Removals
============================================ */

/*	Remove Admin bar
================================================== */
add_filter('show_admin_bar', 'remove_admin_bar');
function remove_admin_bar() {
	if( current_theme_supports('admin_bar') ) {
		return true;
	}
}


/*	Remove 'text/css' from our enqueued stylesheet
================================================== */
add_filter('style_loader_tag', 'simple_style_remove');
function simple_style_remove($tag) {
	return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}


/*	Remove <p> tags in Dynamic Sidebars
================================================== */
add_filter('widget_text', 'shortcode_unautop');


/*	Remove invalid rel attribute
================================================== */
add_filter('the_category', 'remove_category_rel_from_category_list');
function remove_category_rel_from_category_list($thelist) {
	return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}


/*	Remove wp_head() injected Recent Comment styles
================================================== */
add_action('widgets_init', 'simple_remove_recent_comments_style');
function simple_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action('wp_head', array(
		$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
		'recent_comments_style'
	));
}


/*	Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
================================================== */
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);
function remove_thumbnail_dimensions( $html ) {
	$html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
	return $html;
}


/*	Remove <p> tags from the images and iFrame
================================================== */
add_filter('the_content', 'simple_img_iframe_unautop');
function simple_img_iframe_unautop( $content ){
	$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
}







/* ============================================
	4. Text Mods
============================================ */

/*	TRUNCATE ANYTHING - CHARACTERS
================================================== */
function truncate_text( $string, $character_limit = 50, $truncation_indicator = '...' ) {

	$truncated = null == $string ? '' : $string;
	$getlength = strlen($truncated);

	if ( $getlength > $character_limit ) {

		$truncated = substr( $truncated, 0, strrpos(substr($truncated, 0, $character_limit), ' ') );
		$truncated .= '...';

		$truncated = $truncated . $truncation_indicator;
	}

	return sprintf( __('%s', SIMPLE_THEME_SLUG), $truncated );
}

/*	TRUNCATE ANYTHING - WORDS
================================================== */
function truncate_words( $text, $limit, $truncation_indicator = '...' ) {

	if ( str_word_count($text, 0) > $limit ) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]) . '...' . $truncation_indicator;
	}
	return $text;
}

/*	EXCERPTS
================================================== */
//	Add Excerpts for Pages
add_post_type_support( 'page', 'excerpt' );
//	Hide auto generated excerpts
function full_excerpt() {
	if ( !has_excerpt() ) {
		echo '';
	} else {
		echo get_the_excerpt();
	}
}

//	Set "more" context for excerpts
add_filter('excerpt_more', 'simple_excerpt_more');
function simple_excerpt_more() {
	global $post;
	return '<br /><a href="'. get_permalink($post->ID) . '" class="read-more">' . __( of_get_option('excerpt_more_text'), SIMPLE_THEME_SLUG) . '</a>';
}

// Example combining truncate_text and simple_excerpt_more
// echo truncate_text( get_the_excerpt(), 50, simple_excerpt_more() );


/*	Simple title for pages : simple_title();
================================================== */
function simple_title() {

	if ( is_home() ) {
		if ( get_option('page_for_posts', true) ) {
			$title = get_the_title( get_option('page_for_posts', true) );
		} else {
			$title = __('Latest Posts', SIMPLE_THEME_SLUG);
		}
	} elseif ( is_archive() ) {
		$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
		if ( $term ) {
			$title = $term->name;
		} elseif ( is_post_type_archive() ) {
			$title = get_queried_object()->labels->name;
		} elseif ( is_day() ) {
			$title = sprintf( __('Daily Archives: %s', SIMPLE_THEME_SLUG), get_the_date() );
		} elseif ( is_month() ) {
			$title = sprintf( __('Monthly Archives: %s', SIMPLE_THEME_SLUG), get_the_date('F Y') );
		} elseif ( is_year() ) {
			$title = sprintf( __('Yearly Archives: %s', SIMPLE_THEME_SLUG), get_the_date('Y') );
		} elseif ( is_author() ) {
			$author = get_queried_object();
			$title = sprintf( __('Author Archives: %s', SIMPLE_THEME_SLUG), $author->display_name );
		} else {
			$title = single_cat_title( '',false );
		}
	} elseif ( is_search() ) {
		$title = sprintf( __('Search Results for %s', SIMPLE_THEME_SLUG), get_search_query() );
	} elseif ( is_404() ) {
		$title = __('Not Found', SIMPLE_THEME_SLUG);
	} else {
		$title = get_the_title();
	}

	return $title;

}






/* ============================================
	5. Search & Pagination
============================================ */

/*	Simple Search Form
============================================ */
function simple_search_form( $form ) {
    $form = '
<form role="search" method="get" class="search-form" action="'.home_url() .'">
	<label>
		<input type="search" class="search-field" placeholder="'. __('Type and press enter', SIMPLE_THEME_SLUG) .'" value="" name="s" title="'. __('Search for:', SIMPLE_THEME_SLUG) .'">
	</label>
	<input type="submit" class="search-submit" value="Search">
</form>
    ';

    return $form;
}

add_filter( 'get_search_form', 'simple_search_form' );


/*	Redirect to result if only one found
============================================ */
function single_search_result() {
	if ( is_search() ) {
		global $wp_query;
		if ( $wp_query->post_count == 1 ) {
			wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
		}
	}
}
add_action('template_redirect', 'single_search_result');


/*	Search only posts, not pages
============================================ */
if ( !is_admin() ) {
	function simple_search_filter($query) {
		if ( $query->is_search && !is_admin() ) {
			// global $query;
			// $query	= apply_filters('simple_search_filter', $query);
			$query->set( 'post_type', array('post') );
		}
		return $query;
	}
	// add_action( 'search_filter' , 'simple_search_filter' );
	add_filter('pre_get_posts','simple_search_filter');
}


/*	Pagination
============================================ */

if ( ! function_exists( 'simple_pagination' ) ) :
function simple_pagination( $args = NULL ) {

	// args
	$prev_text 	= isset($args['prev_text']) ? $args['prev_text'] : '';
	$next_text 	= isset($args['next_text']) ? $args['next_text'] : '';

	global $wp_query;

	$infiniteScroll = of_get_option('blog_multi_checkbox')['infinite_scroll'];

	if ( $wp_query->max_num_pages > 1 ) :
		if ( $infiniteScroll == '1' ) {
			$infiniteScrollClass = 'infinite-scroll-pagination';
		} else {
			$infiniteScrollClass = '';
		}
	?>
		<nav class="pagination <?php echo $infiniteScrollClass; ?>" role="navigation">
			<ul>
				<li>
					<?php next_posts_link( __( $next_text, SIMPLE_THEME_SLUG) ); ?>
				</li>
				<li>
					<?php previous_posts_link( __( $prev_text, SIMPLE_THEME_SLUG) ); ?>
				</li>
			</ul>
		</nav>
	<?php endif;
}
endif;


// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
// if ( ! function_exists( 'simple_pagination' ) ) :
// function simple_pagination() {
//     global $wp_query;
//     $big = 999999999;
//     echo paginate_links(array(
//         'base' => str_replace($big, '%#%', get_pagenum_link($big)),
//         'format' => '?paged=%#%',
//         'current' => max(1, get_query_var('paged')),
//         'total' => $wp_query->max_num_pages
//     ));
// }
// endif;





/* ============================================
	6. Commenting
============================================ */

/*	Switch off Comments on Pages by default.
================================================== */
function simple_default_comments_off( $data ) {

	if( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
		$data['comment_status'] = 0;
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'simple_default_comments_off' );



/*	Change default fields, add placeholder and change type attributes.
================================================== */
add_filter( 'comment_form_default_fields', 'simple_comment_form_placeholders' );
function simple_comment_form_placeholders( $fields ) {

	// // name
	// $fields['author'] = str_replace(
	// 	'<input id="author"',
	// 	'<input type="text" placeholder="Your name" id="author" name="author"',
	// 	$fields['author']
	// );

	// // email
	// $fields['email'] = str_replace(
	//     '<input id="email"',
	//     '<input type="email" placeholder="Email Address" id="email" name="email"',
	//     $fields['email']
	// );

	// // website
	$fields['url'] = ''; // removes website field
	// $fields['url'] = str_replace(
	//     '<input id="url"',
	//     '<input placeholder="http://example.com" id="url" name="url" type="url"',
	//     $fields['url']
	// );

	return $fields;
}


/*	Threaded Comments
================================================== */
add_action('get_header', 'enable_threaded_comments');
function enable_threaded_comments() {
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
			wp_enqueue_script('comment-reply');
		}
	}
}


/*	Custom Gravatar in Settings > Discussion
================================================== */
add_filter('avatar_defaults', 'simple_gravatar');
function simple_gravatar ($avatar_defaults) {
	// set as theme option
	$myAvatar = of_get_option('avatar') ? of_get_option('avatar') : get_template_directory_uri() . '/img/gravatar.jpg';
	$avatar_defaults[$myAvatar] = "Custom Gravatar";
	return $avatar_defaults;
}





/* ============================================
	7. Shortcodes
============================================ */

/*	Allow shortcodes in Dynamic Sidebar
================================================== */
add_filter('widget_text', 'do_shortcode');


/*	Fix filtering for shortcodes
================================================== */
function shortcode_empty_paragraph_fix($content){
	$array = array (
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']'
	);
	$content = strtr($content, $array);
	return $content;
}

add_filter('the_content', 'shortcode_empty_paragraph_fix');


/* Call a shortcode function by tag name.
================================================== */
/**
 * @author J.D. Grimes
 * @link http://codesymphony.co/dont-do_shortcode/
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function do_shortcode_func( $tag, array $atts = array(), $content = null ) {

	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) )
		return false;

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}




/* ============================================
	8. Meta
============================================ */

/*	Get attachment meta : $attachment_id = get_post_thumbnail_id($post->ID);
================================================== */
function wp_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );
	if( $attachment )
	return array(
		'alt' 			=> get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' 		=> $attachment->post_excerpt,
		'description' 	=> $attachment->post_content,
		'href' 			=> get_permalink( $attachment->ID ),
		'src' 			=> $attachment->guid,
		'title' 		=> $attachment->post_title
	);

}

/*	List the terms for taxonomy ($args = tax, show links, all terms or those specific to post)
================================================== */
function list_terms( $args ) {

	$taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : 'category';
	$anchors  = isset($args['anchors']) ? $args['anchors'] : true;
	$postID   = isset($args['postID']) ? $args['postID'] : null;
	$wrap     = isset($args['wrap']) ? $args['wrap'] : null;
	$echo     = isset($args['echo']) ? $args['echo'] : true;


	if ( $postID ) {
		$terms = get_the_terms($postID, $taxonomy);
	} else {
		$terms = get_terms($taxonomy);
	}

	if ( !empty( $terms ) ) :

		$count = count($terms);
		$i = 0;
		$term_list = '';

		if ( $count > 0 ) {

			foreach ($terms as $term) {

				$i++;

				if ( $wrap ) :
					$term_list .= '<'.$wrap.'>';
				endif;

				if ( $anchors ) :
					$term_list .= '<a href="' . get_term_link( $term ) . '" title="' . sprintf(__('View all post filed under %s', SIMPLE_THEME_SLUG), $term->name) . '">';
				endif;

				$term_list .= sprintf( __('%s',SIMPLE_THEME_SLUG), $term->name );

				if ( $anchors ) :
					$term_list .= '</a>';
				endif;

				if ( $wrap ) :
					$term_list .= '</'.$wrap.'>';
				endif;


				$term_list .= ' ';

				// if ( $count != $i ) {
				// 	$term_list .= ' &middot; ';
				// } else {
				// 	$term_list .= '';
				// }

			}

			if ( $echo ) {
				echo $term_list;
			} else {
				return $term_list;
			}


		}

	endif;
}
add_action('simple_list_terms', 'list_terms', 10, 4);


/*	Add custom js for admin meta post formats
================================================== */
add_action( 'admin_enqueue_scripts', 'simple_load_metabox_conditional_js');
function simple_load_metabox_conditional_js() {

	wp_enqueue_script( 'simple-post-formats', get_template_directory_uri() . '/lib/admin/meta/js/post-formats.js', 'jquery', '', true );

}


/*	Wordcount
================================================== */
function wordcount() {
    ob_start();
    the_content();
    $content = ob_get_clean();
    return sizeof( explode(" ", $content) );
}


/*	Reading Time
================================================== */
function reading_time() {
	global $post;
	//READING TIME CALCULATIONS
	$mycontent = $post->post_content;
	$words = str_word_count(strip_tags($mycontent));
	$reading_time = floor($words / 100);

	//IF LESS THAN A MINUTE - DISPLAY 1 MINUTE
	if ($reading_time == 0 )  {
		$reading_time = '1';
	}
	return $reading_time;
}


/*	Post Views
================================================== */
function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// To keep the count accurate, lets get rid of prefetching
// remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // done in wordpress-resets.php

// Set View Count
function wpb_track_post_views( $postID ) {
    if ( !is_single() ) return;
    if ( empty ( $postID) ) {
        global $post;
        $postID = $post->ID;
    }
    wpb_set_post_views($postID);
}
add_action( 'wp_head', 'wpb_track_post_views');

// Get View Count
function wpb_get_post_views( $postID = null ) {
    if ( empty ( $postID) ) {
        global $post;
        $postID = $post->ID;
    }
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Views";
    }
    return $count.' Views';
}




/* ============================================
	9. Add custom columns to admin
============================================ */

//	Add column
function add_custom_column( $columns ) {
	global $post;

	if ( get_post_type($post) == 'post' || get_post_type($post) == 'page' ) {
		$column_thumb = array( 'thumbnail' => __( 'Thumbnail', SIMPLE_THEME_SLUG ) );
		$columns = array_slice( $columns, 0, 2, true ) + $column_thumb + array_slice( $columns, 1, NULL, true );
	}

	if ( get_post_type($post) == 'page' ) {
		$columns['template'] = __( 'Page Template', SIMPLE_THEME_SLUG );
	}

	$columns['id'] = 'ID';

	return $columns;
}
add_filter( 'manage_posts_columns', 'add_custom_column', 10 );
add_filter( 'manage_pages_columns', 'add_custom_column', 10 );


//	Add thumbnails to columns
function display_custom_column_data( $column ) {

	global $post;

	if ( get_post_type($post) == 'post' || get_post_type($post) == 'page' ) :
		if ( $column == 'thumbnail' ) :
			echo get_the_post_thumbnail( $post->ID, array(35, 35) );
		endif;
	endif;

	switch ( $column ) {
		case 'template':

			if ( get_post_type($post) == 'page' ) :

				$template_name = '';

				// If we're looking at our custom column, then let's get ready to render some information.
				if ( 'template' == $column ) {

					// First, the get name of the template
					$template_name = get_page_template_slug( $post->ID );

					// If the file name is empty or the template file doesn't exist (because, say, meta data is left from a previous theme)...
					if ( 0 == strlen( trim( $template_name ) ) || ! file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {

						// ...then we'll set it as default
						$template_name = __( 'Default', SIMPLE_THEME_SLUG );

					// Otherwise, let's actually get the friendly name of the file rather than the name of the file itself
					// by using the WordPress `get_file_description` function
					} else {

						$template_name = get_file_description( get_stylesheet_directory() . '/' . $template_name );

					} // end if

				} // end if

				// Finally, render the template name
				echo $template_name;

				endif;

			break;
		case 'id':
			echo $post->ID;
			break;
	}
}
add_action( 'manage_posts_custom_column', 'display_custom_column_data', 10, 2 );
add_action( 'manage_pages_custom_column', 'display_custom_column_data', 10, 2 );


//	Register the column as sortable
function custom_column_register_sortable( $columns ) {
	$columns['thumbnail'] = 'thumbnail';
	$columns['template'] = 'template';
	$columns['id'] = 'id';

	return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'custom_column_register_sortable' );
add_filter( 'manage_edit-page_sortable_columns', 'custom_column_register_sortable' );


// Output CSS for width of new column ID
function id_css() {
?>
<style type="text/css">
	#id {
		width: 50px;
	}
</style>
<?php
}
add_action('admin_head', 'id_css');




/* ============================================
	10. Other
============================================ */
// boolval fix for php versions under 5.5
if ( !function_exists('boolval') ) {
    function boolval($val) {
		return (bool) $val;
    }
}

// Widget Admin Styles
if ( !function_exists('simple_widgets_style') ) {
	function simple_widgets_style() {
	    echo '
			<style type="text/css">
			div.widget[id*=_simple_] .widget-title {
			    border-left: 3px solid #2191bf;
			}
			</style>';
	}
	add_action('admin_print_styles-widgets.php', 'simple_widgets_style');
}

// Check if on login page
function is_login() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

// Get More Themes Link
if ( current_theme_supports('more-themes-link') ) :
	function admin_menu_new_items() {
	    global $submenu;
	    $submenu['index.php'][500] = array( __('More Simple Themes', SIMPLE_THEME_SLUG), 'manage_options' , 'http://getsimple.io/themes' );
	}
	add_action( 'admin_menu' , 'admin_menu_new_items' );
endif;

// Footer Admin Extra Text
if ( current_theme_supports('admin-footer-text') ) :
	function simple_footer_admin() {
		_e('Thank you for creating with <a href="http://getsimple.io" target="blank">SimpleThemes</a>. You rock.', SIMPLE_THEME_SLUG);
	}
	add_filter('admin_footer_text', 'simple_footer_admin');
endif;


// HexToRGB
if ( !function_exists('HexToRGB') ) {
	function HexToRGB($hex, $alpha = null) {
	    $hex = str_replace('#', '', $hex);

		if ( strlen($hex) == 3 ) {
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
			$a = $alpha ? $alpha : '1';
		} else if( strlen($hex) == 6 ) {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
			$a = $alpha ? $alpha : '1';
		}
		$rgba = array($r, $g, $b, $a);
		return implode(",", $rgba); // returns the rgb values separated by commas
		// return $rgba; // returns an array with the rgb values
	}
}