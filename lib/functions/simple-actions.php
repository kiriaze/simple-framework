<?php

/* ============================================
	Simple Actions
*
*	Load helpers, prefferably last
*
*	1. Grid
*	2. Archive
*	3. Footer Widgets
*	4. Social Sharing
*	5. Custom Favicon / Apple Touch Icon
*	6. Single Post Pagination
*	7. Post Meta
*	8. Footer Social Icons
*	9. Open Graph
*
============================================ */


/*	Markup Actions Example
================================================== */
// function element_before( $args = NULL ) {
// 	global $post;
// 	$class    = isset($args['class']) ? $args['class'] : null;
// 	$html = '';
// 	$html .= '<div class="' . $class .'">';
// 	echo $html;
// }
// add_action('simple_element_before', 'element_before');

// function element( $args = NULL ) {
// 	global $post;
// 	$class    = isset($args['class']) ? $args['class'] : null;
// 	$html .= '<h1 class="page-title">' . get_the_title() . '</h1>';
// 	echo $html;
// }
// add_action('simple_element', 'element');

// function element_after( $args = NULL ) {
// 	$html .= '</div>';
// 	echo $html;
// }
// add_action('simple_element_after', 'element_after');
/* To be used in template or whatnot
	do_action('simple_element_before');
	do_action('simple_element');
	do_action('simple_element_after');
*/



/*	1. Grid Markup : class: content, scrollTarget, grid: true
================================================== */
//	Grid Before
if ( !function_exists('grid_before') ) {
	function grid_before( $args = NULL ) {

		global $post, $container_class, $grid;

		// args
		$container_class 	= isset($args['class']) ? $args['class'] : false;
		$scrollTarget 		= isset($args['scrollTarget']) ? 'data-scroll-target=' . $args['scrollTarget'] : null;
		$grid 				= isset($args['grid']) ? $args['grid'] : true; // defaults to true

		// allow filtering in child themes
		$container_class 	= apply_filters('grid_before', $container_class);

		$html = '';

		if ( $container_class ) {
			$html .= '<div class="'. $container_class .'" '. $scrollTarget .'>';
		}

		if ( $grid ) {
			$html .= '<div data-layout="grid">';
		}

		echo $html;
	}
	add_action('simple_grid_before', 'grid_before');
}


//	Grid After
if ( !function_exists('grid_after') ) {
	function grid_after( $args = NULL ) {

		global $grid, $container_class;

		$html = '';

		if ( $grid ) {
			$html .= '</div>';
		}

		if ( $container_class ) {
			$html .= '</div>';
		}

		echo $html;
	}
	add_action('simple_grid_after', 'grid_after');
}



/*	2. ARCHIVE MARKUP
================================================== */
// Grab blog layout options
global $presentation_options;
$presentation_options = of_get_option( 'blog_layout' );

//	Archive Before
if ( !function_exists('archives_before') ) {
	function archives_before( $args = NULL ) {

		global $presentation_options;

		// args
		$container_class 	= isset($args['class']) ? $args['class'] : 'primary';

		$html = '';

		// get_sidebar outputs, place outside of $html
		if ( 'left_sidebar_layout' == $presentation_options ) :
			include simple_sidebar_path();
		endif;

		$html .= '<div class="'.$container_class.'">';

		echo $html;
	}
	add_action('simple_archives_before', 'archives_before');
}

//	Archive After
if ( !function_exists('archives_after') ) {
	function archives_after( $args = NULL ) {

		global $presentation_options;

		$html = '</div>';

		echo $html;

		// get_sidebar outputs, place outside of $html
		if ( 'right_sidebar_layout' == $presentation_options ) :
			include simple_sidebar_path();
		endif;
	}
	add_action('simple_archives_after', 'archives_after');
}




