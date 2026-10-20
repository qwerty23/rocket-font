<?php
namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 사용할 수 있는 폰트 목록과 CSS 파일 위치
 *
 * 폰트 항목 :
 *   font_name        CSS 에서 사용하는 font-family 이름 (제공처 CSS 안의 이름과 같아야 함)
 *   cdn_url          폰트 CSS 주소 (비어 있으면 inline_font_face 사용)
 *   font_text_title  설정 화면에 표시할 이름
 *   group            그룹 (get_font_groups() 의 키)
 *   inline_font_face (선택) @font-face 규칙을 직접 넣을 때
 *
 * 주의 : 저장된 설정은 슬러그(배열의 키)로 폰트를 찾으므로 기존 슬러그는 바꾸거나 지우지 말 것.
 * 목록에 폰트를 추가할 때는 폰트 라이선스(웹 폰트 사용 가능 여부)와 CDN 주소, CSS 안의 font-family 이름을 확인한다.
 */
class Font_Setting {
	public static $css_file_name = "_rocketfont";

	/**
	 * 설정 화면의 폰트 그룹 (표시 순서)
	 */
	private static $font_groups = array(
		'gothic'      => '고딕 (본문용으로 추천)',
		'serif'       => '바탕 / 명조',
		'handwriting' => '손글씨',
		'display'     => '제목 / 장식용',
		'coding'      => '코딩용 (고정폭)',
	);

