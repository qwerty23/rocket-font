/**
 * Rocket Font : 블록 에디터(구텐베르크)에서 선택한 글자만 한글 폰트로 바꾸기
 *
 * 글자를 선택하면 나타나는 툴바에 "한글 폰트" 버튼을 추가한다.
 * 선택한 글자는 <span class="rocketfont" data-rocketfont="슬러그">…</span> 로 저장된다.
 *
 *  - 빌드 도구 없이 동작하도록 JSX 를 쓰지 않았다 (wp.element.createElement 직접 사용)
 *  - class 를 "rocketfont" 로 고정해 다른 서식과 겹치지 않는다
 *
 * 서버에서 넘겨 주는 데이터 : window.rocketFontEditor (fonts, groups, rules, i18n)
 */
( function ( wp ) {
	'use strict';

	var data = window.rocketFontEditor;

	if ( ! data || ! data.fonts || ! wp || ! wp.richText || ! wp.element || ! wp.blockEditor || ! wp.components ) {
		return;
	}

	var el = wp.element.createElement;
	var useState = wp.element.useState;
	var useEffect = wp.element.useEffect;
	var registerFormatType = wp.richText.registerFormatType;
	var applyFormat = wp.richText.applyFormat;
	var removeFormat = wp.richText.removeFormat;
	var BlockControls = wp.blockEditor.BlockControls;
	var Dropdown = wp.components.Dropdown;
	var ToolbarButton = wp.components.ToolbarButton;

	var FORMAT = 'rocket-font/font';
	var i18n = data.i18n || {};

	if ( ! BlockControls || ! Dropdown || ! ToolbarButton || ! useState || ! useEffect ) {
		return;
	}

	/* ---------- 에디터 화면에 폰트 불러오기 ---------- */

	// 글 내용이 보이는 화면(iframe)과 일반 화면
	function editorDocuments() {
		var docs = [ document ];
		var frames = document.querySelectorAll( 'iframe[name="editor-canvas"]' );

		for ( var i = 0; i < frames.length; i++ ) {
			try {
				if ( frames[ i ].contentDocument && frames[ i ].contentDocument.head ) {
					docs.push( frames[ i ].contentDocument );
				}
			} catch ( e ) {
				// 접근할 수 없는 화면은 건너뜀
			}
		}

		return docs;
	}

	// 폰트 CSS(CDN) 와 적용 규칙을 한 번만 넣는다
	function ensureFont( slug ) {
		var font = data.fonts[ slug ];

		if ( ! font ) {
			return;
		}

		editorDocuments().forEach( function ( doc ) {
			var head = doc.head;

			if ( ! head ) {
				return;
			}

			if ( font.css && ! doc.getElementById( 'rf-ef-' + slug ) ) {
				var link = doc.createElement( 'link' );
				link.rel = 'stylesheet';
				link.id = 'rf-ef-' + slug;
				link.href = font.css;
				head.appendChild( link );
			}

			if ( font.face && ! doc.getElementById( 'rf-ef-face-' + slug ) ) {
				var face = doc.createElement( 'style' );
				face.id = 'rf-ef-face-' + slug;
				face.textContent = font.face;
				head.appendChild( face );
			}

			if ( ! doc.getElementById( 'rf-ef-rules' ) ) {
				var rules = doc.createElement( 'style' );
				rules.id = 'rf-ef-rules';
				rules.textContent = data.rules || '';
				head.appendChild( rules );
			}
		} );
	}

	/* ---------- 툴바 버튼과 폰트 목록 ---------- */

	function Edit( props ) {
		var value = props.value;
		var onChange = props.onChange;
		var isActive = props.isActive;
		var current = ( props.activeAttributes && props.activeAttributes.font ) || '';

		var queryState = useState( '' );
		var query = queryState[ 0 ];
		var setQuery = queryState[ 1 ];

		// 이미 폰트가 적용된 글자를 선택했을 때, 그 폰트가 에디터에 불러와져 있도록 한다
		useEffect( function () {
			if ( current ) {
				ensureFont( current );
			}
		}, [ current ] );

		function choose( slug, close ) {
			ensureFont( slug );
			onChange( applyFormat( value, { type: FORMAT, attributes: { font: slug } } ) );

			if ( close ) {
				close();
			}
		}

		function reset( close ) {
			onChange( removeFormat( value, FORMAT ) );

			if ( close ) {
				close();
			}
		}

		function matches( slug ) {
			var q = query.trim().toLowerCase();
			var font = data.fonts[ slug ];

			if ( q === '' ) {
				return true;
			}

			return ( font.title + ' ' + font.name + ' ' + slug ).toLowerCase().indexOf( q ) !== -1;
		}

		function renderList( close ) {
			var children = [];
			var found = 0;

			children.push( el( 'input', {
				key: 'search',
				type: 'search',
				className: 'rocket-font-search',
				placeholder: i18n.search || 'Search',
				'aria-label': i18n.search || 'Search',
				value: query,
				autoFocus: true,
				onChange: function ( event ) {
					setQuery( event.target.value );
				}
			} ) );

			if ( isActive ) {
				children.push( el( 'button', {
					key: 'reset',
					type: 'button',
					className: 'rocket-font-reset',
					onClick: function () {
						reset( close );
					}
				}, i18n.reset || 'Reset' ) );
			}

			( data.groups || [] ).forEach( function ( group ) {
				var items = [];

				group.slugs.forEach( function ( slug ) {
					if ( ! matches( slug ) ) {
						return;
					}

					found++;

					items.push( el( 'button', {
						key: slug,
						type: 'button',
						className: 'rocket-font-item' + ( slug === current ? ' is-active' : '' ),
						'aria-pressed': slug === current,
						onMouseDown: function ( event ) {
							event.preventDefault(); // 글자 선택이 풀리지 않도록
						},
						onClick: function () {
							choose( slug, close );
						}
					}, data.fonts[ slug ].title ) );
				} );

				if ( items.length ) {
					children.push( el( 'div', { key: 'group-' + group.key, className: 'rocket-font-group' }, group.label ) );
					children.push( el( 'div', { key: 'items-' + group.key, className: 'rocket-font-items' }, items ) );
				}
			} );

			if ( ! found ) {
				children.push( el( 'p', { key: 'none', className: 'rocket-font-empty' }, i18n.noResults || 'No results' ) );
			}

			return el( 'div', { className: 'rocket-font-list' }, children );
		}

		return el( BlockControls, { group: 'inline' },
			el( Dropdown, {
				className: 'rocket-font-dropdown',
				contentClassName: 'rocket-font-popover',
				popoverProps: { placement: 'bottom-start' },
				position: 'bottom right',
				renderToggle: function ( toggle ) {
					return el( ToolbarButton, {
						className: 'rocket-font-toggle',
						label: i18n.title || 'Korean font',
						showTooltip: true,
						isPressed: !! ( isActive || toggle.isOpen ),
						'aria-haspopup': 'true',
						'aria-expanded': toggle.isOpen,
						onClick: toggle.onToggle
					}, i18n.button || i18n.title || 'Korean font' );
				},
				renderContent: function ( content ) {
					return renderList( content.onClose );
				}
			} )
		);
	}

	registerFormatType( FORMAT, {
		title: i18n.title || 'Korean font',
		tagName: 'span',
		className: 'rocketfont',
		attributes: {
			font: 'data-rocketfont'
		},
		edit: Edit
	} );
}( window.wp ) );
