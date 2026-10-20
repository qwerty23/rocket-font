<?php

namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 관리자 설정 화면
 *
 * 워드프레스 Settings API 를 사용한다.
 *
 *  - register_setting() 으로 옵션(rocket_font)과 검증 함수를 등록
 *  - 폼은 options.php 로 전송 → 권한(manage_options), nonce 확인, 검증, 저장은 워드프레스 코어가 처리
 *  - 저장 직후 update_option_rocket_font 훅에서 CSS 파일을 생성
 *  - 알림은 add_settings_error() / settings_errors() (설정 메뉴 아래의 화면은 코어가 자동으로 출력)
 *
 * 화면(미리보기 포함)은 직접 만든 템플릿을 사용하므로 do_settings_sections() 는 사용하지 않는다.
 */
class Setting extends Feature {

	/** 옵션 그룹 이름 (settings_fields() 와 register_setting() 에서 같은 값 사용) */
	const OPTION_GROUP = 'rocket_font_group';

	public function __construct() {

		$this->add_action( 'admin_menu', 'rocket_font_menu' );
		$this->add_action( 'admin_init', 'register_settings', 10, 0 );

		// 저장(추가/수정) 직후 CSS 파일 생성
		$this->add_action( 'update_option_' . PluginOptions::$option_key, 'on_option_updated', 10, 2 );
		$this->add_action( 'add_option_' . PluginOptions::$option_key, 'on_option_added', 10, 2 );

		// 기본값으로 되돌리기 (admin-post.php)
		$this->add_action( 'admin_post_rocket_font_reset', 'handle_reset', 10, 0 );

		if ( $this->is_setting_page() ) {
			$this->add_action( 'admin_enqueue_scripts', 'enqueue' );
		}

		$this->add_filter( 'mce_css', 'add_tiny_mce_css' );
	}

	/**
	 * 지금 보고 있는 화면이 이 플러그인의 설정 화면인지
	 */
	private function is_setting_page() {
		return isset( $_GET['page'] ) && PLUGIN_MENU_SLUG === sanitize_key( wp_unslash( $_GET['page'] ) );
	}