/*	3. Footer Widgets
================================================== */
if ( !function_exists('simple_footer_widgets') ) {

	function simple_footer_widgets() {
		$footer_widgets = of_get_option('footer_multi_checkbox')['footer_widgets_checkbox'];

		if ( $footer_widgets ) { ?>

			<section class="footer-widgets">
				<div data-layout="grid">
					<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {

						$sidebars = [];
						if ( substr( $sidebar['id'], 0, 15 ) == 'sidebar_footer_' ) {
							array_push($sidebars, $sidebar['id']);
							foreach ($sidebars as $sidebar) {
								// sp( $sidebar );
								if ( is_active_sidebar( $sidebar ) ) : ?>

									<?php dynamic_sidebar($sidebar); ?>

								<?php else : ?>

								<div class="widget">
									<h6 class="widgettitle">Widget Title</h6>
									<p><a href="http://wordpress.dev/wp-admin/widgets.php">Click here to assign a widget to this area.</a></p>
								</div>

								<?php endif;
							}
						}

					} ?>
				</div>
			</section>

		<?php }
	}
	add_action( 'simple_footer_widgets', 'simple_footer_widgets' );

}




/*	4. SOCIAL SHARING MARKUP
================================================== */
if ( !function_exists('simple_social_share') ) {
	function simple_social_share( $args = NULL ) {
		global $post;

		$socialShare = of_get_option('blog_multi_checkbox')['blog_sharing_checkbox'];

		$html = '';

		if ($socialShare){
			$html .= get_template_part('partials/share');
		}

		return $html;
	}
	add_action('simple_social_share', 'simple_social_share');
}



/*	5. CUSTOM FAVICON AND APPLE TOUCH ICON
================================================== */
if ( !function_exists('simple_add_favicon') ) {

	function simple_add_favicon() {

		$favicon = of_get_option('favicon');
		$appleicon = of_get_option('appleicon');

		if (empty($favicon) ) {
			$favicon = get_stylesheet_directory_uri() . '/assets/images/browser-icons/favicon.ico';
		}

		if (empty($appleicon) ) {
			$appleicon = get_stylesheet_directory_uri() . '/assets/images/browser-icons/apple-touch-icon.png';
		}
		?>
			<link rel="shortcut icon" href="<?php echo $favicon ?>"/>
			<link rel="apple-touch-icon-precomposed" href="<?php echo $appleicon ?>"/>

		<?php
	}

	add_action('wp_head', 'simple_add_favicon');
}



/*	6. SINGLE POST PAGINATION
================================================== */
if ( !function_exists('simple_post_pagination') ) {

	function simple_post_pagination( $args = NULL ) {

		if ( of_get_option('blog_multi_checkbox')['post_pagination_checkbox'] ){

			$showTitles 		= isset($args['showTitles']) ? $args['showTitles'] : true;

			// Prev and Next post links
			echo '<div class="post-pagination">';
				if ( $showTitles ) :
					previous_post_link();
					next_post_link();
				else :
					previous_post_link('%link', 'Prev Post');
					next_post_link('%link', 'Next Post');
				endif;
			echo '</div>';
		}
	}
	add_action('simple_post_pagination','simple_post_pagination');
}



