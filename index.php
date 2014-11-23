<?php
/**
 * The template is for rendering the index.
 *
 * @package 	WordPress
 * @subpackage 	Simple
 * @version 	1.0
*/
?>

<?php

if ( is_post_type_archive($post_type) ) :

	if ( $post_type && locate_template( '/templates/archive-'.$post_type.'.php' ) != '' ) :
		$template = 'archive-'.$post_type.'.php';
	else :
		$template = 'archive.php';
	endif;

	require_once locate_template( '/templates/' . $template );

elseif ( is_404() || is_search() ) :

	$template = '404.php';
	require_once locate_template( '/templates/' . $template );

elseif ( is_page() ) :

	$template = 'page.php';
	require_once locate_template( '/templates/' . $template );

elseif ( is_singular($post_type) ) :

	if( $post_type && locate_template( '/templates/single-'.$post_type.'.php' ) != '' ) :
		$template = 'single-'.$post_type.'.php';
	else :
		$template = 'single.php';
	endif;

	require_once locate_template( '/templates/' . $template );

elseif ( is_tax() ) :

	$tax = $wp_query->get_queried_object();
	$taxonomy = $tax->taxonomy;

	if ( locate_template( '/templates/taxonomy-'.$taxonomy.'.php' ) != '' ) :
		$template = 'taxonomy-'.$taxonomy.'.php';
	else :
		$template = 'archive.php';
	endif;

	require_once locate_template( '/templates/' . $template );

elseif ( is_archive() ) :

	$template = 'archive.php';
	require_once locate_template( '/templates/' . $template );

else :

	// $template = 'blog.php';
	// require_once locate_template( '/templates/' . $template );

	// or :

	if ( have_posts() ) :

		while ( have_posts() ) : the_post();

			get_template_part( 'content/content', get_post_format() );

		endwhile;

	else :

		get_template_part( 'partials/no-results' );

	endif;

	wp_reset_postdata();

endif;

?>