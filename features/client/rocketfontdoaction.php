<?php
namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}
class RocketFontDoAction extends Feature {

	public $selected_font_info = array();

	public function __construct() {

		$this->add_action( 'wp_enqueue_scripts', 'set_rocket_font', 999);
		//$this->add_filter('get_the_excerpt', 'utf8_excerpt', 9);

	}

	function set_rocket_font(){

		$options = self::get_current_all_options();
		$css = Font_Setting::locate_css();

		if($options['selected_font'] && $css):

			$font_list = Font_Setting::get_font_list();
			$this->selected_font_info = isset($font_list[$options['selected_font_slug']]) ? $font_list[$options['selected_font_slug']] : array();

			// font async
			if($options['use_async']=="yes"):
				$this->add_action('wp_head','rocket_font_async', 50);
			else:
				if(!empty($this->selected_font_info['cdn_url'])){
					wp_enqueue_style( 'rocket-font', $this->selected_font_info['cdn_url'], array(), null );
				}
			endif;

			if($options['en_font_add_type'] != "select"){

				// 직접 입력한 영문 폰트 URL 은 허용된 호스트(https)만 사용 (저장된 예전 값도 한번 더 검증)
				$family_url = Sanitizer::font_url($options['selected_font_family_url']);
				if(!empty($family_url)){

					wp_enqueue_style( 'rocket-backup', $family_url, array(), null, 'all' );
				}

			}

			// ?ver= 를 지우는 테마/플러그인이 있어도 캐시가 갱신되도록 다른 이름(rf)으로 저장 시각을 붙인다
			wp_enqueue_style( 'rocket-font-css', add_query_arg( 'rf', (int) $options['update_time'], $css['url'] ), array(), null );

			if($options['use_jquery']=="yes"):

				$this->add_action('wp_head','set_rocket_font_jquery', 50);

			endif;

		endif;
	}

	public function set_rocket_font_jquery(){

		if(empty($this->selected_font_info['font_name'])){
			return;
		}
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery("body").css({"font-family": <?php echo wp_json_encode($this->selected_font_info['font_name']); ?>});
			});
		</script>
		<?php
	}

	//css 를 불러오기 전에 아래 스크립트를 실행해야 폰트가 바뀌는 깜박거리는 현상이 발생하지 않음
	public function rocket_font_async(){

		if(empty($this->selected_font_info['font_name'])){
			return;
		}

		$font_name = $this->selected_font_info['font_name'];
		$cdn_url = isset($this->selected_font_info['cdn_url']) ? $this->selected_font_info['cdn_url'] : '';
		?>
		<script>
			function preconnectTo(url) {
				var hint = document.createElement("link");
				hint.rel = "preconnect";
				//hint.href = url;
				hint.href = "https://fonts.gstatic.com";

				document.head.appendChild(hint);
			}

			WebFontConfig = {
				custom: {
					families: [<?php echo wp_json_encode($font_name); ?>],
					urls: [<?php echo wp_json_encode($cdn_url); ?>]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			};

			(function() {
				//preconnectTo("https://fonts.gstatic.com");

				var wf = document.createElement('script');
				var s = document.getElementsByTagName('script')[0];

				//wf.src = 'https://cdn.jsdelivr.net/webfontloader/1.6.24/webfontloader.js';
				wf.src = 'https://cdn.jsdelivr.net/webfontloader/1.6.26/webfontloader.js';
				wf.type = 'text/javascript';
				//wf.async = 'true';

				s.parentNode.insertBefore(wf, s);
			})();
		</script>
		<?php
	}
}

add_action( 'rocket_font_init' , array( 'rocket_font\RocketFontDoAction' , 'init'  )  , 1 ,  1 );
?>
