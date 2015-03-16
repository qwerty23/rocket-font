<?php
/*
 *	Plugin Name: 	Rocket Font
 *	Description:    한국 유저를 위한 한글 폰트 플러그인
 *	Author:         Park Jin Han
 *	Author URI:     http://in-web.co.kr
 *	Version: 		0.0.1
 *	Text Domain: 	RocketFont
 *	Domain Path: 	languages/
 */

namespace rocket_font;

define( 'rocket_font\VERSION', '0.0.1' );
define( 'rocket_font\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'rocket_font\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'rocket_font\PLUGIN_PREFIX'	, 'RocketFont');
define( 'rocket_font\PLUGIN_MENU_SLUG'	, 'rocket-font-setting');

require_once( 'features/feature.php' );
require_once( 'features/template.php' );
require_once( 'features/options.php' );
require_once( 'features/class-loader.php' );
require_once( 'features/plugin-set.php' );

class RocketFont extends Plugin_Set {

	private static $__instance;

	public static function init() {
		if ( !self::$__instance ) {
			load_plugin_textdomain( PLUGIN_PREFIX, FALSE, PLUGIN_DIR . 'languages' );
			self::$__instance = new RocketFont();
			parent::initialize();
		}
		return self::$__instance;
	}

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 플러그인 옵션 기본값 정의
	 *
	 * @static
	 * @return array 플러그인 옵션 기본값
	 */
	public static function defaults() {

		$defaults_value = array(
			"update_time"			=> time(),
			"selected_font"			=> "",
			"selected_font_family"	=> "",
			"selected_font_slug"	=> "",
			"use_jquery"			=> "no",
			"use_tinymce_editor"	=> "no",
			
		);
		
		if( class_exists('rocket_font\Font_Setting' ) ){
				
			$target_tag = Font_Setting::get_target_tag_list();
			$create_value_names = array("font_weight_", "font_lineheight_", "font_color_", "font_use_");
			
			$create_values = array();
			foreach($create_value_names as $value_name):
				foreach($target_tag as $tag):
					$create_values[$value_name . $tag] = "";
				endforeach;
			endforeach;
			
			$defaults_value = array_merge($defaults_value, $create_values);
		}
		
		
		return $defaults_value;
	}

	/**
	 * Plugin activation hook
	 *
	 * 플러그인 활성화시 실행
	 *
	 * @static
	 * @hook register_activation_hook
	 */
	public static function activate_plugin() {
		
	}
	
	/**
	 * Plugin deactivation hook
	 *
	 * 플러그인 비활성화시 실행
	 *
	 * @static
	 * @hook register_deactivation_hook
	 */
	public static function deactivate_plugin() {
		$dismissed_pointers_values = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
		
		if($dismissed_pointers_values){
			
			$dismissed_pointers = explode( ',', $dismissed_pointers_values );
		
			if( in_array( 'rocketfont_settings_pointer', $dismissed_pointers ) ) {
				
				unset($dismissed_pointers[array_search("rocketfont_settings_pointer",$dismissed_pointers)]);
				update_user_meta(get_current_user_id(),'dismissed_wp_pointers',$dismissed_pointers);
				
			}
		}
	}

} // End Class

add_action( 'plugins_loaded', array( 'rocket_font\RocketFont', 'init' ) );
register_activation_hook( __FILE__, array( 'rocket_font\RocketFont', '_activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'rocket_font\RocketFont', 'deactivate_plugin' ) );
