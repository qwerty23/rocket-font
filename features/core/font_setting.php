<?php
namespace rocket_font;

class Font_Setting {
	public static $css_file_name = "_rocketfont";
	private static $font_list = array(
							"hanna"						=>array("font_name"	=>"Hanna",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/hanna.css",
																"font_text_title"=>"한나"),
							"jejugothic"				=>array("font_name"	=>"Jeju Gothic",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/jejugothic.css",
																"font_text_title"=>"제주 고딕"),
							"jejuhallasan"				=>array("font_name"	=>"Jeju Hallasan",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/jejuhallasan.css",
																"font_text_title"=>"제주 한라산"),
							"jejumyeongjo"				=>array("font_name"	=>"Jeju Myeongjo",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/jejumyeongjo.css",
																"font_text_title"=>"제주 명조"),
							"kopubbatang"				=>array("font_name"	=>"KoPub Batang",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/kopubbatang.css",
																"font_text_title"=>"KoPub 바탕"),
							"nanumgothic"				=>array("font_name"	=>"Nanum Gothic",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumgothic.css",
																"font_text_title"=>"나눔 고딕"),
							"nanumbarungothiclight"		=>array("font_name"	=>"NanumBarunGothicLight",
																"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanumbarungothic/nanumbarungothiclight.css",
																"font_text_title"=>"나눔 바른 고딕 light"),
							"nanumbarungothicultralight"=>array("font_name"	=>"NanumBarunGothicUltraLight",
																"cdn_url"	=>"//cdn.jsdelivr.net/font-nanum/1.0/nanumbarungothic/nanumbarungothicultralight.css",
																"font_text_title"=>"나눔 바른 고딕 ultra light"),
							"nanumbrushscript"			=>array("font_name"	=>"Nanum Brush Script",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumbrushscript.css",
																"font_text_title"=>"나눔 붓글씨"),
							"nanumgothiccoding"			=>array("font_name"	=>"Nanum Gothic Coding",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumgothiccoding.css",
																"font_text_title"=>"나눔 고딕 코딩"),
							"nanummyeongjo"				=>array("font_name"	=>"Nanum Myeongjo",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanummyeongjo.css",
																"font_text_title"=>"나눔 명조"),
							"nanumpenscript"			=>array("font_name"	=>"Nanum Pen Script",
																"cdn_url"	=>"//fonts.googleapis.com/earlyaccess/nanumpenscript.css",
																"font_text_title"=>"나눔 펜글씨"),
							"nanumsquare"				=>array("font_name"	=>"Nanum Square",
																"cdn_url"	=>"https://cdn.jsdelivr.net/font-nanumsquare/1.0.0/nanumsquare.css",
																"font_text_title"=>"나눔 스퀘어 (웹용 저용량 버전)"),
																
							"nanumbarungothic"			=>array("font_name"	=>"Nanum Barun Gothic",
																"cdn_url"	=>"https://cdn.jsdelivr.net/font-nanumlight/1.0/nanumbarungothicweb.css",
																"font_text_title"=>"나눔 바른 고딕 웹 (웹에서 사용하는 저용량 버전)"),
																
							"notosanskr"				=>array("font_name"	=>"NotoSansKR",
																"cdn_url"	=>"//cdn.jsdelivr.net/font-notosans-kr/1.0.0-v1004/NotoSansKR-2350.css",
																"font_text_title"=>"노토 산스"),
							"blackhansans"				=>array("font_name"	=>"Black Han Sans",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Black+Han+Sans:400",
																"font_text_title"=>"검은고딕"),
							"gothica1"					=>array("font_name"	=>"Gothic A1",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Gothic+A1:400",
																"font_text_title"=>"고딕 A1"),
							"songmyung"					=>array("font_name"	=>"Song Myung",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Song+Myung:400",
																"font_text_title"=>"송명"),
							"gamjaflower"				=>array("font_name"	=>"Gamja Flower",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Gamja+Flower:400",
																"font_text_title"=>"감자꽃마을"),
							"gaegu"						=>array("font_name"	=>"Gaegu",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Gaegu:300",
																"font_text_title"=>"개구쟁이"),
							"gugi"						=>array("font_name"	=>"Gugi",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Gugi:400",
																"font_text_title"=>"구기"),
							"cutefont"					=>array("font_name"	=>"Cute Font",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Cute+Font:400",
																"font_text_title"=>"귀여운폰트"),
							"kiranghaerang"				=>array("font_name"	=>"Kirang Haerang",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Kirang+Haerang:400",
																"font_text_title"=>"기랑해랑"),
							"eastseadokdo"				=>array("font_name"	=>"East Sea Dokdo",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=East+Sea+Dokdo:400",
																"font_text_title"=>"대한 민국 독도"),
							"dohyeon"					=>array("font_name"	=>"Do Hyeon",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Do+Hyeon:400",
																"font_text_title"=>"도현"),
							"dokdo"						=>array("font_name"	=>"Dokdo",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Dokdo:400",
																"font_text_title"=>"독도"),
							"poorstory"					=>array("font_name"	=>"Poor Story",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Poor+Story:400",
																"font_text_title"=>"서툰 이야기"),
							"stylish"					=>array("font_name"	=>"Stylish",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Stylish:400",
																"font_text_title"=>"스타일리시"),
							"yeonsung"					=>array("font_name"	=>"Yeon Sung",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Yeon+Sung:400",
																"font_text_title"=>"연성"),
							"jua"						=>array("font_name"	=>"jua",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Jua:400",
																"font_text_title"=>"주아"),
							"himelody"					=>array("font_name"	=>"Hi Melody",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Hi+Melody:400",
																"font_text_title"=>"하이멜로디"),
							"sunflower"					=>array("font_name"	=>"Sunflower",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Sunflower:500",
																"font_text_title"=>"해바라기"),
							"blackandwhitepicture"		=>array("font_name"	=>"Black And White Picture",
																"cdn_url"	=>"https://fonts.googleapis.com/css?family=Black+And+White+Picture:400",
																"font_text_title"=>"흑백사진"),
							"spoqahansans"				=>array("font_name"	=>"Spoqa Han Sans",
																"cdn_url"	=>"https://cdn.jsdelivr.net/font-spoqa-han-sans/2.1.0/css/SpoqaHanSans-kr.css",
																"font_text_title"=>"스포카 한 산스 2.1.0"),
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
			"Lato"               	=> array("font_name"			=>"Lato, 'Apple SD Gothic Neo'",
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
	
	public static function get_css_file_name($path){
			
		$current_site_url = str_replace(array("http://","https://"),"",site_url());
		$css_prefix = str_replace(array(".", "/"),"-",$current_site_url);
		
		return $path .'assets/css/' . $css_prefix . self::$css_file_name;
	}
}
?>
