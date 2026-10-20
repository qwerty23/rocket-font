<?php

namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 글 편집 화면 : 드래그한 글자만 선택한 한글 폰트로 바꾸기
 *
 *  - 블록 에디터 : 글자를 선택하면 나오는 툴바에 "한글 폰트" 버튼을 추가 (assets/js/editor-block.js)
 *  - 클래식 에디터(TinyMCE) : 도구 모음에 "한글 폰트" 메뉴를 추가 (assets/js/editor-tinymce.js)
 *
 * 두 에디터 모두 같은 형태의 태그를 만든다 (EditorFonts 클래스 설명 참고).
 * 설정에서 "에디터에서 글자별 폰트 사용"을 끄면 이 파일의 기능은 모두 동작하지 않는다.
 */
class Editor extends Feature {

	public function __construct() {

		if ( ! EditorFonts::is_enabled() ) {
			return;
		}

		// 블록 에디터
		$this->add_action( 'enqueue_block_editor_assets', 'enqueue_block_editor', 10, 0 );
		$this->add_filter( 'block_editor_settings_all', 'block_editor_settings', 10, 2 );

		// 클래식 에디터 (TinyMCE) : 블록 에디터의 "클래식" 블록에서도 같은 설정이 쓰인다
		$this->add_filter( 'mce_external_plugins', 'mce_plugin', 10, 1 );
		$this->add_filter( 'mce_buttons', 'mce_button', 10, 1 );
		$this->add_filter( 'tiny_mce_before_init', 'mce_init', 10, 1 );
	}

	/**
	 * 현재 사용자가 글을 편집할 수 있는지
	 */
	private function can_edit() {
		return current_user_can( 'edit_posts' );
	}

	/* ------------------------------------------------------------------
	 * 블록 에디터
	 * ------------------------------------------------------------------ */

	public function enqueue_block_editor() {

		if ( ! $this->can_edit() ) {
			return;
		}

		wp_enqueue_style(
			'rocket-font-editor',
			PLUGIN_URL . 'assets/css/editor-font.css?rf=' . VERSION,
			array(),
			null
		);

		wp_register_script(
			'rocket-font-editor-block',
			PLUGIN_URL . 'assets/js/editor-block.js?rf=' . VERSION,
			array( 'wp-rich-text', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			null,
			true
		);

		// 자바스크립트가 읽을 폰트 정보
		wp_add_inline_script(
			'rocket-font-editor-block',
			'window.rocketFontEditor = ' . wp_json_encode( EditorFonts::payload() ) . ';',
			'before'
		);

		wp_enqueue_script( 'rocket-font-editor-block' );
	}

	/**
	 * 블록 에디터 화면(글 내용이 보이는 영역)에 폰트 CSS 를 넣는다
	 *
	 * - 모든 폰트의 규칙(작은 CSS)은 항상 넣고
	 * - 폰트 파일이 있는 CSS 는 이 글에서 실제로 쓰인 폰트만 불러온다 (새로 고른 폰트는 자바스크립트가 불러옴)
	 *
	 * @param array                    $settings 에디터 설정
	 * @param \WP_Block_Editor_Context $context  에디터 정보
	 * @return array
	 */
	public function block_editor_settings( $settings, $context = null ) {

		if ( ! $this->can_edit() || ! is_array( $settings ) ) {
			return $settings;
		}

		if ( ! isset( $settings['styles'] ) || ! is_array( $settings['styles'] ) ) {
			$settings['styles'] = array();
		}

		$fonts = Font_Setting::get_font_list();
		$post  = ( is_object( $context ) && ! empty( $context->post ) ) ? $context->post : null;

		if ( $post && ! empty( $post->post_content ) ) {

			foreach ( EditorFonts::extract_slugs( $post->post_content ) as $slug ) {

				$font = $fonts[ $slug ];
				$url  = EditorFonts::css_url( $font );

				if ( '' !== $url ) {
					$settings['styles'][] = array( 'css' => '@import url("' . esc_url_raw( $url ) . '");' );
				}

				if ( ! empty( $font['inline_font_face'] ) ) {
					$settings['styles'][] = array( 'css' => $font['inline_font_face'] );
				}
			}
		}

		$settings['styles'][] = array( 'css' => EditorFonts::all_rules() );

		return $settings;
	}

	/* ------------------------------------------------------------------
	 * 클래식 에디터 (TinyMCE)
	 * ------------------------------------------------------------------ */

	public function mce_plugin( $plugins ) {

		if ( $this->can_edit() && is_array( $plugins ) ) {
			$plugins['rocketfont'] = PLUGIN_URL . 'assets/js/editor-tinymce.js?rf=' . VERSION;
		}

		return $plugins;
	}

	public function mce_button( $buttons ) {

		if ( ! $this->can_edit() || ! is_array( $buttons ) ) {
			return $buttons;
		}

		// 기울임 버튼 바로 뒤에 넣는다. 없으면 맨 뒤에 넣는다
		$index = array_search( 'italic', $buttons, true );

		if ( false === $index ) {
			$buttons[] = 'rocketfont';
		} else {
			array_splice( $buttons, $index + 1, 0, 'rocketfont' );
		}

		return $buttons;
	}

	/**
	 * TinyMCE 설정에 폰트 정보를 실어 보낸다 (plugin 에서 editor.getParam('rocketfont_data') 로 읽음)
	 */
	public function mce_init( $init ) {

		if ( $this->can_edit() && is_array( $init ) ) {
			$init['rocketfont_data'] = wp_json_encode( EditorFonts::payload() );
		}

		return $init;
	}
}
add_action( 'rocket_font_init', array( 'rocket_font\Editor', 'init' ), 1, 1 );
