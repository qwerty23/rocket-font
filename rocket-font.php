<?php
/**
 * Plugin Name:       한글
 * Plugin URI:        http://in-web.co.kr/wordpress/plug-in/wordpress-%EC%97%90%EC%84%9C-%ED%95%9C%EA%B8%80-%ED%8F%B0%ED%8A%B8%EB%A5%BC-rocket-font/
 * Description:       한글 폰트를 쉽게 적용하는 플러그인입니다. 폰트를 고르고 저장하기만 하면 CSS 파일을 고치지 않고도 사이트 전체에 한글 폰트가 적용됩니다. (Apply Korean web fonts to your site without editing any CSS.)
 * Version:           2.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Qwerty23
 * Author URI:        http://in-web.co.kr/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       RocketFont
 * Domain Path:       /languages
 */

namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

define( 'rocket_font\VERSION', '2.0.0' );
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
			load_plugin_textdomain( PLUGIN_PREFIX, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
			self::$__instance = new RocketFont();
			parent::initialize();
			self::maybe_upgrade();
		}
		return self::$__instance;
	}

	/**
	 * 플러그인이 업데이트된 직후 한 번만 실행
	 *
	 * 폰트 목록의 주소나 이름이 바뀌었을 수 있으므로 이미 저장된 설정으로 CSS 파일을 다시 만들어,
	 * 사이트 주인이 설정 화면에서 다시 저장하지 않아도 폰트가 계속 올바르게 표시되도록 한다.
	 */
	private static function maybe_upgrade() {

		if ( VERSION === get_option( 'rocket_font_version' ) ) {
			return;
		}

		// 다른 요청이 동시에 같은 작업을 하지 않도록 버전 기록을 먼저 남긴다
		update_option( 'rocket_font_version', VERSION, true );

		$options = PluginOptions::get_current_all_options();
		$slug    = $options['selected_font_slug'];
		$fonts   = Font_Setting::get_font_list();

		if ( '' === $slug || ! isset( $fonts[ $slug ] ) ) {
			return; // 아직 폰트를 설정하지 않은 사이트
		}

		// 폰트는 슬러그로 찾기 때문에 저장된 설정은 그대로 두고, 목록의 현재 이름과 주소로 CSS 파일만 다시 만든다
		// (예: NotoSansKR -> Noto Sans KR)
		Font_Setting::generate_css( $options );
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
			"use_async"				=> "no",
			"use_jquery"			=> "no",
			"use_tinymce_editor"	=> "no",
			"use_editor_font"		=> "yes",
			"rocketfont_class_id_list"	=> ".rocket-font,#rocket-font,.content,.content>p,#content,#content>p",
			"en_font_add_type"		=> "select",
			"selected_font_family_url"	=> "",
			"selected_font_family_name"	=> "",
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
			$defaults_value['font_use_body'] = "yes";
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

		// 활성화 안내 말풍선을 다시 표시할 수 있도록 닫힘 기록을 지운다
		$value = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
		$dismissed_pointers = is_array( $value ) ? $value : explode( ',', (string) $value );

		$index = array_search( 'rocketfont_settings_pointer', $dismissed_pointers, true );
		if( false !== $index ) {
			unset( $dismissed_pointers[ $index ] );
			update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', implode( ',', $dismissed_pointers ) );
		}
	}

} // End Class

add_action( 'plugins_loaded', array( 'rocket_font\RocketFont', 'init' ) );
register_activation_hook( __FILE__, array( 'rocket_font\RocketFont', '_activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'rocket_font\RocketFont', 'deactivate_plugin' ) );
