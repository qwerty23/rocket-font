/**
 * Rocket Font 설정 화면
 *
 * - 외부 라이브러리 없이 동작 (색상 선택기는 워드프레스에 포함된 wp-color-picker 사용)
 * - 폰트/태그 설정 변경 시 오른쪽 미리보기에 바로 반영
 * - 저장되지 않은 변경 사항 표시 및 이탈 경고
 *
 * 서버에서 넘겨 주는 데이터: window.rocketFontAdmin (optionKey, fonts, families, tags, allowedHosts, i18n)
 * 입력 이름은 Settings API 규칙에 따라 rocket_font[항목] 형태이다.
 */
jQuery(function ($) {
	'use strict';

	var cfg       = window.rocketFontAdmin || {};
	var optionKey = cfg.optionKey || 'rocket_font';
	var fonts     = cfg.fonts || {};
	var families  = cfg.families || {};
	var tags      = cfg.tags || [];
	var allowed   = cfg.allowedHosts || [];
	var i18n      = cfg.i18n || {};

	var $form = $('#rocketfont_form');

	// 문제가 생기면 화면 위에 알려 준다 (개발자 도구를 열지 않아도 원인을 알 수 있도록)
	function rfReport(msg) {
		var box = document.getElementById('rf-js-status');
		if (!box) {
			box = document.createElement('div');
			box.id = 'rf-js-status';
			box.className = 'notice notice-error';
			box.style.cssText = 'padding:8px 12px;margin:10px 0;';
			var host = document.getElementById('rocketfont_form') || document.querySelector('.wrap') || document.body;
			host.parentNode.insertBefore(box, host);
		}
		box.textContent = 'Rocket Font 미리보기 오류: ' + msg;
	}

	if (!$form.length) {
		rfReport('설정 폼(#rocketfont_form)을 찾지 못했습니다.');
		return;
	}
	if (!window.rocketFontAdmin) {
		rfReport('폰트 데이터(rocketFontAdmin)가 전달되지 않았습니다. 플러그인 파일이 모두 올라갔는지 확인해 주세요.');
		return;
	}
	$form.attr('data-rf-js', 'ready');

	var $style   = $('<style id="rf-preview-style"></style>').appendTo(document.head);
	var $cssLine = $('#rf-css-line');
	var dirty      = false;
	var submitting = false;

	/* ---------- 유틸 ---------- */

	// rocket_font[항목] 입력을 찾는다
	function field(name) {
		return $form.find('[name="' + optionKey + '[' + name + ']"]');
	}

	function isAllowedUrl(url) {
		if (!url) {
			return false;
		}
		var a = document.createElement('a');
		a.href = (url.indexOf('//') === 0) ? 'https:' + url : url;
		return a.protocol === 'https:' && allowed.indexOf(a.hostname.toLowerCase()) !== -1;
	}

	function loadStylesheet(id, href) {
		if (!href || document.getElementById(id)) {
			return;
		}
		var abs = (href.indexOf('//') === 0) ? 'https:' + href : href;

		// 서버가 이미 불러온 폰트(화면이 열릴 때 미리 불러 둠)는 다시 불러오지 않는다
		var links = document.getElementsByTagName('link');
		for (var i = 0; i < links.length; i++) {
			if (links[i].rel === 'stylesheet' && links[i].href === abs) { // 주소 전체(? 뒤 포함)가 같아야 같은 폰트이다
				return;
			}
		}

		var link = document.createElement('link');
		link.rel = 'stylesheet';
		link.id = id;
		link.href = abs;
		document.head.appendChild(link);
	}

	function loadInlineFace(id, css) {
		if (!css || document.getElementById(id)) {
			return;
		}
		var el = document.createElement('style');
		el.id = id;
		el.textContent = css;
		document.head.appendChild(el);
	}

	// 서버(Sanitizer::font_name)와 같은 규칙: 글자, 숫자, 공백, -, _ 만 허용
	function sanitizeFontName(value) {
		var v = String(value || '');
		try {
			v = v.replace(new RegExp('[^\\p{L}\\p{N} _\\-]', 'gu'), '');
		} catch (e) {
			v = v.replace(/[^A-Za-z0-9 _\-À-￿]/g, '');
		}
		return v.replace(/\s+/g, ' ').trim().substring(0, 60);
	}

	function markDirty() {
		if (!dirty) {
			dirty = true;
			$('.rf-dirty').prop('hidden', false);
		}
	}

	function numberOrEmpty(value) {
		var v = String(value).trim();
		return /^\d{1,3}(\.\d{1,2})?$/.test(v) ? v : '';
	}

	/* ---------- 폰트 스택 / 미리보기 ---------- */

	function koreanFont() {
		return fonts[field('selected_font_slug').val()] || null;
	}

	function englishMode() {
		return field('en_font_add_type').filter(':checked').val() === 'text' ? 'text' : 'select';
	}

	// 서버(write_css)가 만드는 font-family 와 같은 순서 : 영문, "한글", 기본 글꼴
	function currentStack() {
		var ko = koreanFont();
		var en = '';
		var generic = 'sans-serif';

		if (englishMode() === 'select') {
			var f = families[field('selected_font_family').val()];
			if (f) {
				en = f.name;
				generic = f.generic || 'sans-serif';
			}
		} else {
			var name = sanitizeFontName(field('selected_font_family_name').val());
			if (name) {
				en = "'" + name + "'";
			}
		}

		var parts = [];
		if (en) {
			parts.push(en);
		}
		if (ko && ko.name) {
			parts.push('"' + ko.name + '"');
		}
		parts.push(generic);
		return parts.join(', ');
	}

	function ensureFontsLoaded() {
		var slug = field('selected_font_slug').val();
		var ko = fonts[slug];
		if (ko) {
			if (ko.css) {
				loadStylesheet('rf-font-' + slug, ko.css);
			}
			if (ko.face) {
				loadInlineFace('rf-face-' + slug, ko.face);
			}
		}

		var $msg = $('#rf-url-msg');
		if (englishMode() === 'text') {
			var url = String(field('selected_font_family_url').val() || '').trim();
			if (url === '' || isAllowedUrl(url)) {
				$msg.prop('hidden', true).text('');
				if (url !== '') {
					loadStylesheet('rf-en-' + url.replace(/[^a-z0-9]/gi, '').substring(0, 60), url);
				}
			} else {
				$msg.text(i18n.urlNotAllowed || '').prop('hidden', false);
			}
		} else {
			$msg.prop('hidden', true).text('');
		}
	}

	/* ---------- "사이트 전체(body)" 계산 (서버 Font_Setting::body_scale 과 같은 규칙) ---------- */

	function cssNumber(n) {
		return String(parseFloat(Number(n).toFixed(4)));
	}

	// 줄 간격 : 글자 크기와 줄 간격을 둘 다 정했으면 비율(단위 없음), 아니면 입력한 px
	function bodyLineHeight(size, lh) {
		if (lh === '') {
			return '';
		}
		if (size === '' || parseFloat(size) <= 0) {
			return cssNumber(lh) + 'px';
		}
		return cssNumber(parseFloat(lh) / parseFloat(size));
	}

	// 본문(p)이 기준, h6 부터 2px 씩 커진다
	function bodyScale(size, lh) {
		var order = ['p', 'h6', 'h5', 'h4', 'h3', 'h2', 'h1'];
		var result = {};
		var line = bodyLineHeight(size, lh);

		$.each(order, function (step, tag) {
			result[tag] = {
				size: (size === '') ? '' : cssNumber(parseFloat(size) + 2 * step),
				lineHeight: line
			};
		});

		return result;
	}

	function bodyOn() {
		return field('font_use_body').is(':checked');
	}

	// "사이트 전체"를 켜면 제목과 문단 줄은 잠그고, 자동으로 정해진 값을 보여 준다
	function syncRows() {
		var on = bodyOn();
		var scaled = on ? bodyScale(
			numberOrEmpty(field('font_weight_body').val()),
			numberOrEmpty(field('font_lineheight_body').val())
		) : null;

		$.each(tags, function (i, tag) {
			if (tag === 'body') {
				return;
			}

			var $row = $form.find('.rf-tag-row[data-tag="' + tag + '"]');
			$row.toggleClass('is-derived', on);
			$row.find('input').prop('disabled', on);

			var sizeText = '';
			var lineText = '';

			if (on) {
				if (scaled[tag].size !== '') {
					sizeText = scaled[tag].size + 'px';
				}
				if (scaled[tag].lineHeight !== '') {
					lineText = scaled[tag].lineHeight;

					// 비율(단위 없음)이면 이 줄의 글자 크기에 곱해서 px 로 보여 준다
					if (lineText.indexOf('px') === -1 && scaled[tag].size !== '') {
						lineText = cssNumber(Math.round(parseFloat(lineText) * parseFloat(scaled[tag].size) * 10) / 10) + 'px';
					}
				}
			}

			$row.find('[data-rf-derived="size"]').text(sizeText);
			$row.find('[data-rf-derived="line"]').text(lineText);
		});
	}

	// 위쪽 버튼(사이트 전체 / 항목별 / 적용 안 함)에 지금 스위치 상태를 표시한다
	function syncPresets() {
		var preset = 'none';

		if (bodyOn()) {
			preset = 'body';
		} else {
			$.each(tags, function (i, tag) {
				if (tag !== 'body' && field('font_use_' + tag).is(':checked')) {
					preset = 'tags';
					return false;
				}
			});
		}

		$form.find('[data-rf-preset]').each(function () {
			var active = ($(this).data('rf-preset') === preset);
			$(this).toggleClass('is-active', active).attr('aria-pressed', active ? 'true' : 'false');
		});
	}

	function buildPreview() {
		ensureFontsLoaded();
		syncRows();
		syncPresets();

		var stack = currentStack();
		var css = '';
		var canvas = '#rf-preview-canvas';

		if (bodyOn()) {
			var size = numberOrEmpty(field('font_weight_body').val());
			var lh = numberOrEmpty(field('font_lineheight_body').val());
			var color = String(field('font_color_body').val() || '').trim();
			var scaled = bodyScale(size, lh);

			var decl = 'font-family:' + stack + ';';
			if (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(color)) {
				decl += 'color:' + color + ';';
			}
			if (size !== '') {
				decl += 'font-size:' + size + 'px;';
			}
			if (bodyLineHeight(size, lh) !== '') {
				decl += 'line-height:' + bodyLineHeight(size, lh) + ';';
			}
			css += canvas + '{' + decl + '}';

			$.each(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'], function (i, tag) {
				var d = 'font-family:' + stack + ';';
				if (scaled[tag].size !== '') {
					d += 'font-size:' + scaled[tag].size + 'px;';
				}
				if (scaled[tag].lineHeight !== '') {
					d += 'line-height:' + scaled[tag].lineHeight + ';';
				}
				css += canvas + ' ' + tag + '{' + d + '}';
			});
		} else {
			$.each(tags, function (i, tag) {
				if (tag === 'body' || !field('font_use_' + tag).is(':checked')) {
					return;
				}

				var d = 'font-family:' + stack + ';';

				var c = String(field('font_color_' + tag).val() || '').trim();
				if (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(c)) {
					d += 'color:' + c + ';';
				}

				var sz = numberOrEmpty(field('font_weight_' + tag).val());
				if (sz !== '') {
					d += 'font-size:' + sz + 'px;';
				}

				var line = numberOrEmpty(field('font_lineheight_' + tag).val());
				if (line !== '') {
					d += 'line-height:' + line + 'px;';
				}

				css += canvas + ' ' + tag + '{' + d + '}';
			});
		}

		$style.text(css);
		$cssLine.text(stack);
	}

	/* ---------- 영문 폰트 입력 방식 전환 ---------- */

	function toggleEnglishMode() {
		var text = englishMode() === 'text';
		$('.rf-en-select').prop('hidden', text);
		$('.rf-en-text').prop('hidden', !text);
	}

	/* ---------- 슬라이더 <-> 숫자 입력 ---------- */

	$form.find('.rf-range').each(function () {
		var $range = $(this);
		var $num = field($range.data('rf-for'));

		function fromNumber() {
			var v = numberOrEmpty($num.val());
			if (v !== '') {
				$range.val(v);
			}
		}

		fromNumber();

		$range.on('input change', function () {
			$num.val($range.val()).trigger('input');
		});
		$num.on('input change', fromNumber);
	});

	/* ---------- 색상 선택기 ---------- */

	try { $form.find('.rf-color').wpColorPicker({
		change: function (event, ui) {
			$(event.target).val(ui.color.toString());
			markDirty();
			buildPreview();
		},
		clear: function () {
			markDirty();
			window.setTimeout(buildPreview, 0);
		}
	}); } catch (e) { /* 색상 선택기를 못 불러와도 미리보기는 동작해야 한다 */ }

	// 워드프레스 색상 선택기가 없으면 브라우저 기본 색상 선택으로 대신한다
	if (typeof $.fn.wpColorPicker !== 'function') {
		$form.find('.rf-color').each(function () {
			var $text = $(this);
			var $native = $('<input type="color" class="rf-native-color" aria-hidden="true" tabindex="-1">');
			if (/^#[0-9a-f]{6}$/i.test($text.val())) {
				$native.val($text.val());
			}
			$native.on('input change', function () {
				$text.val(this.value).trigger('input');
			});
			$text.after($native);
		});
	}

	/* ---------- 프리셋 ---------- */

	$form.on('click', '[data-rf-preset]', function () {
		var preset = $(this).data('rf-preset');

		$.each(tags, function (i, tag) {
			var on = false;

			if (preset === 'body') {
				on = (tag === 'body');      // 사이트 전체 : 본문 기준으로 제목까지 자동 적용
			} else if (preset === 'tags') {
				on = (tag !== 'body');      // 항목별로 따로 설정 : 사이트 전체는 끄고 나머지를 켠다
			}

			var $box = field('font_use_' + tag);
			$box.prop('disabled', false).prop('checked', on);
		});

		markDirty();
		buildPreview();
	});

	/* ---------- 한글 폰트 검색 상자 (select2) ---------- */

	// 이름(한글/영문)과 슬러그로 검색한다. 그룹 제목은 그대로 두고 일치하는 폰트만 남긴다
	function fontMatcher(params, data) {
		var term = String(params.term || '').trim().toLowerCase();
		if (term === '') {
			return data;
		}

		function hit(d) {
			var name = d.element ? ($(d.element).attr('data-name') || '') : '';
			var hay = (String(d.text || '') + ' ' + name + ' ' + String(d.id || '')).toLowerCase();
			return hay.indexOf(term) !== -1;
		}

		if (data.children && data.children.length) {
			var kids = [];
			$.each(data.children, function (i, child) {
				if (hit(child)) {
					kids.push(child);
				}
			});
			if (kids.length) {
				var group = $.extend({}, data);
				group.children = kids;
				return group;
			}
			return null;
		}

		return hit(data) ? data : null;
	}

	if (typeof $.fn.select2 === 'function') {
		try {
			field('selected_font_slug').select2({
				width: '100%',
				matcher: fontMatcher,
				language: {
					noResults: function () { return i18n.noResults || 'No results'; },
					searching: function () { return i18n.searching || 'Searching...'; }
				}
			});
		} catch (e) {
			rfReport('폰트 검색 상자를 시작하지 못했습니다: ' + e.message);
		}
	}

	/* ---------- 이벤트 연결 ---------- */

	$form.on('change input', 'input, select, textarea', function () {
		markDirty();
		buildPreview();
	});

	$form.on('change', 'input[name$="[en_font_add_type]"]', toggleEnglishMode);

	$form.on('submit', function () {
		submitting = true;
	});

	$('#rocketfont_reset_form').on('submit', function () {
		if (!window.confirm(i18n.confirmReset || 'Reset all settings?')) {
			return false;
		}
		submitting = true;
		return true;
	});

	$(window).on('beforeunload', function (event) {
		if (dirty && !submitting) {
			var message = i18n.confirmLeave || '';
			event.returnValue = message;
			return message;
		}
	});

	/* ---------- 초기화 ---------- */

	toggleEnglishMode();
	buildPreview();

	// 초기화 과정에서 발생한 이벤트로 "변경됨" 표시가 생기지 않도록 한다
	window.setTimeout(function () {
		dirty = false;
		$('.rf-dirty').prop('hidden', true);
	}, 0);
});
