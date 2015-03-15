<?php
//폰트 배열에 굵기까지 추가 
namespace rocket_font;

class Setting extends Feature {

	public function __construct() {
		
    	$this->add_action( 'admin_menu', 'rocket_font_menu' );
		
		if(isset($_GET['page']) && in_array($_GET['page'],array(PLUGIN_MENU_SLUG))):
			//업데이트 된 값이 있으면 반영
			self::detect_option_change();
			$this->add_action( 'admin_enqueue_scripts', 'enqueue' );
			$this->add_action( 'admin_enqueue_scripts', 'load_korean_font' );
			
			if($_POST['action']=="update"):
				self::set_option("update_time",time());
				$this->write_css();
				
			endif;
			
		endif;
		
		$this->add_filter( 'mce_css', 'add_tiny_mce_css' );
    }
	
	public function load_korean_font(){
		
		foreach(Font_Setting::get_font_list() as $font_css_import_name => $font_info):
			wp_enqueue_style(
		        'rocket-font-'.$font_css_import_name,
		        //'//fonts.googleapis.com/earlyaccess/'.$font_css_import_name.'.css',
		        $font_info['cdn_url'],
		        time(),
		        true
		    );
		endforeach;
		
	}
	
	//관리 메뉴 추가
	public function rocket_font_menu(){
		add_options_page('Rocket Font', 'Rocket Font', 'manage_options', PLUGIN_MENU_SLUG, array( &$this, 'setting_page' ));
		
		$enqueue_pointer_script_style = false;
		$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		
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
				var options = {"content":"<h3 class='dnp'>"+'Rokcet Font'+"<\/h3>"+'<p>플러그인이 활성화 되었습니다.</p><p>[설정] > [Rocket Font] 메뉴에서 폰트 설정을 해 주세요.</p>',"position":{"edge":"left","align":"center"}};
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
		$template->set('version',VERSION);
		$template->set('options',$options);
		$template->set('font_list',Font_Setting::get_font_list());
		$template->set('target_tag',Font_Setting::get_target_tag_list());
		$template->set('font_family_list',$backup_font_list);
		
		$selected_backup_font_info = $backup_font_list[$options['selected_font_family']];
		$template->set('selected_backup_font_info',$backup_font_list[$options['selected_font_family']]);
		
		echo $template->apply("admin/setting.php");

	}
	
	function write_css(){
		
		$options = self::get_current_all_options();
		$font_list = Font_Setting::get_font_list();
		$backup_font_list = Font_Setting::get_font_family_list();
		$target_tag_list = Font_Setting::get_target_tag_list();
		$selected_font_info = $font_list[$options['selected_font_slug']];
		$selected_backup_font_info = $backup_font_list[$options['selected_font_family']];
		
		ob_start();
		include PLUGIN_DIR . 'assets/templates/admin/tinymce-rocketfont-dynamic-css.php';
		$result = ob_get_clean();
		
		$a = fopen( PLUGIN_DIR . 'assets/css/tinymce_rocketfont_dynamic.css', 'w');
		fwrite($a, $result);
		fclose($a);
		@chmod(PLUGIN_DIR . 'assets/css/tinymce_rocketfont_dynamic.css', 0644);
		
		$minify = $this->compress($result);
		$b = fopen( PLUGIN_DIR . 'assets/css/tinymce_rocketfont_dynamic.min.css', 'w');
		fwrite($b, $minify);
		fclose($b);
		@chmod(PLUGIN_DIR . 'assets/css/tinymce_rocketfont_dynamic.min.css', 0644);
	}
	
	function compress($minify){
		$minify = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify);
		$minify = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify );
		return $minify;
	}
	
	function add_tiny_mce_css( $mce_css ) {
		
	 	$options = self::get_current_all_options();
		
		if($options['use_tinymce_editor'] == "yes" && $options['selected_font']):
			
			$font_list = Font_Setting::get_font_list();
			$target_tag_list = Font_Setting::get_target_tag_list();
			
			$selected_font_info = $font_list[$options['selected_font_slug']];
			$mce_css .= ', ' . $selected_font_info['cdn_url'];
			
			$mce_css .= ', ' . PLUGIN_URL . 'assets/css/tinymce_rocketfont_dynamic.min.css?'.$options['update_time'];
			
		endif;
		
	    return $mce_css;
	}

	public function enqueue() {
		
		wp_enqueue_script("jquery-ui-sortable");
		
		wp_enqueue_style(
	        'select2',
	        '//cdn.jsdelivr.net/select2/3.5.2/select2.css'
	    );

		wp_enqueue_script(
	        'select2',
	        '//cdn.jsdelivr.net/select2/3.5.2/select2.min.js',
	        array( 'jquery' ),
	        true
	    );
		
		wp_enqueue_style(
	        'nouislider',
	        '//cdnjs.cloudflare.com/ajax/libs/noUiSlider/6.2.0/jquery.nouislider.min.css'
	    );
		
		wp_enqueue_style(
	        'nouislider',
	        //'//cdn.jsdelivr.net/jquery.nouislider/7.0.10/jquery.nouislider.pips.min.css'
	        '//oss.maxcdn.com/jquery.nouislider/7.0.10/jquery.nouislider.pips.min.css'
		);

		wp_enqueue_script(
	        'nouislider',
	        //'//cdn.jsdelivr.net/jquery.nouislider/7.0.10/jquery.nouislider.all.min.js',
	        '//oss.maxcdn.com/jquery.nouislider/7.0.10/jquery.nouislider.all.min.js',
	        array( 'jquery' ),
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
	        true
	    );
		
		wp_enqueue_script(
	        'rocket-font-admin-setting',
	        PLUGIN_URL . 'assets/js/setting.js',
	        array( 'jquery' ),
	        true
	    );
		
		wp_enqueue_style(
	        'font-awesome',
	        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css'
	    );
		
		wp_enqueue_style(
	        'minicolors',
	        //'//cdn.jsdelivr.net/jquery.minicolors/2.1.2/jquery.minicolors.css'
	        '//oss.maxcdn.com/jquery.minicolors/2.1.2/jquery.minicolors.css'
	    );

		wp_enqueue_script(
	        'minicolors',
	        // '//cdn.jsdelivr.net/jquery.minicolors/2.1.2/jquery.minicolors.min.js',
	        '//oss.maxcdn.com/jquery.minicolors/2.1.2/jquery.minicolors.min.js',
	        array( 'jquery' ),
	        true
	    );
		
		wp_enqueue_style(
	        'switchery',
	        PLUGIN_URL .'assets/js/switchery/switchery.min.css'
	    );

		wp_enqueue_script(
	        'switchery',
	        PLUGIN_URL . 'assets/js/switchery/switchery.min.js',
	        array( 'jquery' ),
	        true
	    );
		
	}
}
add_action( 'rocket_font_init' , array( 'rocket_font\Setting' , 'init'  )  , 1 ,  1 );
?>