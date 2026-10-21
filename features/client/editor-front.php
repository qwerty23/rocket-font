<?php

namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 사이트 화면 : 글에서 실제로 쓰인 폰트만 불러온다
 *
 * 글 안의 <span class="rocketfont" data-rocketfont="슬러그"> 를 찾아서
 *  - 그 폰트의 CSS 파일(CDN)을 불러오고
 *  - 폰트를 적용하는 아주 작은 CSS 규칙을 추가한다.
 * 쓰이지 않은 폰트는 아무것도 불러오지 않으므로 사이트 속도에 거의 영향이 없다.
 */
class EditorFront extends Feature {

	const STYLE_HANDLE = 'rocket-font-editor-front';

	/** 이미 처리한 슬러그 */
	private $done = array();

	public function __construct() {

		if ( ! EditorFonts::is_enabled() ) {
			return;
		}

		// 글 한 개를 보여 주는 화면은 헤더에서 미리 처리 (화면이 깜빡이지 않도록)
		$this->add_action( 'wp_enqueue_scripts', 'scan_current_post', 20, 0 );

		// 목록 화면, 위젯 등은 글 내용이 출력될 때 처리 (푸터에서 CSS 가 출력됨)
		$this->add_filter( 'the_content', 'scan_content', 20, 1 );
	}

	public function scan_current_post() {

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();

		if ( $post && isset( $post->post_content ) ) {
			$this->use_fonts( EditorFonts::extract_slugs( $post->post_content ) );
		}
	}

	public function scan_content( $content ) {

		$slugs = EditorFonts::extract_slugs( $content );

		if ( $slugs ) {
			$this->use_fonts( $slugs );
		}

		return $content;
	}

	/**
	 * 폰트 CSS 와 적용 규칙을 추가한다
	 */
	private function use_fonts( $slugs ) {

		$fonts = Font_Setting::get_font_list();

		foreach ( $slugs as $slug ) {

			if ( isset( $this->done[ $slug ] ) || ! isset( $fonts[ $slug ] ) ) {
				continue;
			}

			$this->done[ $slug ] = true;
			$font                = $fonts[ $slug ];
			$url                 = EditorFonts::css_url( $font );

			if ( ! wp_style_is( self::STYLE_HANDLE, 'registered' ) ) {
				wp_register_style( self::STYLE_HANDLE, false, array(), VERSION );
			}

			if ( '' !== $url ) {
				wp_enqueue_style( 'rocket-font-ef-' . $slug, $url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- 외부 폰트 CSS 는 주소가 곧 버전이다
			}

			wp_enqueue_style( self::STYLE_HANDLE );

			$css = EditorFonts::rule( $slug, $font );

			if ( ! empty( $font['inline_font_face'] ) ) {
				$css = $font['inline_font_face'] . $css;
			}

			wp_add_inline_style( self::STYLE_HANDLE, $css );
		}
	}
}
add_action( 'rocket_font_init', array( 'rocket_font\EditorFront', 'init' ), 1, 1 );