/*	7. POST META
================================================== */
if ( !function_exists('simple_post_meta') ) {

	function simple_post_meta( $args = NULL ) {

		$author 		= isset($args['author']) ? $args['author'] : false;
		$date 			= isset($args['date']) ? $args['date'] : false;
		$tags 			= isset($args['tags']) ? $args['tags'] : false;
		$categories 	= isset($args['categories']) ? $args['categories'] : false;
		$wordcount 		= isset($args['wordcount']) ? $args['wordcount'] : false;
		$reading_time 	= isset($args['reading_time']) ? $args['reading_time'] : false;
		$views 			= isset($args['views']) ? $args['views'] : false;
		$comments 		= isset($args['comments']) ? $args['comments'] : false;
		$icons 			= isset($args['icons']) ? $args['icons'] : false;

		$html = '';

	?>

	<?php
	    $html .= '<div class="post-meta">';

	    	$post_options = of_get_option('blog_postmeta_checkbox');

    		if ( $author ) :
	    		if ( $post_options['post_author'] ) :
					$html .= '<span class="meta-label meta-author">';
					if ( $icons ) :
						$html .= '<i class="ion-person"></i>';
					endif;
					$html .= '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">';
					$html .= get_the_author();
					$html .= '</a>';
					$html .= '</span>';
				endif;
			endif;

			if ( $date ) :
				if ( $post_options['post_date'] ) :
					$html .= '<span class="meta-label meta-time">';
					if ( $icons ) :
						$html .= '<i class="ion-calendar"></i>';
					endif;
					$html .= '<time datetime="'. get_the_time( get_option('date_format') ) .'" pubdate>'. get_the_time( get_option('date_format') ) .'</time>';
					$html .= '</span>';
				endif;
			endif;

			if ( $tags ) :
	    		if ( $post_options['post_tags'] ) {
	    			if ( has_tag() ) {

	    				$html .= '<span class="meta-label meta-tags">';

	    				if ( $icons ) :
	    					$html .= '<i class="fi-check"></i>';
						endif;

						$posttags = get_the_tags();
						if ($posttags) {
							foreach($posttags as $tag) {
								$html .= '<a href="'.get_tag_link($tag->term_id).'">';
								$html .= $tag->name . ' ';
								$html .= '</a>';
							}
						}

	    				$html .= '</span>';
	    			}
	    		}
    		endif;

			if ( $categories ) :
				if ( $post_options['post_category'] ) {

					$html .= '<span class="meta-label meta-category">';

					if ( $icons ) :
						$html .= '<i class="fi-clock"></i>';
					endif;

					// sp(get_the_taxonomies()); // for custom terms

					$categories = get_the_category();
					$separator = ' ';
					$output = '';
					if ($categories){
						foreach($categories as $category) {
							$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", SIMPLE_THEME_SLUG ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
						}
						$html .= trim($output, $separator);
					}

					$html .= '</span>';
				}
			endif;

			if ( $wordcount ) :
				if ( $post_options['post_wordcount'] ) {
					$html .= '<span class="meta-label meta-wordcount">';
					if ( $icons ) :
						$html .= '<i class="ion-pound"></i>';
					endif;
					$html .= wordcount();
					$html .= __(' Words', SIMPLE_THEME_SLUG);
					$html .= '</span>';
				}
			endif;

			if ( $reading_time ) :
				if ( $post_options['post_reading_time'] ) {
					$html .= '<span class="meta-label meta-reading-time">';
					if ( $icons ) :
			    		$html .= '<i class="ion-ios7-clock"></i>';
			    	endif;
					$html .= reading_time();
					$html .= __( ' Minute Read', SIMPLE_THEME_SLUG );
					$html .= '</span>';
		    	}
	    	endif;

			if ( $views ) :
		    	if ( $post_options['post_view_count'] ) {
			    	$html .= '<span class="meta-label meta-view-count">';
			    	if ( $icons ) :
			    		$html .= '<i class="ion-eye"></i>';
			    	endif;
					$html .= wpb_get_post_views( get_the_ID() );
					$html .= '</span>';
				}
	    	endif;

			if ( $comments ) :

		    	if ( $post_options['post_comments'] ) {

			    	if ( comments_open() && ! post_password_required() ) :

						$num_comments = get_comments_number();

						if ( comments_open() ) {
							$html .= '<span class="meta-label meta-comment">';
							if ( $icons ) :
								$html .= '<i class="ion-chatbubble"></i>';
							endif;
							if ( $num_comments == 0 ) {
								$comments = __('No Comments', SIMPLE_THEME_SLUG);
							} elseif ( $num_comments > 1 ) {
								$comments = $num_comments . __(' Comments', SIMPLE_THEME_SLUG);
							} else {
								$comments = __('1 Comment', SIMPLE_THEME_SLUG);
							}
							$html .= '<a href="' . get_comments_link() .'">'. $comments.'</a>';
						} else {
							$html .=  __('Comments are off for this post.', SIMPLE_THEME_SLUG);
						}

			    		if ( $num_comments != 0 ) {
			    			$html .= '</span>';
			    		}

			    	endif;

		    	}

	    	endif;

	    $html .= '</div>';

		return $html;
	}
	add_action('simple_post_meta','simple_post_meta');
}



