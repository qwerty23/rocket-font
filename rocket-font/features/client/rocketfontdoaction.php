<?php
namespace rocket_font;
class RocketFontDoAction extends Feature {
	
	public $selected_font_info;
	
    public function __construct() {
    	
		$this->add_action( 'wp_enqueue_scripts', 'set_rocket_font', 50);
		
    }
	
	function set_rocket_font(){
		
		$options = self::get_current_all_options();

		if($options['selected_font']):
			
			$font_list = Font_Setting::get_font_list();
			$this->selected_font_info = $font_list[$options['selected_font_slug']];
			wp_enqueue_style( 'rocket-font', $this->selected_font_info['cdn_url'], false );
			wp_enqueue_style( 'rocket-font-css', PLUGIN_URL . 'assets/css/tinymce_rocketfont_dynamic.min.css', false, $options['update_time'] );
			
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

	public function enqueue() {
		
	}
}
add_action( 'rocket_font_init' , array( 'rocket_font\RocketFontDoAction' , 'init'  )  , 1 ,  1 );
?>