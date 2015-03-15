<?php
namespace rocket_font;

class PluginOptions {

	/**
	 * @var 플러그인 옵션 키값
	 */
	public static $option_key = "rocket_font";

	public static function upgrade_plugin_options( $defaults = NULL ) {
		$options = get_option( self::$option_key );

		if ( $options === FALSE ) {
			$options = $defaults;
		} else {
			$options = shortcode_atts( $defaults, $options );
		}
		return update_option( self::$option_key, $options );
	}

	public static function get_option( $name , $default ) {
		$options = get_option( self::$option_key );
		if ( is_array( $options ) && array_key_exists( $name, $options ) ) {
			return $options[ $name ];
		}
		return $default;
	}
	
	public static function get_current_all_options() {
		return shortcode_atts(RocketFont::defaults(), get_option( self::$option_key ));
	}
	
	/**
	 *  post 값과 default(이전에 저장된 옵션값) 를 비교 후 post 값에 변화가 있으면 업데이트
	 *
	 * @param $post  사용자가 입력한 폼값
	 */
	public static function update_option_check_match_default($post){
		$options = shortcode_atts(RocketFont::defaults(), self::get_current_all_options());
		foreach($options as $option_name => $option_value):
			//if(empty($post[$option_name])) continue;
			
			//값이 존재하고 그 값이 기본값과 일치하지 않을 경우만 업데이트
			if($option_value != $post[$option_name]):
				self::update_option($option_name, $post[$option_name]);
			endif;
		endforeach;
	}
	
	public static function update_option( $name, $value ) {
		$options          = get_option( self::$option_key );
		$options[ $name ] = $value;

		return update_option( self::$option_key, $options );
	}
} // END CLASS