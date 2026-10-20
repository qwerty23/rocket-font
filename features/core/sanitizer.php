<?php
namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 사용자 입력값 검증(sanitize) 클래스
 *
 * 설정 저장 시 POST 값은 반드시 이 클래스를 거쳐야 하며,
 * CSS 파일을 만들거나 화면에 출력할 때에도 한번 더 거칩니다(저장된 예전 값 방어).
 */
class Sanitizer {


	/**
	 * 영문 폰트 CSS(URL 직접 입력) 로 허용하는 호스트
	 * 필요하면 'rocket_font_allowed_font_hosts' 필터로 추가할 수 있음
	 */
	public static function allowed_font_hosts() {
		$hosts = array(
			'fonts.googleapis.com',
			'cdn.jsdelivr.net',
			'cdnjs.cloudflare.com',
		);
		return apply_filters( 'rocket_font_allowed_font_hosts', $hosts );
	}

	/**
	 * POST 값 전체를 검증해서 저장할 옵션 배열을 돌려줌
	 *
	 * @param array $post    wp_unslash 처리된 $_POST
	 * @param array $current 현재 저장된 옵션
	 * @return array 저장할 옵션(키 => 값). 알 수 없는 키는 모두 버림
	 */
	public static function sanitize_options( $post, $current ) {

		$defaults    = RocketFont::defaults();
		$font_list   = Font_Setting::get_font_list();
		$family_list = Font_Setting::get_font_family_list();
		$tags        = Font_Setting::get_target_tag_list();
		$clean       = array();

		foreach ( $defaults as $name => $default ) {

			if ( 'update_time' === $name || 'selected_font' === $name || 'selected_font_slug' === $name ) {
				continue; // 서버에서 결정 (아래에서 한글 폰트 선택값과 함께 처리)
			}

			$has = is_array( $post ) && array_key_exists( $name, $post );
			$raw = ( $has && is_scalar( $post[ $name ] ) ) ? (string) $post[ $name ] : '';

			// 태그별 옵션 (font_use_p, font_color_h1 ...)
			if ( preg_match( '/^font_(weight|lineheight|color|use)_(.+)$/', $name, $m ) && in_array( $m[2], $tags, true ) ) {
				switch ( $m[1] ) {
					case 'use':
						$clean[ $name ] = ( 'yes' === $raw ) ? 'yes' : ''; // 체크박스: 없으면 해제
						break;
					case 'color':
						$clean[ $name ] = $has ? self::hex_color( $raw ) : self::current( $current, $name );
						break;
					default: // weight, lineheight
						$clean[ $name ] = $has ? self::int_or_empty( $raw ) : self::current( $current, $name );
				}
				continue;
			}

			// 그 외 옵션은 값이 넘어온 경우에만 갱신
			if ( ! $has ) {
				continue;
			}

			switch ( $name ) {
				case 'selected_font_family':
					$clean[ $name ] = array_key_exists( $raw, $family_list ) ? $raw : '';
					break;
				case 'en_font_add_type':
					$clean[ $name ] = in_array( $raw, array( 'select', 'text' ), true ) ? $raw : 'select';
					break;
				case 'selected_font_family_url':
					$clean[ $name ] = self::font_url( $raw );
					break;
				case 'selected_font_family_name':
					$clean[ $name ] = self::font_name( $raw );
					break;
				case 'rocketfont_class_id_list':
					$clean[ $name ] = self::selector_list( $raw );
					break;
				case 'word_keep_all':
				case 'use_editor_font':
				case 'use_async':
				case 'use_jquery':
				case 'use_tinymce_editor':
					$clean[ $name ] = ( 'yes' === $raw ) ? 'yes' : 'no';
					break;
				default:
					// 정의되지 않은 옵션은 저장하지 않음
					break;
			}
		}

		// 한글 폰트 : 폼에서는 슬러그(selected_font_slug)로 선택. 예전 폼의 selected_font(폰트 이름)도 허용
		$has_slug = is_array( $post ) && array_key_exists( 'selected_font_slug', $post ) && is_scalar( $post['selected_font_slug'] );
		$has_name = is_array( $post ) && array_key_exists( 'selected_font', $post ) && is_scalar( $post['selected_font'] );

		if ( $has_slug && isset( $font_list[ (string) $post['selected_font_slug'] ] ) ) {

			$slug = (string) $post['selected_font_slug'];
			$clean['selected_font_slug'] = $slug;
			$clean['selected_font']      = $font_list[ $slug ]['font_name'];

		} elseif ( $has_slug || $has_name ) {

			// 슬러그가 없거나 알 수 없는 값 : 이름으로 찾거나, 없으면 "선택하지 않음"
			$font_name = $has_name ? self::known_font_name( (string) $post['selected_font'], $font_list ) : '';
			$clean['selected_font']      = $font_name;
			$clean['selected_font_slug'] = self::font_slug( $font_name, '', $font_list );
		}

		return $clean;
	}

