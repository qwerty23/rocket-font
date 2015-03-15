<?php
namespace rocket_font;

class Template {

	private static $template_path;

	private $vars;

	public function get_path() {
		return self::$template_path;
	}

	public function __construct( $path = NULL ) {
		if ( !is_null( $path ) ) {
			self::set_path( $path );
		}
		$this->vars = array();
	}

	public static function set_path( $path ) {
		self::$template_path = $path;
	}

	public function clear() {
		$this->vars = array();
	}

	public function set( $name, $value ) {
		$this->vars[ $name ] = $value;
	}

	public function set_vars( $pairs, $append = TRUE ) {
		if ( is_array( $pairs ) ) {
			if ( $append && is_array( $this->vars ) ) {
				$this->vars = array_merge( $this->vars, $pairs );
			}
			else {
				$this->vars = $pairs;
			}
		}
	}

	public function apply( $file, $use_default_path = TRUE ) {
		extract( $this->vars );
		ob_start();
		if ( $use_default_path == TRUE ) {
			include( self::$template_path . $file );
		}
		else {
			include( $file );
		}
		$contents = ob_get_contents();
		ob_end_clean();

		$this->clear();

		return $contents;
	}
} // END CLASS
