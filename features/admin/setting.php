<?php

namespace rocket_font;

class Setting extends Feature {

	public function __construct() {

		$this->add_action( 'admin_menu', 'rocket_font_menu' );

		if(isset($_GET['page']) && in_array($_GET['page'],array(PLUGIN_MENU_SLUG))):
			//업데이트 된 값이 있으면 반영
			$this->add_action( 'admin_enqueue_scripts', 'enqueue' );
			$this->add_action( 'admin_enqueue_scripts', 'load_korean_font' );

			if(!empty($_POST['action']) && $_POST['action']=="update"): 
				
				self::detect_option_change();
				self::set_option("update_time",time());
				$this->write_css();

			endif;

		endif;

		$this->add_filter( 'mce_css', 'add_tiny_mce_css' );
	}
	
	public function add_font_family_tinymce($initArray){
		
		foreach(Font_Setting::get_font_list() as $font_css_import_name => $font_info):
			$initArray['font_formats'] .= $font_info["font_text_title"] . "=" . $font_info["font_name"] . ";";
		endforeach;
		
		return $initArray;
	}
	
	public function load_korean_font(){
		
		foreach(Font_Setting::get_font_list() as $font_css_import_name => $font_info):
			wp_enqueue_style(
				'rocket-font-'.$font_css_import_name,
				$font_info['cdn_url'],
				VERSION,
				true
			);
		endforeach;
	}

	//관리 메뉴 추가
	public function rocket_font_menu(){

		add_options_page('Rocket Font', '로켓 폰트', 'manage_options', PLUGIN_MENU_SLUG, array( &$this, 'setting_page' ));

		$enqueue_pointer_script_style = false;
		$dismissed_pointers_values = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );

		if(is_array($dismissed_pointers_values)){
			$dismissed_pointers = $dismissed_pointers_values;
		}else{
			$dismissed_pointers = explode( ',', $dismissed_pointers_values );
		}
		
		if( !in_array( 'rocketfont_settings_pointer', $dismissed_pointers ) ) {
			$enqueue_pointer_script_style = true;
			$this->add_action("admin_print_footer_scripts","show_post_pointer");
		}
		