	/**
	 * Settings API 등록
	 */
	public function register_settings() {

		register_setting(
			self::OPTION_GROUP,
			PluginOptions::$option_key,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => RocketFont::defaults(),
				'show_in_rest'      => false,
			)
		);
	}

	/**
	 * 저장 전 검증 (options.php 가 호출)
	 *
	 * 첫 저장 때는 워드프레스가 이 함수를 두 번 호출할 수 있으므로,
	 * 이미 검증된 값을 다시 넣어도 같은 결과가 나오도록(멱등) 만들어져 있다.
	 *
	 * @param mixed $input 폼에서 넘어온 rocket_font[...] 배열
	 * @return array 저장할 옵션 전체
	 */
	public function sanitize_settings( $input ) {

		$current = self::get_current_all_options();

		if ( ! is_array( $input ) ) {
			return $current;
		}

		$clean = Sanitizer::sanitize_options( $input, $current );

		$merged = array_merge( $current, $clean );
		$merged['update_time'] = time(); // CSS 주소의 버전 값 (브라우저 캐시 갱신용)

		return $merged;
	}

	/**
	 * 옵션이 수정된 직후 : CSS 파일 생성
	 */
	public function on_option_updated( $old_value, $value ) {
		$this->after_save( $value );
	}

	/**
	 * 옵션이 처음 추가된 직후 : CSS 파일 생성
	 */
	public function on_option_added( $option, $value ) {
		$this->after_save( $value );
	}

	private function after_save( $value ) {

		$options = shortcode_atts( RocketFont::defaults(), is_array( $value ) ? $value : array() );
		$ok      = $this->write_css( $options );

		if ( $ok ) {
			add_settings_error(
				PLUGIN_PREFIX,
				'saved',
				sprintf(
					/* translators: %s: link to the site */
					__( '설정을 저장했습니다. %s', PLUGIN_PREFIX ),
					'<a href="' . esc_url( home_url( '/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( '내 사이트에서 확인하기', PLUGIN_PREFIX ) . '</a>'
					. ' <span class="description">' . esc_html__( '(바로 바뀌지 않으면 캐시 플러그인과 브라우저 캐시를 비워 주세요.)', PLUGIN_PREFIX ) . '</span>'
				),
				'success'
			);
		} else {
			add_settings_error(
				PLUGIN_PREFIX,
				'saved_css_error',
				__( '설정은 저장했지만 폰트 CSS 파일을 만들지 못했습니다. wp-content/uploads 폴더에 쓰기 권한이 있는지 확인해 주세요.', PLUGIN_PREFIX ),
				'warning'
			);
		}
	}

	/**
	 * 모든 설정을 기본값으로 되돌리기
	 */
	public function handle_reset() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( '이 설정을 변경할 권한이 없습니다.', PLUGIN_PREFIX ), '', array( 'response' => 403 ) );
		}

		check_admin_referer( 'rocket_font_reset', 'rocket_font_reset_nonce' );

		delete_option( PluginOptions::$option_key );
		$this->write_css( RocketFont::defaults() );

		wp_safe_redirect( add_query_arg( 'rf_reset', '1', admin_url( 'options-general.php?page=' . PLUGIN_MENU_SLUG ) ) );
		exit;
	}

	//관리 메뉴 추가
	public function rocket_font_menu() {

		add_options_page( 'Rocket Font', '한글 폰트', 'manage_options', PLUGIN_MENU_SLUG, array( $this, 'setting_page' ) );

		$dismissed_pointers_values = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );

		if ( is_array( $dismissed_pointers_values ) ) {
			$dismissed_pointers = $dismissed_pointers_values;
		} else {
			$dismissed_pointers = explode( ',', (string) $dismissed_pointers_values );
		}

		if ( ! in_array( 'rocketfont_settings_pointer', $dismissed_pointers, true ) ) {
			$this->add_action( 'admin_print_footer_scripts', 'show_post_pointer' );
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
		}
	}

	function show_post_pointer() {
		?>
		<script>
		jQuery(document).ready( function($) {

			if($("#menu-settings .menu-icon-settings").length > 0){

				var options = {"content":"<h3>Rocket Font<\/h3>"+'<p>플러그인이 활성화 되었습니다.</p><p>[설정] > [한글 폰트] 메뉴에서 폰트를 골라 주세요.</p>',"position":{"edge":"left","align":"center"}};

				options = $.extend( options, {
					close: function() {
						$.post( ajaxurl, {
							pointer: 'rocketfont_settings_pointer',
							action: 'dismiss-wp-pointer'
						});
					}
				});
				$('#menu-settings .menu-icon-settings').pointer( options ).pointer("open");
			}
		});
		</script>
		<?php
	}

	//관리 메뉴 랜더링
	public function setting_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( '이 페이지에 접근할 권한이 없습니다.', PLUGIN_PREFIX ), '', array( 'response' => 403 ) );
		}

		$template = self::get_template();

		$template->set( 'option_key', PluginOptions::$option_key );
		$template->set( 'option_group', self::OPTION_GROUP );
		$template->set( 'reset_url', admin_url( 'admin-post.php' ) );
		$template->set( 'was_reset', isset( $_GET['rf_reset'] ) );
		$template->set( 'plugin_url', PLUGIN_URL );
		$template->set( 'version', VERSION );
		$template->set( 'options', self::get_current_all_options() );
		$template->set( 'font_list', Font_Setting::get_font_list() );
		$template->set( 'font_groups', Font_Setting::get_grouped_font_list() );
		$template->set( 'target_tag', Font_Setting::get_target_tag_list() );
		$template->set( 'font_family_list', Font_Setting::get_font_family_list() );

		echo $template->apply( 'admin/setting.php' ); // phpcs:ignore WordPress.Security.EscapeOutput -- 템플릿 안에서 이스케이프 처리
	}

	/**
	 * 설정값으로 CSS 파일을 만든다 (uploads/rocket-font/)
	 *
	 * @param array|null $options 저장된 옵션 (null 이면 현재 옵션)
	 * @return boolean 성공 여부
	 */
	function write_css( $options = null ) {

		if ( null === $options ) {
			$options = self::get_current_all_options();
		}

		return Font_Setting::generate_css( $options );
	}

	function add_tiny_mce_css( $mce_css ) {

		$options = self::get_current_all_options();

		if ( 'yes' === $options['use_tinymce_editor'] && $options['selected_font'] ) {

			$font_list = Font_Setting::get_font_list();

			$selected_font_info = isset( $font_list[ $options['selected_font_slug'] ] ) ? $font_list[ $options['selected_font_slug'] ] : array();

			if ( ! empty( $selected_font_info['cdn_url'] ) ) {
				$mce_css .= ', ' . $selected_font_info['cdn_url'];
			}

			$css = Font_Setting::locate_css();
			if ( $css ) {
				$mce_css .= ', ' . $css['url'] . '?' . (int) $options['update_time'];
			}
		}

		return $mce_css;
	}

	/**
	 * 설정 화면(JS)에 넘겨줄 데이터
	 * 모두 코드에 정의된 값이라 별도의 AJAX 없이 화면에서 바로 미리보기에 사용함
	 */
	private function get_js_data() {

		$fonts = array();
		foreach ( Font_Setting::get_font_list() as $slug => $info ) {
			$fonts[ $slug ] = array(
				'name'  => $info['font_name'],
				'title' => $info['font_text_title'],
				'css'   => isset( $info['cdn_url'] ) ? $info['cdn_url'] : '',
				'face'  => isset( $info['inline_font_face'] ) ? $info['inline_font_face'] : '',
			);
		}

		$families = array();
		foreach ( Font_Setting::get_font_family_list() as $key => $info ) {
			$families[ $key ] = array(
				'name'    => $info['font_name'],
				'generic' => $info['generic_font_family'],
			);
		}

		return array(
			'optionKey'    => PluginOptions::$option_key,
			'fonts'        => $fonts,
			'families'     => $families,
			'tags'         => Font_Setting::get_target_tag_list(),
			'allowedHosts' => array_values( Sanitizer::allowed_font_hosts() ),
			'i18n'         => array(
				'confirmLeave' => __( '저장하지 않은 변경 사항이 있습니다. 이 페이지를 떠나시겠습니까?', PLUGIN_PREFIX ),
				'confirmReset' => __( '모든 설정을 기본값으로 되돌립니다. 계속하시겠습니까?', PLUGIN_PREFIX ),
				'urlNotAllowed' => __( '이 주소는 사용할 수 없습니다. https 로 시작하는 Google Fonts 등의 폰트 CSS 주소를 입력해 주세요.', PLUGIN_PREFIX ),
				'noResults'     => __( '검색 결과가 없습니다.', PLUGIN_PREFIX ),
				'searching'     => __( '검색 중...', PLUGIN_PREFIX ),
			),
		);
	}

	/**
	 * 지금 선택된 한글 폰트의 슬러그 (예전 버전은 폰트 이름만 저장되어 있을 수 있음)
	 */
	private function get_current_slug( $options, $fonts ) {

		$slug = $options['selected_font_slug'];

		if ( '' !== $slug && isset( $fonts[ $slug ] ) ) {
			return $slug;
		}

		if ( '' !== $options['selected_font'] ) {
			foreach ( $fonts as $key => $font ) {
				if ( $font['font_name'] === $options['selected_font'] ) {
					return $key;
				}
			}
		}

		return '';
	}

	/**
	 * 미리보기에 처음부터 적용할 CSS
	 *
	 * 자바스크립트(setting.js)가 만드는 규칙과 같은 내용을 서버에서 미리 만들어 두어,
	 * 화면이 열리는 순간부터 저장된 폰트가 미리보기에 적용되어 있도록 한다.
	 */
	private function build_preview_css( $options, $fonts ) {

		$slug     = $this->get_current_slug( $options, $fonts );
		$families = Font_Setting::get_font_family_list();
		$parts    = array();
		$generic  = 'sans-serif';

		// 영문 폰트
		if ( 'text' === $options['en_font_add_type'] ) {
			$name = Sanitizer::font_name( $options['selected_font_family_name'] );
			if ( '' !== $name ) {
				$parts[] = "'" . $name . "'";
			}
		} elseif ( '' !== $options['selected_font_family'] && isset( $families[ $options['selected_font_family'] ] ) ) {
			$family  = $families[ $options['selected_font_family'] ];
			$parts[] = $family['font_name'];
			$generic = $family['generic_font_family'];
		}

		// 한글 폰트
		if ( '' !== $slug ) {
			$parts[] = '"' . str_replace( array( '"', '<', '>', '\\' ), '', $fonts[ $slug ]['font_name'] ) . '"';
		}

		$parts[] = $generic;
		$stack   = implode( ', ', $parts );

		$css    = '';
		$canvas = '#rf-preview-canvas';
		$number = '/^\d{1,3}(\.\d{1,2})?$/';

		// "사이트 전체(body)" : 본문(p)을 기준으로 제목(h1~h6)의 크기와 줄 간격을 비례해서 정한다
		if ( 'yes' === $options['font_use_body'] ) {

			$color = $options['font_color_body'];
			$size  = preg_match( $number, $options['font_weight_body'] ) ? $options['font_weight_body'] : '';
			$line  = preg_match( $number, $options['font_lineheight_body'] ) ? $options['font_lineheight_body'] : '';

			$decl = 'font-family:' . $stack . ';';
			if ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color ) ) {
				$decl .= 'color:' . $color . ';';
			}
			if ( '' !== $size ) {
				$decl .= 'font-size:' . $size . 'px;';
			}
			if ( '' !== Font_Setting::body_line_height( $size, $line ) ) {
				$decl .= 'line-height:' . Font_Setting::body_line_height( $size, $line ) . ';';
			}
			$css .= $canvas . '{' . $decl . '}';

			$scaled = Font_Setting::body_scale( $size, $line );
			foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ) as $tag ) {
				$decl = 'font-family:' . $stack . ';';
				if ( '' !== $scaled[ $tag ]['size'] ) {
					$decl .= 'font-size:' . $scaled[ $tag ]['size'] . 'px;';
				}
				if ( '' !== $scaled[ $tag ]['line_height'] ) {
					$decl .= 'line-height:' . $scaled[ $tag ]['line_height'] . ';';
				}
				$css .= $canvas . ' ' . $tag . '{' . $decl . '}';
			}

			return $css;
		}

		// 항목별로 따로 설정
		foreach ( Font_Setting::get_target_tag_list() as $tag ) {

			if ( 'body' === $tag || 'yes' !== $options[ 'font_use_' . $tag ] ) {
				continue;
			}

			$decl = 'font-family:' . $stack . ';';

			$color = $options[ 'font_color_' . $tag ];
			if ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color ) ) {
				$decl .= 'color:' . $color . ';';
			}

			$size = $options[ 'font_weight_' . $tag ];
			if ( preg_match( $number, $size ) ) {
				$decl .= 'font-size:' . $size . 'px;';
			}

			$line = $options[ 'font_lineheight_' . $tag ];
			if ( preg_match( $number, $line ) ) {
				$decl .= 'line-height:' . $line . 'px;';
			}

			$css .= $canvas . ' ' . $tag . '{' . $decl . '}';
		}

		return $css;
	}

	public function enqueue() {

		// 워드프레스에 포함된 색상 선택기를 사용
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		// 폰트 검색 상자(select2) : 플러그인에 포함된 파일을 사용 (외부 주소에서 불러오지 않음)
		wp_register_style( 'rocket-font-select2', PLUGIN_URL . 'assets/vendor/select2/select2.min.css', array(), '4.0.13' );
		wp_register_script( 'rocket-font-select2', PLUGIN_URL . 'assets/vendor/select2/select2.min.js', array( 'jquery' ), '4.0.13', true );

		wp_enqueue_style(
			'rocket-font-admin-setting',
			PLUGIN_URL . 'assets/css/admin-setting.css?rf=' . VERSION,
			array( 'wp-color-picker', 'rocket-font-select2' ),
			null
		);

		$options = self::get_current_all_options();
		$fonts   = Font_Setting::get_font_list();
		$slug    = $this->get_current_slug( $options, $fonts );

		// 저장된 한글 폰트를 화면이 열릴 때부터 미리 불러온다 (자바스크립트가 실행되기 전에도 미리보기에 적용되도록)
		if ( '' !== $slug ) {

			$font = $fonts[ $slug ];

			if ( ! empty( $font['cdn_url'] ) ) {
				$url = ( 0 === strpos( $font['cdn_url'], '//' ) ) ? 'https:' . $font['cdn_url'] : $font['cdn_url'];
				wp_enqueue_style( 'rocket-font-preview-font', $url, array(), null );
			}

			if ( ! empty( $font['inline_font_face'] ) ) {
				wp_add_inline_style( 'rocket-font-admin-setting', $font['inline_font_face'] );
			}
		}

		// 영문 폰트를 직접 입력한 경우, 허용된 주소만 미리 불러온다
		if ( 'text' === $options['en_font_add_type'] && '' !== $options['selected_font_family_url'] ) {
			$en_url = Sanitizer::font_url( $options['selected_font_family_url'] );
			if ( '' !== $en_url ) {
				wp_enqueue_style( 'rocket-font-preview-en-font', $en_url, array(), null );
			}
		}

		wp_add_inline_style( 'rocket-font-admin-setting', $this->build_preview_css( $options, $fonts ) );

		wp_register_script(
			'rocket-font-admin-setting',
			PLUGIN_URL . 'assets/js/setting.js?rf=' . VERSION,
			array( 'jquery', 'wp-color-picker', 'rocket-font-select2' ),
			null,
			true
		);
		wp_localize_script( 'rocket-font-admin-setting', 'rocketFontAdmin', $this->get_js_data() );
		wp_enqueue_script( 'rocket-font-admin-setting' );
	}
}
add_action( 'rocket_font_init', array( 'rocket_font\Setting', 'init' ), 1, 1 );