	private static $font_list = array(

		/* ---------- 고딕 ---------- */
		"pretendard"        => array("font_name" => "Pretendard",
									 "cdn_url" => "https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard-dynamic-subset.css",
									 "font_text_title" => "프리텐다드 (Pretendard)",
									 "group" => "gothic"),
		"suit"              => array("font_name" => "SUIT",
									 "cdn_url" => "https://cdn.jsdelivr.net/gh/sun-typeface/SUIT@2/fonts/static/woff2/SUIT.css",
									 "font_text_title" => "SUIT",
									 "group" => "gothic"),
		"wantedsans"        => array("font_name" => "Wanted Sans",
									 "cdn_url" => "https://cdn.jsdelivr.net/gh/wanteddev/wanted-sans@v1.0.3/packages/wanted-sans/fonts/webfonts/static/split/WantedSans.min.css",
									 "font_text_title" => "원티드 산스 (Wanted Sans)",
									 "group" => "gothic"),
		"notosanskr"        => array("font_name" => "Noto Sans KR",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap",
									 "font_text_title" => "노토 산스 (Noto Sans KR)",
									 "group" => "gothic"),
		"ibmplexsanskr"     => array("font_name" => "IBM Plex Sans KR",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+KR:wght@400;700&display=swap",
									 "font_text_title" => "IBM Plex Sans KR",
									 "group" => "gothic"),
		"nanumgothic"       => array("font_name" => "Nanum Gothic",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@400;700&display=swap",
									 "font_text_title" => "나눔 고딕",
									 "group" => "gothic"),
		"gowundodum"        => array("font_name" => "Gowun Dodum",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gowun+Dodum&display=swap",
									 "font_text_title" => "고운 돋움",
									 "group" => "gothic"),
		"gothica1"          => array("font_name" => "Gothic A1",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gothic+A1:wght@400;700&display=swap",
									 "font_text_title" => "고딕 A1 (Gothic A1)",
									 "group" => "gothic"),
		"nanumsquare"       => array("font_name" => "Nanum Square",
									 "cdn_url" => "https://cdn.jsdelivr.net/font-nanumsquare/1.0.0/nanumsquare.css",
									 "font_text_title" => "나눔 스퀘어 (웹용 저용량 버전)",
									 "group" => "gothic"),
		"nanumbarungothic"  => array("font_name" => "NanumBarunGothic",
									 "cdn_url" => "https://cdn.jsdelivr.net/font-nanumlight/1.0/nanumbarungothicweb.css",
									 "font_text_title" => "나눔 바른 고딕 (웹용 저용량 버전)",
									 "group" => "gothic"),
		"nanumbarungothiclight" => array("font_name" => "NanumBarunGothicLight",
									 "cdn_url" => "https://cdn.jsdelivr.net/font-nanum/1.0/nanumbarungothic/nanumbarungothiclight.css",
									 "font_text_title" => "나눔 바른 고딕 Light",
									 "group" => "gothic"),
		"nanumbarungothicultralight" => array("font_name" => "NanumBarunGothicUltraLight",
									 "cdn_url" => "https://cdn.jsdelivr.net/font-nanum/1.0/nanumbarungothic/nanumbarungothicultralight.css",
									 "font_text_title" => "나눔 바른 고딕 Ultra Light",
									 "group" => "gothic"),

		/* ---------- 바탕 / 명조 ---------- */
		"notoserifkr"       => array("font_name" => "Noto Serif KR",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Noto+Serif+KR:wght@400;700&display=swap",
									 "font_text_title" => "노토 세리프 (Noto Serif KR)",
									 "group" => "serif"),
		"nanummyeongjo"     => array("font_name" => "Nanum Myeongjo",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Nanum+Myeongjo:wght@400;700&display=swap",
									 "font_text_title" => "나눔 명조",
									 "group" => "serif"),
		"gowunbatang"       => array("font_name" => "Gowun Batang",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gowun+Batang:wght@400;700&display=swap",
									 "font_text_title" => "고운 바탕",
									 "group" => "serif"),
		"hahmlet"           => array("font_name" => "Hahmlet",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Hahmlet:wght@400;700&display=swap",
									 "font_text_title" => "함렛 (Hahmlet)",
									 "group" => "serif"),
		"songmyung"         => array("font_name" => "Song Myung",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Song+Myung&display=swap",
									 "font_text_title" => "송명 (Song Myung)",
									 "group" => "serif"),

		/* ---------- 손글씨 ---------- */
		"nanumpenscript"    => array("font_name" => "Nanum Pen Script",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&display=swap",
									 "font_text_title" => "나눔 펜글씨",
									 "group" => "handwriting"),
		"nanumbrushscript"  => array("font_name" => "Nanum Brush Script",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Nanum+Brush+Script&display=swap",
									 "font_text_title" => "나눔 붓글씨",
									 "group" => "handwriting"),
		"gaegu"             => array("font_name" => "Gaegu",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gaegu:wght@400;700&display=swap",
									 "font_text_title" => "개구 (Gaegu)",
									 "group" => "handwriting"),
		"himelody"          => array("font_name" => "Hi Melody",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Hi+Melody&display=swap",
									 "font_text_title" => "하이멜로디 (Hi Melody)",
									 "group" => "handwriting"),
		"gamjaflower"       => array("font_name" => "Gamja Flower",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gamja+Flower&display=swap",
									 "font_text_title" => "감자꽃 (Gamja Flower)",
									 "group" => "handwriting"),
		"poorstory"         => array("font_name" => "Poor Story",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Poor+Story&display=swap",
									 "font_text_title" => "푸어 스토리 (Poor Story)",
									 "group" => "handwriting"),
		"eastseadokdo"      => array("font_name" => "East Sea Dokdo",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=East+Sea+Dokdo&display=swap",
									 "font_text_title" => "동해 독도 (East Sea Dokdo)",
									 "group" => "handwriting"),
		"singleday"         => array("font_name" => "Single Day",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Single+Day&display=swap",
									 "font_text_title" => "싱글 데이 (Single Day)",
									 "group" => "handwriting"),
		"kiranghaerang"     => array("font_name" => "Kirang Haerang",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Kirang+Haerang&display=swap",
									 "font_text_title" => "기랑 해랑 (Kirang Haerang)",
									 "group" => "handwriting"),
		"blackandwhitepicture" => array("font_name" => "Black And White Picture",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Black+And+White+Picture&display=swap",
									 "font_text_title" => "흑백사진 (Black And White Picture)",
									 "group" => "handwriting"),

		/* ---------- 제목 / 장식 ---------- */
		"blackhansans"      => array("font_name" => "Black Han Sans",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Black+Han+Sans&display=swap",
									 "font_text_title" => "검은고딕 (Black Han Sans)",
									 "group" => "display"),
		"dohyeon"           => array("font_name" => "Do Hyeon",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap",
									 "font_text_title" => "도현 (Do Hyeon)",
									 "group" => "display"),
		"jua"               => array("font_name" => "Jua",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Jua&display=swap",
									 "font_text_title" => "주아 (Jua)",
									 "group" => "display"),
		"bagelfatone"       => array("font_name" => "Bagel Fat One",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Bagel+Fat+One&display=swap",
									 "font_text_title" => "베이글 팻 원 (Bagel Fat One)",
									 "group" => "display"),
		"gasoekone"         => array("font_name" => "Gasoek One",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gasoek+One&display=swap",
									 "font_text_title" => "가석 원 (Gasoek One)",
									 "group" => "display"),
		"dongle"            => array("font_name" => "Dongle",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Dongle:wght@400;700&display=swap",
									 "font_text_title" => "동글 (Dongle)",
									 "group" => "display"),
		"sunflower"         => array("font_name" => "Sunflower",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Sunflower:wght@300;500;700&display=swap",
									 "font_text_title" => "해바라기 (Sunflower)",
									 "group" => "display"),
		"orbit"             => array("font_name" => "Orbit",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Orbit&display=swap",
									 "font_text_title" => "오르빗 (Orbit)",
									 "group" => "display"),
		"gugi"              => array("font_name" => "Gugi",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Gugi&display=swap",
									 "font_text_title" => "구기 (Gugi)",
									 "group" => "display"),
		"stylish"           => array("font_name" => "Stylish",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Stylish&display=swap",
									 "font_text_title" => "스타일리시 (Stylish)",
									 "group" => "display"),
		"cutefont"          => array("font_name" => "Cute Font",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Cute+Font&display=swap",
									 "font_text_title" => "귀여운 폰트 (Cute Font)",
									 "group" => "display"),
		"yeonsung"          => array("font_name" => "Yeon Sung",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Yeon+Sung&display=swap",
									 "font_text_title" => "연성 (Yeon Sung)",
									 "group" => "display"),
		"dokdo"             => array("font_name" => "Dokdo",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Dokdo&display=swap",
									 "font_text_title" => "독도 (Dokdo)",
									 "group" => "display"),
		"bmeuljiro"         => array("font_name" => "BMEULJIRO",
									 "cdn_url" => "",
									 "font_text_title" => "배민 을지로체",
									 "group" => "display",
									 "inline_font_face" => "@font-face { font-family: 'BMEULJIRO'; src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_twelve@1.0/BMEULJIRO.woff') format('woff'); font-weight: normal; font-style: normal;}"),

		/* ---------- 코딩용 ---------- */
		"nanumgothiccoding" => array("font_name" => "Nanum Gothic Coding",
									 "cdn_url" => "https://fonts.googleapis.com/css2?family=Nanum+Gothic+Coding:wght@400;700&display=swap",
									 "font_text_title" => "나눔 고딕 코딩",
									 "group" => "coding"),

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
		"Tahoma"                => array("font_name"			=>"Tahoma, Geneva",
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

	/**
	 * 그룹별로 묶은 폰트 목록 (설정 화면의 선택 상자용)
	 *
	 * @return array 그룹키 => array('label' => 그룹 이름, 'fonts' => 슬러그 => 폰트 정보)
	 */
	public static function get_grouped_font_list(){

		$grouped = array();
		foreach(self::$font_groups as $group_key => $label){
			$grouped[$group_key] = array('label' => $label, 'fonts' => array());
		}

		foreach(self::$font_list as $slug => $font){
			$group_key = isset($font['group']) && isset($grouped[$font['group']]) ? $font['group'] : 'display';
			$grouped[$group_key]['fonts'][$slug] = $font;
		}

		// 비어 있는 그룹은 표시하지 않음
		foreach($grouped as $group_key => $group){
			if(empty($group['fonts'])){
				unset($grouped[$group_key]);
			}
		}

		return $grouped;
	}

	/**
	 * 숫자를 CSS 에 쓰기 좋게 (불필요한 0 제거)  예: 13.0 -> "13", 1.8180 -> "1.818"
	 */
	private static function css_number($number){
		$text = number_format((float) $number, 4, '.', '');
		return rtrim(rtrim($text, '0'), '.');
	}

	/**
	 * 줄 간격 값 : 글자 크기와 줄 간격을 둘 다 정했으면 "비율"(단위 없음)로 만들어
	 * 제목처럼 글자 크기가 다른 곳에서도 크기에 비례해서 줄 간격이 정해지게 한다.
	 * 글자 크기를 정하지 않았으면 입력한 px 값을 그대로 쓴다.
	 *
	 * @param string $size        본문(p) 글자 크기 (px, 비어 있을 수 있음)
	 * @param string $line_height 본문(p) 줄 간격 (px, 비어 있을 수 있음)
	 * @return string CSS 값 ("" 이면 지정하지 않음)
	 */
	public static function body_line_height($size, $line_height){

		if('' === $line_height){
			return '';
		}

		if('' === $size || (float) $size <= 0){
			return self::css_number($line_height) . 'px';
		}

		return self::css_number((float) $line_height / (float) $size);
	}

	/**
	 * "사이트 전체(body)" 를 선택했을 때 본문(p)과 제목(h1~h6)의 크기와 줄 간격을 정한다.
	 *
	 * 본문(p)이 기준이고, h6 부터 2px 씩 커진다.  예) p 11px -> h6 13px, h5 15px ... h1 23px
	 *
	 * @param string $size        본문 글자 크기 (px, 비어 있으면 크기는 지정하지 않음)
	 * @param string $line_height 본문 줄 간격 (px, 비어 있으면 지정하지 않음)
	 * @return array 태그 => array('size' => 글자 크기(숫자 문자열 또는 ""), 'line_height' => 줄 간격 CSS 값 또는 "")
	 */
	public static function body_scale($size, $line_height){

		$result = array();
		$lh     = self::body_line_height($size, $line_height);

		foreach(array('p', 'h6', 'h5', 'h4', 'h3', 'h2', 'h1') as $step => $tag){
			$result[$tag] = array(
				'size'        => ('' === $size) ? '' : self::css_number((float) $size + 2 * $step),
				'line_height' => $lh,
			);
		}

		return $result;
	}

	public static function get_target_tag_list(){
		return self::$target_tag;
	}

	public static function get_font_family_list(){
		return self::$font_family_list;
	}

	public static function get_single_font($font_name){
		foreach (self::$font_list as $font) :
			if($font["font_name"] === $font_name):
				return $font;
			endif;
		endforeach;
		return null;
	}

	/**
	 * 예전(플러그인 폴더 안) CSS 파일 경로. 이미 생성된 파일을 읽을 때만 사용
	 */
	public static function get_css_file_name($path){
		return $path .'assets/css/' . self::get_css_basename();
	}

	/**
	 * CSS 파일의 이름(확장자 제외)
	 */
	public static function get_css_basename(){

		$current_site_url = str_replace(array("http://","https://"),"",site_url());
		$css_prefix = str_replace(array(".", "/"),"-",$current_site_url);

		return $css_prefix . self::$css_file_name;
	}

	/**
	 * 새 CSS 파일 저장 폴더 (uploads/rocket-font/)
	 * 플러그인 폴더는 업데이트 때 지워지므로 업로드 폴더를 사용함
	 *
	 * @param boolean $create 폴더가 없으면 만들지 여부
	 * @return string|false 끝에 슬래시가 붙은 경로. 사용할 수 없으면 false
	 */
	public static function get_upload_css_dir($create = false){

		$upload = wp_upload_dir(null, false);
		if(!empty($upload['error']) || empty($upload['basedir'])){
			return false;
		}

		$dir = trailingslashit($upload['basedir']) . 'rocket-font/';

		if($create && !is_dir($dir)){
			if(!wp_mkdir_p($dir)){
				return false;
			}
			@file_put_contents($dir . 'index.php', "<?php\n// Silence is golden.\n");
		}

		return $dir;
	}

	public static function get_upload_css_url(){

		$upload = wp_upload_dir(null, false);
		return trailingslashit($upload['baseurl']) . 'rocket-font/';
	}

	/**
	 * 설정값으로 CSS 파일을 만든다 (uploads/rocket-font/)
	 *
	 * 설정 저장 직후와 플러그인 업데이트 직후에 호출된다. 사용자 입력은 이미 Sanitizer 를 거친 값이어야 한다.
	 *
	 * @param array $options 옵션 전체 (RocketFont::defaults() 형식)
	 * @return boolean 성공 여부
	 */
	public static function generate_css($options){

		$font_list        = self::$font_list;
		$backup_font_list = self::$font_family_list;
		$target_tag_list  = self::$target_tag;

		$selected_font_info = array( 'font_name' => '', 'cdn_url' => '' );
		if( isset( $font_list[ $options['selected_font_slug'] ] ) ){
			$selected_font_info = $font_list[ $options['selected_font_slug'] ];
		}

		$selected_backup_font_info = array( 'font_name' => '', 'generic_font_family' => '', 'font_stack' => '' );
		if( 'select' === $options['en_font_add_type'] ){
			if( isset( $backup_font_list[ $options['selected_font_family'] ] ) ){
				$selected_backup_font_info = $backup_font_list[ $options['selected_font_family'] ];
				$selected_backup_font_info['font_stack'] = $selected_backup_font_info['font_name'];
			}
		}else{
			$name = Sanitizer::font_name( $options['selected_font_family_name'] );
			$selected_backup_font_info['font_name']  = $name;
			$selected_backup_font_info['font_stack'] = ( '' !== $name ) ? "'" . $name . "'" : '';
		}

		ob_start();
		include PLUGIN_DIR . 'assets/templates/admin/tinymce-rocketfont-dynamic-css.php';
		$result = ob_get_clean();

		$dir = self::get_upload_css_dir( true );
		if( ! $dir || ! is_writable( $dir ) ){
			return false;
		}

		$css_save_path = $dir . self::get_css_basename();

		$ok = self::put_file( $css_save_path . '.css', $result );
		$ok = self::put_file( $css_save_path . '.min.css', self::compress_css( $result ) ) && $ok;

		return $ok;
	}

	private static function put_file($path, $contents){

		if( false === file_put_contents( $path, $contents, LOCK_EX ) ){
			return false;
		}

		@chmod( $path, 0644 );
		return true;
	}

	private static function compress_css($css){

		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		return $css;
	}

	/**
	 * 사용자 화면에서 사용할 CSS(.min.css) 위치
	 * 업로드 폴더의 파일을 우선 사용하고, 없으면 예전 위치의 파일을 사용함
	 *
	 * @return array|null array('path' => 파일 경로, 'url' => URL). 파일이 없으면 null
	 */
	public static function locate_css(){

		$basename = self::get_css_basename();
		$dir = self::get_upload_css_dir(false);

		if($dir && is_file($dir . $basename . '.min.css')){
			return array(
				'path' => $dir . $basename . '.min.css',
				'url'  => self::get_upload_css_url() . $basename . '.min.css',
			);
		}

		$legacy_path = self::get_css_file_name(PLUGIN_DIR) . '.min.css';
		if(is_file($legacy_path)){
			return array(
				'path' => $legacy_path,
				'url'  => self::get_css_file_name(PLUGIN_URL) . '.min.css',
			);
		}

		return null;
	}
}
