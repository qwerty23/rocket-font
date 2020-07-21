<?php
namespace rocket_font;

class Plugin_Set {

	public function __construct() {
		Template::set_path( PLUGIN_DIR . 'assets/templates/' );
	}

	/**
	 * features 폴더안의 모든 클래스 파일을 싱글턴으로 로드하는  initialize 메서드
	 *
	 */
	public static function initialize( ) {
		if( class_exists( 'rocket_font\ClassLoader' ) ){
			ClassLoader::load_plugin_classes();
		}
		do_action( 'rocket_font_init' );
	}

	public static function _activate_plugin() {
		if( function_exists( 'get_called_class' ) ){
			$calledClass = get_called_class( );
			if( class_exists('rocket_font\PluginOptions' ) ){
				PluginOptions::upgrade_plugin_options( $calledClass::defaults() );
			}
			$calledClass::activate_plugin();
		}
	}
}