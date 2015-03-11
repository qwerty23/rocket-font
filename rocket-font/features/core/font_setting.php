<?php
namespace rocket_font;

class Font_Setting {
	private static $font_list = array(
							  "hanna"				=>array("font_name"	=>"Hanna",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/hanna.css",
															"font_text_title"=>"한나"),
							  "jejugothic"			=>array("font_name"	=>"Jeju Gothic",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/jejugothic.css",
															"font_text_title"=>"제주 고딕"),
							  "jejuhallasan"		=>array("font_name"	=>"Jeju Hallasan",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/jejuhallasan.css",
															"font_text_title"=>"제주 한라산"),
							  "jejumyeongjo"		=>array("font_name"	=>"Jeju Myeongjo",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/jejumyeongjo.css",
															"font_text_title"=>"제주 명조"),
							  "kopubbatang"			=>array("font_name"	=>"KoPub Batang",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/kopubbatang.css",
															"font_text_title"=>"KoPub 바탕"),
							  "kopubdotum"			=>array("font_name"	=>"KoPub Batang",
							  								"cdn_url"	=>"//cdn.jsdelivr.net/font-kopub/1.0/kopubdotum.css",
															"font_text_title"=>"KoPub 돋음"),
							  "nanumgothic"			=>array("font_name"	=>"Nanum Gothic",
							  								//"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanumgothic/nanumgothic.css",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumgothic.css",
															"font_text_title"=>"나눔 고딕"),
							  "nanumbarungothic"	=>array("font_name"	=>"Nanum Barun Gothic",
							  								"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanumbarungothic/nanumbarungothic.css",
															"font_text_title"=>"나눔 바른 고딕"),
							  "nanumbrushscript"	=>array("font_name"	=>"Nanum Brush Script",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumbrushscript.css",
							  								//fonts.googleapis.com/earlyaccess/nanumbrushscript.css
															"font_text_title"=>"나눔 붓글씨"),
							  "nanumgothiccoding"	=>array("font_name"	=>"Nanum Gothic Coding",
							  								//"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanumgothiccoding/nanumgothiccoding.css",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumgothiccoding.css",
															"font_text_title"=>"나눔 고딕 코딩"),
							  "nanummyeongjo"		=>array("font_name"	=>"Nanum Myeongjo",
							  								//"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanummyeongjo/nanummyeongjo.css",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanummyeongjo.css",
															"font_text_title"=>"나눔 명조"),
							  "nanumpenscript"		=>array("font_name"	=>"Nanum Pen Script",
							  								//"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanumpenscript/nanumpenscript.css",
							  								"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumpenscript.css",
															"font_text_title"=>"나눔 펜글씨"),
							  );

	private static $target_tag = array("body","h1","h2","h3","h4","h5","h6","p");
	
	private static $font_family_list = array(
            "Arial"					=> array("font_name"			=>"Arial, Helvetica",
											 "generic_font_family" 	=>"sans-serif"),
            "Arial_Black"			=> array("font_name"			=>"'Arial Black', Gadget",
											 "generic_font_family" 	=>"sans-serif"),
            "Bookman_Old_Style"     => array("font_name"			=>"'Bookman Old Style'",
											 "generic_font_family" 	=>"serif"),
            "Comic_Sans_MS"         => array("font_name"			=>"'Comic Sans MS'",
											 "generic_font_family" 	=>"cursive"),
            "Courier"               => array("font_name"			=>"Courier",
											 "generic_font_family" 	=>"monospace"),
            "Garamond"              => array("font_name"			=>"Garamond",
											 "generic_font_family" 	=>"serif"),
            "Georgia"               => array("font_name"			=>"Georgia",
											 "generic_font_family" 	=>"serif"),
            "Impact"                => array("font_name"			=>"Impact, Charcoal",
											 "generic_font_family" 	=>"sans-serif"),
            "Lucida_Console"        => array("font_name"			=>"'Lucida Console', Monaco",
											 "generic_font_family" 	=>"monospace"),
            "Lucida_Sans_Unicode"   => array("font_name"			=>"'Lucida Sans Unicode', 'Lucida Grande'",
											 "generic_font_family" 	=>"sans-serif"),
            "MS_Sans_Serif"         => array("font_name"			=>"'MS Sans Serif', Geneva",
											 "generic_font_family" 	=> "sans-serif"),
            "MS_Serif"              => array("font_name"			=>"'MS Serif', 'New York'",
											 "generic_font_family" 	=> "sans-serif"),
            "Palatino_Linotype"  	=> array("font_name"			=>"'Palatino Linotype', 'Book Antiqua', Palatino",
											 "generic_font_family" 	=> "serif"),
            "Tahoma"                => array("font_name"			=>"Tahoma, Geneva, sans-serif",
											 "generic_font_family" 	=>"sans-serif"),
            "Times_New_Roman"       => array("font_name"			=>"'Times New Roman', Times",
											 "generic_font_family" 	=> "serif"),
            "Trebuchet_MS"          => array("font_name"			=>"'Trebuchet MS', Helvetica",
											 "generic_font_family" 	=> "sans-serif"),
            "Verdana"               => array("font_name"			=>"Verdana, Geneva",
											 "generic_font_family" 	=> "sans-serif"),
	);
	
	public static function get_font_list(){
		return self::$font_list;
	}
	
	public static function get_target_tag_list(){
		return self::$target_tag;
	}
	
	public static function get_font_family_list(){
		return self::$font_family_list;
	}
}
//add_action( 'rocket_font_init' , array( 'rocket_font\Font_Setting' , 'init'  )  , 1 ,  1 );
?>
