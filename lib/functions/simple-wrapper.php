<?php
/**
 * Simple theme wrapper based on scribu and roots
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 * @link http://roots.io
 */
function simple_template_path() {
	return Simple_Wrapping::$main_template;
}

// In conjunction with custom-templates.php
function simple_sidebar_path() {
	return new Simple_Wrapping('partials/sidebar.php');
}

class Simple_Wrapping {

	// Stores the full path to the main template file
	static $main_template;

	// Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	static $base;

	public function __construct( $template = 'template.php' ) {
		$this->slug = basename( $template, '.php' );
		$this->templates = array($template);
		
		if ( self::$base ) {
			$str = substr($template, 0, -4);
			array_unshift( $this->templates, sprintf($str . '-%s.php', self::$base) );
		}
	}
	
	// magic: must return a string, e.g. 'page'
	public function __toString() {
		$this->templates = apply_filters('simple_wrapper_' . $this->slug, $this->templates);
		return locate_template($this->templates);
	}
	
	static function wrap($main) {
		self::$main_template = $main;
		self::$base = basename( self::$main_template, '.php' );

		if ( self::$base === 'index' ) {
			self::$base = false;
		}
		
		return new Simple_Wrapping();
	}
}

add_filter('template_include', array('Simple_Wrapping', 'wrap'), 99);