<?php
/**
 * Rocket Font 설정 화면
 *
 * Template::apply() 안에서만 include 됨.
 * 사용 변수 : $option_key, $option_group, $reset_url, $was_reset, $plugin_url, $version,
 *             $options, $font_list, $font_groups, $target_tag, $font_family_list
 *
 * - 저장은 워드프레스 Settings API(options.php)가 처리하며, 입력 이름은 rocket_font[항목] 형태
 * - 모든 출력은 이스케이프 처리
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

// 입력 이름 : rocket_font[항목]
$input_name = function ( $key ) use ( $option_key ) {
	return esc_attr( $option_key . '[' . $key . ']' );
};

$tag_labels = array(
		'body' => __( '사이트 전체 (본문 기준)', 'RocketFont' ),
	'h1'   => __( '제목 1 (h1)', 'RocketFont' ),
	'h2'   => __( '제목 2 (h2)', 'RocketFont' ),
	'h3'   => __( '제목 3 (h3)', 'RocketFont' ),
	'h4'   => __( '제목 4 (h4)', 'RocketFont' ),
	'h5'   => __( '제목 5 (h5)', 'RocketFont' ),
	'h6'   => __( '제목 6 (h6)', 'RocketFont' ),
	'p'    => __( '문단 (p)', 'RocketFont' ),
);

// 현재 선택된 한글 폰트 슬러그 (예전 버전에서 저장된 값은 폰트 이름만 있을 수 있음)
$current_slug = $options['selected_font_slug'];
if ( '' !== $current_slug && ! isset( $font_list[ $current_slug ] ) ) {
	$current_slug = ''; // 목록에서 제외된 폰트
}
if ( '' === $current_slug && '' !== $options['selected_font'] ) {
	foreach ( $font_list as $slug => $font ) {
		if ( $font['font_name'] === $options['selected_font'] ) {
			$current_slug = $slug;
			break;
		}
	}
}

$en_type = ( 'text' === $options['en_font_add_type'] ) ? 'text' : 'select';

// 영문 폰트를 따로 지정한 적이 있으면 펼쳐서 보여 준다
$en_open = ( '' !== $options['selected_font_family'] || '' !== $options['selected_font_family_name'] || '' !== $options['selected_font_family_url'] );

// 태그별 세부 설정에 값이 하나라도 있으면 펼쳐서 보여 준다
$has_tag_values = false;
foreach ( $target_tag as $tag ) {
	if ( '' !== $options[ 'font_color_' . $tag ] || '' !== $options[ 'font_weight_' . $tag ] || '' !== $options[ 'font_lineheight_' . $tag ] ) {
		$has_tag_values = true;
		break;
	}
}

// 고급 옵션을 바꿔 둔 적이 있으면 펼쳐서 보여 준다
$adv_open = ( 'yes' === $options['use_jquery'] || 'yes' === $options['use_tinymce_editor'] || 'yes' === $options['use_async'] );

// "어디에 적용할까요?" 버튼 중 지금 설정과 맞는 것 (스위치와 연동 : 사이트 전체 / 항목별 / 적용 안 함)
$any_tag_on = false;
foreach ( $target_tag as $preset_tag ) {
	if ( 'body' !== $preset_tag && 'yes' === $options[ 'font_use_' . $preset_tag ] ) {
		$any_tag_on = true;
		break;
	}
}
$current_preset = ( 'yes' === $options['font_use_body'] ) ? 'body' : ( $any_tag_on ? 'tags' : 'none' );
?>
<div class="wrap rocket-font-admin" id="rocket-font-admin">

	<h1>
		<?php esc_html_e( '한글 폰트', 'RocketFont' ); ?>
		<span class="rf-version">Rocket Font v<?php echo esc_html( $version ); ?></span>
	</h1>

	<?php if ( $was_reset ) : ?>
		<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e( '모든 설정을 기본값으로 되돌렸습니다.', 'RocketFont' ); ?></strong></p></div>
	<?php endif; ?>

	<?php /* 저장 결과 알림(settings_errors)은 워드프레스가 이 화면 위쪽에 자동으로 출력한다 */ ?>

	<?php if ( '' === $current_slug && '' !== $options['selected_font'] ) : ?>
		<div class="notice notice-warning">
			<p>
				<strong>
					<?php
					printf(
						/* translators: %s: font name */
						esc_html__( '이전에 선택하신 폰트(%s)는 더 이상 제공되지 않아 목록에서 제외되었습니다. 다른 폰트를 골라 저장해 주세요.', 'RocketFont' ),
						esc_html( $options['selected_font'] )
					);
					?>
				</strong>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( '' === $current_slug ) : ?>
		<div class="rf-intro">
			<p><strong><?php esc_html_e( '쉽게 시작하기', 'RocketFont' ); ?></strong></p>
			<ol>
				<li><?php esc_html_e( '아래 "한글 폰트"에서 마음에 드는 폰트를 고릅니다.', 'RocketFont' ); ?></li>
				<li><?php esc_html_e( '오른쪽 미리보기에서 어떻게 보이는지 확인합니다.', 'RocketFont' ); ?></li>
				<li><?php esc_html_e( '맨 아래 [변경 사항 저장] 버튼을 누르면 내 사이트 전체에 적용됩니다.', 'RocketFont' ); ?></li>
			</ol>
			<p class="description"><?php esc_html_e( '다른 설정은 건드리지 않아도 됩니다. 필요할 때만 아래의 "더 자세히 조절하기"를 열어 보세요.', 'RocketFont' ); ?></p>
		</div>
	<?php endif; ?>

	<form method="post" action="options.php" id="rocketfont_form" role="form">
		<?php settings_fields( $option_group ); // option_page, action=update, nonce, referer ?>

		<div class="rf-layout">

			<div class="rf-settings">

				<!-- 1. 폰트 선택 -->
				<section class="rf-card">
					<h2><span class="dashicons dashicons-editor-textcolor" aria-hidden="true"></span> <?php esc_html_e( '1. 폰트 고르기', 'RocketFont' ); ?></h2>

					<div class="rf-field">
						<label for="rf-font-select"><?php esc_html_e( '한글 폰트', 'RocketFont' ); ?></label>
						<select name="<?php echo $input_name( 'selected_font_slug' ); // phpcs:ignore WordPress.Security.EscapeOutput -- esc_attr 처리됨 ?>" id="rf-font-select">
							<option value=""><?php esc_html_e( '선택하지 않음 (폰트를 바꾸지 않음)', 'RocketFont' ); ?></option>
							<?php foreach ( $font_groups as $group ) : ?>
								<optgroup label="<?php echo esc_attr( $group['label'] ); ?>">
									<?php foreach ( $group['fonts'] as $slug => $font ) : ?>
										<option value="<?php echo esc_attr( $slug ); ?>" data-name="<?php echo esc_attr( $font['font_name'] ); ?>" <?php selected( $slug, $current_slug ); ?>><?php echo esc_html( $font['font_text_title'] ); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( '내 사이트의 글꼴로 사용할 한글 폰트를 고르세요. 상자를 열고 이름을 입력하면 검색할 수 있고, 오른쪽 미리보기에 바로 보입니다.', 'RocketFont' ); ?></p>
					</div>

					<details class="rf-details" <?php echo $en_open ? 'open' : ''; ?>>
						<summary><?php esc_html_e( '영문은 다른 폰트를 쓰고 싶어요 (선택 사항)', 'RocketFont' ); ?></summary>

						<div class="rf-field">
							<span class="rf-label"><?php esc_html_e( '영문 폰트 지정 방법', 'RocketFont' ); ?></span>
							<label class="rf-inline">
								<input type="radio" name="<?php echo $input_name( 'en_font_add_type' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="select" <?php checked( $en_type, 'select' ); ?>>
								<?php esc_html_e( '목록에서 고르기', 'RocketFont' ); ?>
							</label>
							<label class="rf-inline">
								<input type="radio" name="<?php echo $input_name( 'en_font_add_type' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="text" <?php checked( $en_type, 'text' ); ?>>
								<?php esc_html_e( '직접 입력하기', 'RocketFont' ); ?>
							</label>

							<div class="rf-en-select" <?php echo ( 'text' === $en_type ) ? 'hidden' : ''; ?>>
								<label class="screen-reader-text" for="rf-family-select"><?php esc_html_e( '영문 폰트 목록', 'RocketFont' ); ?></label>
								<select name="<?php echo $input_name( 'selected_font_family' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" id="rf-family-select">
									<option value=""><?php esc_html_e( '선택하지 않음', 'RocketFont' ); ?></option>
									<?php foreach ( $font_family_list as $key => $family ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $options['selected_font_family'] ); ?>><?php echo esc_html( $family['font_name'] . ', ' . $family['generic_font_family'] ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="rf-en-text" <?php echo ( 'select' === $en_type ) ? 'hidden' : ''; ?>>
								<p>
									<label for="rf-family-url"><?php esc_html_e( '폰트 CSS 주소', 'RocketFont' ); ?></label><br>
									<input type="url" class="regular-text" name="<?php echo $input_name( 'selected_font_family_url' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" id="rf-family-url" value="<?php echo esc_attr( $options['selected_font_family_url'] ); ?>" placeholder="https://fonts.googleapis.com/css?family=Oranienbaum">
									<span class="rf-url-msg" id="rf-url-msg" role="alert" hidden></span>
								</p>
								<p>
									<label for="rf-family-name"><?php esc_html_e( '폰트 이름', 'RocketFont' ); ?></label><br>
									<input type="text" class="regular-text" name="<?php echo $input_name( 'selected_font_family_name' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" id="rf-family-name" value="<?php echo esc_attr( $options['selected_font_family_name'] ); ?>" placeholder="Oranienbaum">
								</p>
								<p class="description">
									<?php
									echo wp_kses(
										__( '<a href="https://fonts.google.com/" target="_blank" rel="noopener">Google Fonts</a>에서 폰트를 고른 뒤 안내된 CSS 주소와 폰트 이름을 입력하세요. 안전을 위해 https 로 시작하는 fonts.googleapis.com, cdn.jsdelivr.net, cdnjs.cloudflare.com 주소만 사용할 수 있습니다.', 'RocketFont' ),
										array( 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array() ) )
									);
									?>
								</p>
							</div>

							<p class="description"><?php esc_html_e( '지정하지 않으면 영문에도 위의 한글 폰트가 사용됩니다.', 'RocketFont' ); ?></p>
						</div>
					</details>
				</section>

				<!-- 2. 적용 범위 -->
				<section class="rf-card">
					<h2><span class="dashicons dashicons-admin-appearance" aria-hidden="true"></span> <?php esc_html_e( '2. 어디에 적용할까요?', 'RocketFont' ); ?></h2>
											<p class="description"><?php esc_html_e( '보통은 "사이트 전체"만 선택하면 충분합니다. 본문과 모든 제목에 같은 폰트가 적용되고, 제목 크기는 본문 크기에 맞춰 자동으로 정해집니다. 제목이나 문단을 따로 꾸미고 싶을 때만 "항목별로 따로 설정"을 선택하세요.', 'RocketFont' ); ?></p>

					<p class="rf-presets">
						<button type="button" class="button rf-preset<?php echo ( 'body' === $current_preset ) ? ' is-active' : ''; ?>" data-rf-preset="body" aria-pressed="<?php echo ( 'body' === $current_preset ) ? 'true' : 'false'; ?>"><?php esc_html_e( '사이트 전체 (추천)', 'RocketFont' ); ?></button>
						<button type="button" class="button rf-preset<?php echo ( 'tags' === $current_preset ) ? ' is-active' : ''; ?>" data-rf-preset="tags" aria-pressed="<?php echo ( 'tags' === $current_preset ) ? 'true' : 'false'; ?>"><?php esc_html_e( '항목별로 따로 설정', 'RocketFont' ); ?></button>
						<button type="button" class="button rf-preset<?php echo ( 'none' === $current_preset ) ? ' is-active' : ''; ?>" data-rf-preset="none" aria-pressed="<?php echo ( 'none' === $current_preset ) ? 'true' : 'false'; ?>"><?php esc_html_e( '적용 안 함', 'RocketFont' ); ?></button>
					</p>

					<details class="rf-details" <?php echo $has_tag_values ? 'open' : ''; ?>>
						<summary><?php esc_html_e( '더 자세히 조절하기 (글자 색, 글자 크기, 줄 간격)', 'RocketFont' ); ?></summary>

						<p class="description"><?php esc_html_e( '비워 두면 현재 테마의 값을 그대로 사용합니다. 꼭 바꾸고 싶은 항목만 입력하세요.', 'RocketFont' ); ?></p>

						<table class="widefat striped rf-tag-table">
							<thead>
								<tr>
									<th scope="col"><?php esc_html_e( '위치', 'RocketFont' ); ?></th>
									<th scope="col"><?php esc_html_e( '폰트 적용', 'RocketFont' ); ?></th>
									<th scope="col"><?php esc_html_e( '글자 색', 'RocketFont' ); ?></th>
									<th scope="col"><?php esc_html_e( '글자 크기 (px)', 'RocketFont' ); ?></th>
									<th scope="col"><?php esc_html_e( '줄 간격 (px)', 'RocketFont' ); ?></th>
								</tr>
							</thead>
							<tbody>
															<?php foreach ( $target_tag as $tag ) : ?>
									<?php
									$label  = isset( $tag_labels[ $tag ] ) ? $tag_labels[ $tag ] : $tag;
									// "사이트 전체"를 켜면 제목과 문단은 본문 기준으로 자동 계산되므로 직접 바꿀 수 없다
									$locked = ( 'body' !== $tag && 'yes' === $options['font_use_body'] );
									?>
									<tr class="rf-tag-row<?php echo $locked ? ' is-derived' : ''; ?>" data-tag="<?php echo esc_attr( $tag ); ?>">
									<th scope="row"><label for="rf-use-<?php echo esc_attr( $tag ); ?>"><?php echo esc_html( $label ); ?></label></th>
									<td>
										<label class="rf-switch">
											<input type="checkbox" id="rf-use-<?php echo esc_attr( $tag ); ?>" name="<?php echo $input_name( 'font_use_' . $tag ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="yes" <?php checked( $options[ 'font_use_' . $tag ], 'yes' ); ?> <?php disabled( $locked ); ?>>
											<span class="rf-switch-ui" aria-hidden="true"></span>
											<span class="screen-reader-text"><?php esc_html_e( '폰트 적용', 'RocketFont' ); ?></span>
										</label>
									</td>
									<td>
										<input type="text" class="rf-color" name="<?php echo $input_name( 'font_color_' . $tag ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="<?php echo esc_attr( $options[ 'font_color_' . $tag ] ); ?>" aria-label="<?php echo esc_attr( $label . ' ' . __( '글자 색', 'RocketFont' ) ); ?>" <?php disabled( $locked ); ?>>
									</td>
									<td class="rf-num">
										<input type="range" min="8" max="64" step="1" class="rf-range" data-rf-for="font_weight_<?php echo esc_attr( $tag ); ?>" aria-hidden="true" tabindex="-1">
										<input type="number" min="1" max="200" step="1" name="<?php echo $input_name( 'font_weight_' . $tag ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="<?php echo esc_attr( $options[ 'font_weight_' . $tag ] ); ?>" placeholder="<?php esc_attr_e( '테마 기본값', 'RocketFont' ); ?>" aria-label="<?php echo esc_attr( $label . ' ' . __( '글자 크기', 'RocketFont' ) ); ?>" <?php disabled( $locked ); ?>>
											<?php if ( 'body' !== $tag ) : ?><span class="rf-derived" data-rf-derived="size"></span><?php endif; ?>
										</td>
									<td class="rf-num">
										<input type="range" min="8" max="96" step="1" class="rf-range" data-rf-for="font_lineheight_<?php echo esc_attr( $tag ); ?>" aria-hidden="true" tabindex="-1">
										<input type="number" min="1" max="300" step="0.1" name="<?php echo $input_name( 'font_lineheight_' . $tag ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="<?php echo esc_attr( $options[ 'font_lineheight_' . $tag ] ); ?>" placeholder="<?php esc_attr_e( '테마 기본값', 'RocketFont' ); ?>" aria-label="<?php echo esc_attr( $label . ' ' . __( '줄 간격', 'RocketFont' ) ); ?>" <?php disabled( $locked ); ?>>
											<?php if ( 'body' !== $tag ) : ?><span class="rf-derived" data-rf-derived="line"></span><?php endif; ?>
										</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<p class="description"><?php esc_html_e( '"사이트 전체"를 켜면 이 줄의 글자 크기가 본문(문단)의 크기가 되고, 제목은 h6부터 2px씩 커지게 자동으로 정해집니다 (예: 문단 11px → h6 13px, h5 15px … h1 23px). 줄 간격도 글자 크기에 비례해서 자동으로 정해지며, 이때 제목과 문단 줄은 직접 바꿀 수 없습니다. 항목별로 다르게 하고 싶으면 "사이트 전체"를 끄고 "항목별로 따로 설정"을 선택하세요.', 'RocketFont' ); ?></p>
					</details>
				</section>

				<!-- 3. 고급 -->
				<section class="rf-card">
					<h2><span class="dashicons dashicons-admin-generic" aria-hidden="true"></span> <?php esc_html_e( '3. 폰트가 바뀌지 않을 때', 'RocketFont' ); ?></h2>
					<p class="description"><?php esc_html_e( '저장했는데도 사이트의 폰트가 바뀌지 않을 때만 사용하세요. 먼저 캐시 플러그인과 브라우저 캐시를 비워 보시고, 그래도 안 되면 아래 방법을 차례로 시도해 보세요.', 'RocketFont' ); ?></p>

					<details class="rf-details" <?php echo $adv_open ? 'open' : ''; ?>>
						<summary><?php esc_html_e( '적용 방식 바꾸기', 'RocketFont' ); ?></summary>

						<div class="rf-field">
							<span class="rf-label"><?php esc_html_e( '폰트를 적용하는 방식', 'RocketFont' ); ?></span>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_jquery' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="no" <?php checked( $options['use_jquery'], 'no' ); ?>> <?php esc_html_e( '기본 방식 (추천)', 'RocketFont' ); ?></label>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_jquery' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="yes" <?php checked( $options['use_jquery'], 'yes' ); ?>> <?php esc_html_e( '강제로 적용하기', 'RocketFont' ); ?></label>
							<p class="description"><?php esc_html_e( '기본 방식으로 폰트가 바뀌지 않을 때 "강제로 적용하기"를 선택하세요.', 'RocketFont' ); ?></p>
						</div>

						<div class="rf-field">
							<span class="rf-label"><?php esc_html_e( '글 쓰기 화면(편집기)에서도 같은 폰트로 보기', 'RocketFont' ); ?></span>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_tinymce_editor' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="no" <?php checked( $options['use_tinymce_editor'], 'no' ); ?>> <?php esc_html_e( '사용하지 않음', 'RocketFont' ); ?></label>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_tinymce_editor' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="yes" <?php checked( $options['use_tinymce_editor'], 'yes' ); ?>> <?php esc_html_e( '사용', 'RocketFont' ); ?></label>
						</div>

						<div class="rf-field">
							<span class="rf-label"><?php esc_html_e( '글 편집 화면에서 글자별 폰트 사용', 'RocketFont' ); ?></span>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_editor_font' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="yes" <?php checked( $options['use_editor_font'], 'yes' ); ?>> <?php esc_html_e( '사용 (추천)', 'RocketFont' ); ?></label>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_editor_font' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="no" <?php checked( $options['use_editor_font'], 'no' ); ?>> <?php esc_html_e( '사용하지 않음', 'RocketFont' ); ?></label>
							<p class="description"><?php esc_html_e( '글을 쓰는 화면에서 글자를 드래그한 뒤 "한글 폰트" 버튼으로 그 글자만 다른 한글 폰트로 바꿀 수 있습니다. 글에서 쓴 폰트만 사이트에서 불러옵니다.', 'RocketFont' ); ?></p>
						</div>

						<div class="rf-field">
							<span class="rf-label"><?php esc_html_e( '폰트를 나중에 불러와 사이트를 빠르게 열기 (실험 기능)', 'RocketFont' ); ?></span>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_async' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="no" <?php checked( $options['use_async'], 'no' ); ?>> <?php esc_html_e( '사용하지 않음', 'RocketFont' ); ?></label>
							<label class="rf-inline"><input type="radio" name="<?php echo $input_name( 'use_async' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" value="yes" <?php checked( $options['use_async'], 'yes' ); ?>> <?php esc_html_e( '사용', 'RocketFont' ); ?></label>
							<p class="description"><?php esc_html_e( '사이트가 빨리 열리지만, 폰트는 페이지가 열린 뒤에 바뀌어 보일 수 있습니다.', 'RocketFont' ); ?></p>
						</div>
					</details>

					<details class="rf-details">
						<summary><?php esc_html_e( '특정 영역에만 폰트 적용하기 (CSS 선택자)', 'RocketFont' ); ?></summary>
						<p>
							<label class="screen-reader-text" for="rf-class-list"><?php esc_html_e( 'CSS 선택자 목록', 'RocketFont' ); ?></label>
							<textarea name="<?php echo $input_name( 'rocketfont_class_id_list' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" id="rf-class-list" rows="3" class="large-text code"><?php echo esc_textarea( $options['rocketfont_class_id_list'] ); ?></textarea>
						</p>
						<p class="description">
							<?php esc_html_e( '쉼표(,)로 구분해 입력하세요. class 는 앞에 점(.), ID 는 앞에 샵(#)을 붙입니다. 예) .content, #content, .content>p', 'RocketFont' ); ?><br>
							<?php esc_html_e( '위에서 폰트를 적용했는데도 바뀌지 않는 영역이 있거나, 특정 영역에만 폰트를 쓰고 싶을 때 사용합니다. 잘 모르겠다면 그대로 두세요. (이 항목은 미리보기에 반영되지 않습니다.)', 'RocketFont' ); ?>
						</p>
					</details>
				</section>

			</div><!-- /.rf-settings -->

			<!-- 미리보기 -->
			<aside class="rf-preview" aria-label="<?php esc_attr_e( '미리보기', 'RocketFont' ); ?>">
				<div class="rf-preview-inner">
					<h2><span class="dashicons dashicons-visibility" aria-hidden="true"></span> <?php esc_html_e( '미리보기', 'RocketFont' ); ?></h2>
					<p class="description"><?php esc_html_e( '바꾼 내용이 바로 보입니다. [변경 사항 저장]을 눌러야 사이트에 적용됩니다.', 'RocketFont' ); ?></p>

					<div id="rf-preview-canvas" class="rf-canvas">
						<h1>H1. 나랏말씀이 중국과 달라 Heading 1</h1>
						<h2>H2. 문자와 서로 통하지 아니하므로 Heading 2</h2>
						<h3>H3. 이런 까닭으로 어리석은 백성이 Heading 3</h3>
						<h4>H4. 이르고자 하는 바가 있어도 Heading 4</h4>
						<h5>H5. 마침내 제 뜻을 실어 펴지 못하는 Heading 5</h5>
						<h6>H6. 사람이 많으니라 Heading 6</h6>
						<p>P. 내가 이를 위하여 딱하게 여겨 새로 스물 여덟 자를 만드니, 사람마다 하여금 쉽게 익혀 날마다 씀에 편안케 하고자 할 따름이니라.<br>The quick brown fox jumps over the lazy dog. 0123456789</p>
					</div>

					<p class="rf-css-line"><strong><?php esc_html_e( '적용되는 폰트 순서', 'RocketFont' ); ?></strong><br><code id="rf-css-line"></code></p>
				</div>
			</aside>

		</div><!-- /.rf-layout -->

		<!-- 저장 바 (항상 화면 아래에 표시) -->
		<div class="rf-savebar">
			<span class="rf-dirty" hidden><span class="dashicons dashicons-warning" aria-hidden="true"></span> <?php esc_html_e( '저장되지 않은 변경 사항이 있습니다.', 'RocketFont' ); ?></span>
			<button type="submit" class="button button-primary button-large"><?php esc_html_e( '변경 사항 저장', 'RocketFont' ); ?></button>
		</div>
	</form>

	<form method="post" action="<?php echo esc_url( $reset_url ); ?>" id="rocketfont_reset_form" class="rf-reset">
		<input type="hidden" name="action" value="rocket_font_reset">
		<?php wp_nonce_field( 'rocket_font_reset', 'rocket_font_reset_nonce' ); ?>
		<button type="submit" class="button-link button-link-delete"><?php esc_html_e( '모든 설정을 처음 상태로 되돌리기', 'RocketFont' ); ?></button>
	</form>

</div>
