<?php

namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 글 편집 화면에서 "선택한 글자만 다른 한글 폰트로" 바꾸는 기능의 공통 코드
 *
 * 글에는 아래와 같은 태그가 저장된다.
 *
 *   <span class="rocketfont" data-rocketfont="pretendard">글자</span>
 *
 *  - class 는 항상 "rocketfont" 로 고정 : 다른 서식(글자 색 등)이나 다른 플러그인과 겹치지 않도록 하기 위함
 *  - data-rocketfont 에는 폰트 슬러그만 저장 : 목록에 있는 슬러그만 인정하므로 다른 값은 무시된다
 *
 * 블록 에디터, 클래식 에디터, 사이트 화면이 모두 같은 규칙을 쓰도록 이 클래스에 모아 둔다.
 */
class EditorFonts {

	const ATTRIBUTE  = 'data-rocketfont';
	const CLASS_NAME = 'rocketfont';

	/**
	 * 설정에서 이 기능을 켜 두었는지
	 */
	public static function is_enabled() {

		$options = PluginOptions::get_current_all_options();

		return ( 'yes' === $options['use_editor_font'] );
	}

	/**
	 * 폰트 CSS 주소 ( // 로 시작하면 https: 를 붙인다 )
	 */
	public static function css_url( $font ) {

		if ( empty( $font['cdn_url'] ) ) {
			return '';
		}

		$url = $font['cdn_url'];

		return ( 0 === strpos( $url, '//' ) ) ? 'https:' . $url : $url;
	}

	/**
	 * 폰트 하나에 대한 CSS 규칙
	 */
	public static function rule( $slug, $font ) {

		$name = str_replace( array( '"', '\\', '<', '>', "\n", "\r" ), '', $font['font_name'] );

		return '.' . self::CLASS_NAME . '[' . self::ATTRIBUTE . '="' . $slug . '"]{font-family:"' . $name . '",sans-serif;}';
	}

	/**
	 * 모든 폰트의 CSS 규칙 (에디터 화면에서 사용)
	 */
	public static function all_rules() {

		$css = '';

		foreach ( Font_Setting::get_font_list() as $slug => $font ) {
			$css .= self::rule( $slug, $font );
		}

		return $css;
	}

	/**
	 * HTML 안에서 사용된 폰트 슬러그를 찾는다 (목록에 있는 슬러그만 돌려줌)
	 *
	 * @param string $html
	 * @return array 슬러그 목록
	 */
	public static function extract_slugs( $html ) {

		if ( ! is_string( $html ) || false === strpos( $html, self::ATTRIBUTE ) ) {
			return array();
		}

		// data-rocketfont="slug" / data-rocketfont='slug' / data-rocketfont=\"slug\" (JSON 안) / &quot;slug&quot;
		if ( ! preg_match_all( '/' . self::ATTRIBUTE . '\s*=\s*(?:\\\\?["\']|&quot;)([A-Za-z0-9_\-]+)/', $html, $m ) ) {
			return array();
		}

		$fonts = Font_Setting::get_font_list();
		$found = array();

		foreach ( array_unique( $m[1] ) as $slug ) {
			if ( isset( $fonts[ $slug ] ) ) {
				$found[] = $slug;
			}
		}

		return $found;
	}

	/**
	 * 에디터(블록/클래식)에 넘겨줄 폰트 정보
	 */
	public static function payload() {

		$payload = array(
			'fonts'  => array(),
			'groups' => array(),
			'rules'  => self::all_rules(),
			'i18n'   => array(
				'title'       => __( '한글 폰트', PLUGIN_PREFIX ),
				'button'      => __( '한글', PLUGIN_PREFIX ), // 툴바 버튼에 표시되는 이름
				'search'      => __( '폰트 검색', PLUGIN_PREFIX ),
				'noResults'   => __( '검색 결과가 없습니다.', PLUGIN_PREFIX ),
				'reset'       => __( '기본 폰트로 되돌리기', PLUGIN_PREFIX ),
				'description' => __( '글자를 드래그해서 고른 뒤 폰트를 선택하세요.', PLUGIN_PREFIX ),
			),
		);

		foreach ( Font_Setting::get_grouped_font_list() as $key => $group ) {

			$slugs = array();

			foreach ( $group['fonts'] as $slug => $font ) {

				$payload['fonts'][ $slug ] = array(
					'name'  => $font['font_name'],
					'title' => $font['font_text_title'],
					'css'   => self::css_url( $font ),
					'face'  => isset( $font['inline_font_face'] ) ? $font['inline_font_face'] : '',
				);

				$slugs[] = $slug;
			}

			$payload['groups'][] = array(
				'key'   => $key,
				'label' => $group['label'],
				'slugs' => $slugs,
			);
		}

		return $payload;
	}
}
