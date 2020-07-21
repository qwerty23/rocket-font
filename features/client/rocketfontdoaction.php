<?php
namespace rocket_font;
class RocketFontDoAction extends Feature {
	
	public $selected_font_info;
	
	public function __construct() {

		$this->add_action( 'wp_enqueue_scripts', 'set_rocket_font', 999);

	}
	
	function set_rocket_font(){
		
		$options = self::get_current_all_options();

		if($options['selected_font'] && is_file(Font_Setting::get_css_file_name(PLUGIN_DIR) . '.min.css')):
			
			$font_list = Font_Setting::get_font_list();
			$this->selected_font_info = $font_list[$options['selected_font_slug']];
			
			// font async
			if($options['use_async']=="yes"):
				$this->add_action('wp_head','rocket_font_async', 50);
			else:
				wp_enqueue_style( 'rocket-font', $this->selected_font_info['cdn_url'], false );
			endif;
			
			$css_save_path = Font_Setting::get_css_file_name(PLUGIN_URL);
			wp_enqueue_style( 'rocket-font-css',  $css_save_path . '.min.css', false, $options['update_time'] );
			
			if($options['use_jquery']=="yes"):
				
				$this->add_action('wp_head','set_rocket_font_jquery', 50);
				
			endif;
			
		endif;
	}

	public function set_rocket_font_jquery(){
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery("body").css({"font-family":"<?php echo $this->selected_font_info['font_name']?>"});
			});
		</script>
		<?php
	}
	
	//css 를 불러오기 전에 아래 스크립트를 실행해야 폰트가 바뀌는 깜박거리는 현상이 발생하지 않음
	public function rocket_font_async(){
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
					families: ['<?php echo $this->selected_font_info['font_name']?>'],
					urls: ['<?php echo $this->selected_font_info['cdn_url']?>']
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

	public function enqueue() {
		/*
		wp_enqueue_script(
			'rocket-font-async',
			PLUGIN_URL . 'assets/js/font-async.js',
			array( 'jquery' ),
			time(),
			true
		);
		*/
	}
}

add_action( 'rocket_font_init' , array( 'rocket_font\RocketFontDoAction' , 'init'  )  , 1 ,  1 );
?>