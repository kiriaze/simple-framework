<?php
/**
 * URL Rewrites
 *
 * @link http://roots.io
 */

function simple_rewrites(){

	//  Define path constants
	define('RELATIVE_PLUGIN_PATH',  str_replace(home_url() . '/', '', plugins_url()));
	define('RELATIVE_CONTENT_PATH', str_replace(home_url() . '/', '', content_url()));
	define('THEME_NAME',            preg_replace('/\W/', '-', strtolower(get_option( 'stylesheet' )) ));
	define('THEME_PATH',            RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);

	function add_filters($tags, $function) {
		foreach( $tags as $tag ) {
			add_filter($tag, $function);
		}
	}

	function simple_base_relative_url($input) {
		preg_match('|https?://([^/]+)(/.*)|i', $input, $matches);

		if ( isset($matches[1]) && isset($matches[2]) && $matches[1] === $_SERVER['SERVER_NAME'] ) {
			return wp_make_link_relative($input);
		} else {
			return $input;
		}
	}

	function simple_enable_base_relative_urls() {
		return !( is_admin() || in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') ) ) && current_theme_supports('simple-relative-urls');
	}

	if ( simple_enable_base_relative_urls() ) {
		$simple_rel_filters = array(
			'bloginfo_url',
			'the_permalink',
			'wp_list_pages',
			'wp_list_categories',
			
			'wp_get_attachment_url',

			'the_content_more_link',
			'the_tags',
			'get_pagenum_link',
			'get_comment_link',
			'month_link',
			'day_link',
			'year_link',

			'tag_link',

			'term_link',

			'the_author_posts_link',
			'script_loader_src',
			'style_loader_src'
		);

		add_filters($simple_rel_filters, 'simple_base_relative_url');
	}


	/**
	 * URL rewriting
	 *
	 * Rewrites do not happen for multisite installations or child themes
	 *
	 * Rewrite:
	 *   /wp-content/themes/themename/assets/css/ to /assets/css/
	 *   /wp-content/themes/themename/assets/js/  to /assets/js/
	 *   /wp-content/themes/themename/assets/img/ to /assets/img/
	 *   /wp-content/plugins/                     to /plugins/
	 *
	 * If you aren't using Apache, alternate configuration settings can be found in the docs.
	 *
	 * 
	 */
	function simple_add_rewrites($content) {
		global $wp_rewrite;
		$simple_new_non_wp_rules = array(
			'assets/(.*)'          => THEME_PATH . '/assets/$1',
			'plugins/(.*)'         => RELATIVE_PLUGIN_PATH . '/$1'
		);
		$wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $simple_new_non_wp_rules);
		return $content;
	}

	function simple_clean_urls($content) {
		if ( strpos($content, RELATIVE_PLUGIN_PATH) > 0 ) {
			return str_replace('/' . RELATIVE_PLUGIN_PATH,  '/plugins', $content);
		} else {
			return str_replace('/' . THEME_PATH, '', $content);
		}
	}

	if ( !is_multisite() && !is_child_theme() ) {

		if ( current_theme_supports('simple-rewrites') ) {
			add_action('generate_rewrite_rules', 'simple_add_rewrites');
		}

		if ( !is_login() && !is_admin() && current_theme_supports('simple-rewrites') ) {
			$tags = array(
				'plugins_url',
				'bloginfo',
				'stylesheet_directory_uri',
				'template_directory_uri',
				'script_loader_src',
				'style_loader_src'
			);

			add_filters($tags, 'simple_clean_urls');
		}

	}
	
}
add_action( 'after_setup_theme', 'simple_rewrites', 11 );