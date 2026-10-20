<?php
namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

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
		$saved = get_option( self::$option_key );
		return shortcode_atts( RocketFont::defaults(), is_array( $saved ) ? $saved : array() );
	}

	/**
	 * 여러 옵션을 한번에 저장
	 * 플러그인에 정의된 옵션 키(RocketFont::defaults)만 저장되며 그 외의 키는 무시됨
	 * 값 검증은 호출하는 쪽(Sanitizer)에서 끝낸 상태여야 함
	 *
	 * @param array $values 키 => 값
	 * @return boolean
	 */
	public static function update_options( $values ) {
		if ( ! is_array( $values ) ) {
			return false;
		}

		$allowed = RocketFont::defaults();
		$options = get_option( self::$option_key );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		foreach ( $values as $name => $value ) {
			if ( array_key_exists( $name, $allowed ) ) {
				$options[ $name ] = $value;
			}
		}

		return update_option( self::$option_key, $options );
	}

	public static function update_option( $name, $value ) {
		$options = get_option( self::$option_key );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options[ $name ] = $value;

		return update_option( self::$option_key, $options );
	}
} // END CLASS
