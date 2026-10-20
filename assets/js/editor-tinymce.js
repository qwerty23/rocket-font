/**
 * Rocket Font : 클래식 에디터(TinyMCE)에서 선택한 글자만 한글 폰트로 바꾸기
 *
 * 도구 모음에 "한글 폰트" 메뉴 버튼을 추가한다. 메뉴는 폰트 종류(고딕, 바탕 ...)별로 묶여 있다.
 * 선택한 글자는 <span class="rocketfont" data-rocketfont="슬러그">…</span> 로 저장된다.
 *
 * 서버에서 넘겨 주는 데이터 : TinyMCE 설정의 rocketfont_data (JSON 문자열)
 */
( function () {
	'use strict';

	if ( ! window.tinymce || ! window.tinymce.PluginManager ) {
		return;
	}

	window.tinymce.PluginManager.add( 'rocketfont', function ( editor ) {

		var data = editor.getParam( 'rocketfont_data' );

		// 워드프레스는 { 로 시작하는 설정값을 문자열이 아닌 객체로 넘겨 주기도 한다. 둘 다 처리한다
		if ( typeof data === 'string' ) {
			try {
				data = JSON.parse( data );
			} catch ( e ) {
				return;
			}
		}

		if ( ! data || ! data.fonts ) {
			return;
		}

		var i18n = data.i18n || {};

		/* ---------- 편집 화면(iframe)에 폰트 불러오기 ---------- */

		function loadFont( slug ) {
			var font = data.fonts[ slug ];
			var doc = editor.getDoc();

			if ( ! font || ! doc || ! doc.head ) {
				return;
			}

			if ( font.css && ! doc.getElementById( 'rf-ef-' + slug ) ) {
				var link = doc.createElement( 'link' );
				link.rel = 'stylesheet';
				link.id = 'rf-ef-' + slug;
				link.href = font.css;
				doc.head.appendChild( link );
			}

			if ( font.face && ! doc.getElementById( 'rf-ef-face-' + slug ) ) {
				var face = doc.createElement( 'style' );
				face.id = 'rf-ef-face-' + slug;
				face.textContent = font.face;
				doc.head.appendChild( face );
			}

			if ( ! doc.getElementById( 'rf-ef-rules' ) ) {
				var rules = doc.createElement( 'style' );
				rules.id = 'rf-ef-rules';
				rules.textContent = data.rules || '';
				doc.head.appendChild( rules );
			}
		}

		// 글 안에 이미 쓰인 폰트를 불러온다
		function loadUsedFonts() {
			var doc = editor.getDoc();

			if ( ! doc ) {
				return;
			}

			var nodes = doc.querySelectorAll( '[data-rocketfont]' );

			for ( var i = 0; i < nodes.length; i++ ) {
				loadFont( nodes[ i ].getAttribute( 'data-rocketfont' ) );
			}
		}

		/* ---------- 서식 등록과 적용 ---------- */

		editor.on( 'PreInit', function () {
			editor.formatter.register( 'rocketfont', {
				inline: 'span',
				classes: 'rocketfont',
				attributes: { 'data-rocketfont': '%slug' }, // TinyMCE 서식 변수는 %이름 (끝에 %를 붙이지 않는다)
				// 같은 서식이 이미 있으면 새로 감싸지 않고 값만 바꾼다
				exact: true
			} );
		} );

		editor.on( 'init', loadUsedFonts );
		editor.on( 'SetContent', loadUsedFonts );

		function applyFont( slug ) {
			loadFont( slug );

			editor.undoManager.transact( function () {
				editor.formatter.remove( 'rocketfont' ); // 이전 폰트를 먼저 제거 (span 이 겹치지 않도록)
				editor.formatter.apply( 'rocketfont', { slug: slug } );
			} );

			editor.nodeChanged();
		}

		function resetFont() {
			editor.undoManager.transact( function () {
				editor.formatter.remove( 'rocketfont' );
			} );

			editor.nodeChanged();
		}

		/* ---------- 메뉴 ---------- */

		function buildMenu() {
			var menu = [ {
				text: i18n.reset || 'Reset',
				onclick: resetFont
			} ];

			( data.groups || [] ).forEach( function ( group ) {
				menu.push( {
					text: group.label,
					menu: group.slugs.map( function ( slug ) {
						return {
							text: data.fonts[ slug ].title,
							onclick: function () {
								applyFont( slug );
							}
						};
					} )
				} );
			} );

			return menu;
		}

		editor.addButton( 'rocketfont', {
			type: 'menubutton',
			text: i18n.button || i18n.title || 'Korean font',
			tooltip: i18n.title || 'Korean font',
			icon: false,
			menu: buildMenu()
		} );
	} );
}() );