	private static function current( $current, $name ) {
		return ( is_array( $current ) && isset( $current[ $name ] ) ) ? $current[ $name ] : '';
	}

	/** 목록에 있는 한글 폰트 이름만 허용 */
	public static function known_font_name( $value, $font_list = null ) {
		if ( null === $font_list ) {
			$font_list = Font_Setting::get_font_list();
		}
		foreach ( $font_list as $font ) {
			if ( $font['font_name'] === $value ) {
				return $value;
			}
		}
		return '';
	}

	/** 선택한 폰트 이름에 해당하는 슬러그 (폼에서 넘어온 슬러그가 일치하면 그 값을 우선) */
	public static function font_slug( $font_name, $posted_slug, $font_list = null ) {
		if ( '' === $font_name ) {
			return '';
		}
		if ( null === $font_list ) {
			$font_list = Font_Setting::get_font_list();
		}
		if ( '' !== $posted_slug && isset( $font_list[ $posted_slug ] ) && $font_list[ $posted_slug ]['font_name'] === $font_name ) {
			return $posted_slug;
		}
		foreach ( $font_list as $slug => $font ) {
			if ( $font['font_name'] === $font_name ) {
				return $slug;
			}
		}
		return '';
	}

	/** #rgb / #rrggbb 만 허용. '#' 이 없으면 붙여 줌 */
	public static function hex_color( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		if ( '#' !== $value[0] ) {
			$value = '#' . $value;
		}
		return preg_match( '/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value ) ? $value : '';
	}

	/** 0 ~ 999 (소수점 둘째 자리까지) 숫자만 허용. 비어 있거나 형식이 다르면 '' */
	public static function int_or_empty( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value || ! preg_match( '/^\d{1,3}(\.\d{1,2})?$/', $value ) ) {
			return '';
		}
		// 20.0 -> 20, 1.50 -> 1.5
		return (string) ( 0 + $value );
	}

	/** 폰트 CSS URL : https 이면서 허용된 호스트만 */
	public static function font_url( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		if ( 0 === strpos( $value, '//' ) ) {
			$value = 'https:' . $value;
		}
		$url = esc_url_raw( $value, array( 'https' ) );
		if ( '' === $url ) {
			return '';
		}
		$host = wp_parse_url( $url, PHP_URL_HOST );
		if ( ! $host || ! in_array( strtolower( $host ), self::allowed_font_hosts(), true ) ) {
			return '';
		}
		return $url;
	}

	/** 영문 폰트명 : 글자, 숫자, 공백, '-', '_' 만 허용 */
	public static function font_name( $value ) {
		$value = preg_replace( '/[^\p{L}\p{N} _\-]/u', '', (string) $value );
		$value = trim( preg_replace( '/\s+/', ' ', $value ) );
		return function_exists( 'mb_substr' ) ? mb_substr( $value, 0, 60 ) : substr( $value, 0, 60 );
	}

	/**
	 * class/id 선택자 목록 : 글자, 숫자, '_', '-', '.', '#', ',', '>', '+', '~', '*', ':' 과 공백만 허용
	 * 괄호, 따옴표, 중괄호, 세미콜론, '/', '@', '<' 등은 제거(CSS 주입 방지)
	 */
	public static function selector_list( $value ) {
		$value = preg_replace( '/\s*[\r\n]+\s*/', ',', trim( (string) $value ) ); // 줄바꿈은 쉼표로
		$value = preg_replace( '/[^\p{L}\p{N}_\-\.\#\s,>\+~\*:]/u', '', $value );
		$value = trim( preg_replace( '/[ \t]+/', ' ', $value ), " ,\t" );
		$value = preg_replace( '/,{2,}/', ',', $value );
		return function_exists( 'mb_substr' ) ? mb_substr( $value, 0, 2000 ) : substr( $value, 0, 2000 );
	}
}