		if( $enqueue_pointer_script_style ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
		}
	}
	
	function show_post_pointer(){
		
		$wp_pointer_content = __('post pointer message', PLUGIN_PREFIX);
		$wp_pointer_image_content = __('post image pointer message', PLUGIN_PREFIX);
		?>
		<script>
		jQuery(document).ready( function($) {
			
			if($("#menu-settings .menu-icon-settings").length > 0){
				
				var options = {"content":"<h3>"+'Rokcet Font'+"<\/h3>"+'<p>플러그인이 활성화 되었습니다.</p><p>[설정] > [로켓 폰트] 메뉴에서 폰트 설정을 해 주세요.</p>',"position":{"edge":"left","align":"center"}};
				if ( ! options ) return;
				
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
	public function setting_page(){
		
		$template = self::get_template();
		$options = self::get_current_all_options();
		$backup_font_list = Font_Setting::get_font_family_list();
		
		$template->set('plugin_url',PLUGIN_URL);
		$template->set('version',VERSION);
		$template->set('options',$options);
		$template->set('font_list',Font_Setting::get_font_list());
		$template->set('target_tag',Font_Setting::get_target_tag_list());
		$template->set('font_family_list',$backup_font_list);
		
		$selected_backup_font_info["font_name"] = "";
		$selected_backup_font_info["generic_font_family"] = "";
		
		if(!empty($options['selected_font_family'])):
			$selected_backup_font_info = $backup_font_list[$options['selected_font_family']];
		endif;
		
		$template->set('selected_backup_font_info',$selected_backup_font_info);
		echo $template->apply("admin/setting.php");

	}
	
	//폰트 이름중 스페이스가 있을경우 쌍따옴표로 묶음
	function detect_space($font_name){
		
		$double_qute = '"';
		
		if (preg_match('/^\S.*\s.*\S$/', $font_name)){
			$font_name = $double_qute . $font_name . $double_qute;
		}
		
		return $font_name;
	}
	
	/* 백업 (영문) 폰트를 선택했을경우
	 * 백업, 한글, 제너릭
	 * 의 순서대로 문자열을 반환
	 */
	function get_font_family_string($selected_backup_font_info, $selected_font_info){
		
		if(!empty($selected_backup_font_info['font_name'])){
			$selected_backup_font_info['font_name'] = $selected_backup_font_info['font_name'] . ", ";
			$selected_backup_font_info['generic_font_family'] = ", " . $selected_backup_font_info['generic_font_family'];
		}
		
		return $selected_backup_font_info['font_name'] . $this->detect_space($selected_font_info['font_name']) . $selected_backup_font_info['generic_font_family'];
	}
	
	/* 
	 * 저장 버튼을 눌렀을 경우
	 * 1. css 파일 생성
	 * 2. 1의 css 로 용량 압축버전 min.css 를 생성
	 */
	function write_css(){
		
		$css_save_path = Font_Setting::get_css_file_name(PLUGIN_DIR);
		
		if(is_file($css_save_path . '.css'))
			unlink($css_save_path . '.css');
		
		if(is_file($css_save_path . '.min.css'))
			unlink($css_save_path . '.min.css');
		
		$options = self::get_current_all_options();
		$font_list = Font_Setting::get_font_list();
		$backup_font_list = Font_Setting::get_font_family_list();
		$target_tag_list = Font_Setting::get_target_tag_list();
		$selected_font_info = $font_list[$options['selected_font_slug']];
		
		$selected_backup_font_info['font_name'] = "";
		$selected_backup_font_info['generic_font_family'] = "";
		
		if(!empty($options['selected_font_family'])):
			
			$selected_backup_font_info = $backup_font_list[$options['selected_font_family']];
			
		endif;
		
		ob_start();
		include PLUGIN_DIR . 'assets/templates/admin/rocketfont-dynamic-css.php';
		$rocketfont_css_file = ob_get_clean();
		
		$a = fopen($css_save_path . '.css', 'w');
		fwrite($a, $rocketfont_css_file);
		fclose($a);
		@chmod($css_save_path . '.css', 0644);
		
		$rocketfont_css_file_minify = $this->compress($rocketfont_css_file);
		$b = fopen($css_save_path . '.min.css', 'w');
		fwrite($b, $rocketfont_css_file_minify);
		fclose($b);
		@chmod($css_save_path . '.min.css', 0644);
	}
	
	function compress($rocketfont_css_file){
		
		$rocketfont_css_file = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $rocketfont_css_file);
		$rocketfont_css_file = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $rocketfont_css_file );
		return $rocketfont_css_file;
		
	}
	
	/*
	 * 옵션에서 에디터에서 폰트 사용을 선택했을경우
	 * tinymce 에 폰트와 생성된 css 파일을 추가
	 */
	function add_tiny_mce_css( $mce_css ) {
		
		if ( ! empty( $mce_css ) )
			$mce_css .= ',';
		
		$options = self::get_current_all_options();
		
		if($options['use_tinymce_editor'] == "yes" && $options['selected_font']):
			
			$font_list = Font_Setting::get_font_list();
			$target_tag_list = Font_Setting::get_target_tag_list();

			$selected_font_info = $font_list[$options['selected_font_slug']];
			$mce_css .= ', ' . $selected_font_info['cdn_url'];

			$css_save_path = Font_Setting::get_css_file_name(PLUGIN_URL);
			$mce_css .= ', ' . $css_save_path . '.min.css?'.$options['update_time'];

		endif;
		
		return $mce_css;
	}

	public function enqueue() {
		
		wp_enqueue_script("jquery-ui-sortable");
		
		wp_enqueue_style(
	        'fontawesome',
	        '//cdn.jsdelivr.net/fontawesome/4.4.0/css/font-awesome.min.css'
	    );
		
		wp_enqueue_style(
	        'select2',
	        '//cdn.jsdelivr.net/select2/3.5.2/select2.css'
	    );
		
		wp_enqueue_style(
	        'minicolors',
	        '//cdn.jsdelivr.net/jquery.minicolors/2.1.2/jquery.minicolors.css'
	    );
		
		wp_enqueue_style(
	        'jsdelivr-group',
	        '//cdn.jsdelivr.net/g/jquery.powertip@1.2.0(css/jquery.powertip.min.css),jquery.nouislider@7.0.10(jquery.nouislider.min.css+jquery.nouislider.pips.min.css),jquery.switchery@0.8.1(switchery.min.css)'
	    );
		
		wp_enqueue_script(
	        'jsdelivr-group',
	        '//cdn.jsdelivr.net/g/select2@3.5.2(select2.min.js),jquery.minicolors@2.1.2(jquery.minicolors.min.js),jquery.powertip@1.2.0(jquery.powertip.min.js),jquery.nouislider@7.0.10(jquery.nouislider.all.min.js),jquery.switchery@0.8.1(switchery.min.js)',
	        array( 'jquery' ),
	        VERSION,
	        true
	    );
		
		wp_enqueue_style(
	        'pwstabs',
	        PLUGIN_URL . 'assets/js/pwstabs/jquery.pwstabs-1.2.1.css'
	    );

		wp_enqueue_script(
	        'pwstabs',
	        PLUGIN_URL . 'assets/js/pwstabs/jquery.pwstabs-1.2.1.min.js',
	        array( 'jquery' ),
	        VERSION,
	        true
	    );
		
		wp_enqueue_style(
	        'tag-editor',
	        PLUGIN_URL . 'assets/js/tag-editor/jquery.tag-editor.css'
	    );

		wp_enqueue_script(
	        'tag-editor-caret',
	        PLUGIN_URL . 'assets/js/tag-editor/jquery.caret.min.js',
	        array( 'jquery' ),
	        VERSION,
	        true
	    );
		
		wp_enqueue_script(
	        'tag-editor',
	        PLUGIN_URL . 'assets/js/tag-editor/jquery.tag-editor.min.js',
	        array( 'jquery' ),
	        VERSION,
	        true
	    );
		
		wp_enqueue_script(
	        'rocket-font-admin-setting',
	        PLUGIN_URL . 'assets/js/setting.js',
	        array( 'jquery' ),
	        time(),
	        true
	    );
		
	}
}
add_action( 'rocket_font_init' , array( 'rocket_font\Setting' , 'init'  )  , 1 ,  1 );
?>