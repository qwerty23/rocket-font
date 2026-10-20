<?php
/**
 * 폰트 CSS 생성 템플릿
 *
 * Setting::write_css() 안에서만 include 됨.
 * 사용 변수 : $options, $target_tag_list, $selected_font_info, $selected_backup_font_info
 *
 * 폰트 이름은 목록(Font_Setting) 또는 Sanitizer::font_name() 으로 검증된 값이고,
 * 색상, 크기, 선택자는 저장된 예전 값이 있을 수 있어 출력 직전에 한번 더 검증한다.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$double_qute = '"';

$korean_font_name  = isset( $selected_font_info['font_name'] ) ? $selected_font_info['font_name'] : '';
// 영문 폰트: 목록에서 고른 경우는 font-family 목록 그대로, 직접 입력한 경우는 따옴표로 감싼 이름 (Setting::write_css() 에서 결정)
$english_stack     = isset( $selected_backup_font_info['font_stack'] ) ? $selected_backup_font_info['font_stack'] : '';
$generic_family    = ! empty( $selected_backup_font_info['generic_font_family'] ) ? $selected_backup_font_info['generic_font_family'] : 'sans-serif';

if(!empty($selected_font_info["inline_font_face"])){
	echo $selected_font_info["inline_font_face"]; // 코드 안에 정의된 값
}


// 폰트 이름 목록 (영문, 한글, 기본 글꼴 순서)
$font_family_value = '';
if($english_stack) {
	$font_family_value .= $english_stack . ', ';
}
if($korean_font_name) {
	$font_family_value .= $double_qute . $korean_font_name . $double_qute . ', ';
}
$font_family_value .= $generic_family;

/**
 * 규칙 하나를 출력한다.  $tag 는 코드 안에 정의된 태그 이름, 나머지 값은 위에서 검증한 값이다.
 */
// 한글 단어 들여쓰기 (사용하지 않으면 빈 문자열)
$word_break = \rocket_font\Font_Setting::word_break_css( $options );

$print_rule = function ( $tag, $color, $size, $line_height ) use ( $font_family_value, $word_break ) {
	echo $tag . '{font-family:' . $font_family_value . ';' . $word_break;
	if ( '' !== $color ) {
		echo 'color:' . $color . ';';
	}
	if ( '' !== $size ) {
		echo 'font-size:' . $size . 'px;';
	}
	if ( '' !== $line_height ) {
		echo 'line-height:' . $line_height . ';';
	}
	echo '}' . "\n";
};

if ( isset( $options['font_use_body'] ) && 'yes' === $options['font_use_body'] ) :

	// "사이트 전체(body)" : 본문(p)을 기준으로 제목(h1~h6)의 크기와 줄 간격을 비례해서 정한다
	$body_color = \rocket_font\Sanitizer::hex_color( isset( $options['font_color_body'] ) ? $options['font_color_body'] : '' );
	$body_size  = \rocket_font\Sanitizer::int_or_empty( isset( $options['font_weight_body'] ) ? $options['font_weight_body'] : '' );
	$body_lh    = \rocket_font\Sanitizer::int_or_empty( isset( $options['font_lineheight_body'] ) ? $options['font_lineheight_body'] : '' );
	$scaled     = \rocket_font\Font_Setting::body_scale( $body_size, $body_lh );

	$print_rule( 'body', $body_color, $body_size, \rocket_font\Font_Setting::body_line_height( $body_size, $body_lh ) );

	foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ) as $scaled_tag ) :
		$print_rule( $scaled_tag, '', $scaled[ $scaled_tag ]['size'], $scaled[ $scaled_tag ]['line_height'] );
	endforeach;

else :

	// 항목별로 따로 설정
	foreach ( $target_tag_list as $target_tag ) :

		if ( 'body' === $target_tag || ! isset( $options[ 'font_use_' . $target_tag ] ) || 'yes' !== $options[ 'font_use_' . $target_tag ] ) {
			continue;
		}

		$tag_color = \rocket_font\Sanitizer::hex_color( isset( $options[ 'font_color_' . $target_tag ] ) ? $options[ 'font_color_' . $target_tag ] : '' );
		$tag_size  = \rocket_font\Sanitizer::int_or_empty( isset( $options[ 'font_weight_' . $target_tag ] ) ? $options[ 'font_weight_' . $target_tag ] : '' );
		$tag_lh    = \rocket_font\Sanitizer::int_or_empty( isset( $options[ 'font_lineheight_' . $target_tag ] ) ? $options[ 'font_lineheight_' . $target_tag ] : '' );

		$print_rule( $target_tag, $tag_color, $tag_size, ( '' === $tag_lh ) ? '' : $tag_lh . 'px' );

	endforeach;

endif;

$class_id_list = \rocket_font\Sanitizer::selector_list( isset( $options['rocketfont_class_id_list'] ) ? $options['rocketfont_class_id_list'] : '' );

if($class_id_list !== '') {
	echo $class_id_list;
	?>
	{font-family:<?php
	if($english_stack) {
		echo $english_stack . ",";
	}

	if($korean_font_name) {
		echo $double_qute . $korean_font_name . $double_qute . ",";
	}

	echo $generic_family;
	?>;<?php echo $word_break; ?>
	}
	<?php
}