/*	8. FOOTER SOCIAL ICONS
================================================== */
if ( !function_exists('simple_social_footer') ) {

	function simple_social_footer( $args = NULL ) {

		$socialFooter 		= of_get_option('footer_multi_checkbox')['footer_social_checkbox'];

		// args [ classes: flip, square; iconFont: fa, ionicon, fi, etc..  ]
		$icon		= isset($args['icon']) ? $args['icon'] : true;
		$classes 	= isset($args['classes']) ? $args['classes'] : array('');
		$iconFont 	= isset($args['iconFont']) ? $args['iconFont'] : 'fa';
		$target		= isset($args['target']) ? $args['target'] : '_new';

		$c = '';

		foreach ( $classes as $class ) {
			$c .= $class . ' ';
		}

		if ( $socialFooter ) :

			$facebook_url 	= of_get_option('social_facebook_url');
			$twitter_url 	= of_get_option('social_twitter_url');
			$google_plus_url= of_get_option('social_google_plus_url');
			$instagram_url 	= of_get_option('social_instagram_url');
			$github_url 	= of_get_option('social_github_url');
			$pinterest_url 	= of_get_option('social_pinterest_url');
			$linkedin_url 	= of_get_option('social_linkedin_url');
			$dribbble_url 	= of_get_option('social_dribbble_url');
			$tumblr_url 	= of_get_option('social_tumblr_url');

			$arr = array(
				'facebook_url',
				'twitter_url',
				'google_plus_url',
				'instagram_url',
				'github_url',
				'pinterest_url',
				'linkedin_url',
				'dribbble_url',
				'tumblr_url'
			);
			$result = compact($arr);

		?>

		<ul class="social-foot">

			<?php
			foreach ( $result as $key => $value ) :

				if ( $value ) :

					$channel = preg_replace( "/_/", "-", strtolower(substr($key, 0, -4)) );


					if ( $icon ) :

						$icon = '<i class="'.$iconFont.'-'.$channel.'"></i>';
						$icon = ( in_array( 'flip', $classes ) ) ? str_repeat($icon,2) : $icon;
						echo '
						<li>
						    <a href="http://www.'.$channel.'.com/'.$value.'" title="'.ucfirst($channel).'" class="'.$channel.' '.$c.'" target="'.$target.'">
						    '.$icon.'
						    </a>
						</li>
						';

					else :

						echo '
						<li>
						    <a href="http://www.'.$channel.'.com/'.$value.'" title="'.ucfirst($channel).'" class="'.$channel.' '.$c.'">
						    '.$channel.'
						    </a>
						</li>
						';

					endif;

				endif;

			endforeach;
			?>

		</ul>

		<?php endif;

	}
	add_action('simple_social_footer','simple_social_footer');
}



/*	9. OPEN GRAPH
================================================== */
function add_opengraph() {
	global $post; // Ensures we can use post variables outside the loop

	if ( isset($post) ) {

		// Start with some values that don't change.
		echo "<meta property='og:site_name' content='". get_bloginfo('name') ."'/>"; // Sets the site name to the one in your WordPress settings
		echo "<meta property='og:url' content='" . get_permalink() . "'/>"; // Gets the permalink to the post/page

		if ( is_singular() ) { // If we are on a blog post/page
	        echo "<meta property='og:title' content='" . get_the_title() . "'/>"; // Gets the page title
	        echo "<meta property='og:type' content='article'/>"; // Sets the content type to be article.
	    } elseif (is_front_page() or is_home()) { // If it is the front page or home page
	    	echo "<meta property='og:title' content='" . get_bloginfo("name") . "'/>"; // Get the site title
	    	echo "<meta property='og:type' content='website'/>"; // Sets the content type to be website.
	    }

		if ( has_post_thumbnail( $post->ID ) ) { // If the post has a featured image.
		//  The above code apparently fails in some instances and the below code is "recommended"
		// if ( '' != get_the_post_thumbnail() ) { // http://codex.wordpress.org/Function_Reference/has_post_thumbnail
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
			echo "<meta property='og:image' content='" . esc_attr( $thumbnail[0] ) . "'/>"; // If it has a featured image, then display this for Facebook
		}
	}

}
add_action( 'wp_head', 'add_opengraph', 5 );