=== 한글 ===
Contributors: qwerty23
Tags: korean, font, web font, hangul, typography
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.0
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Apply Korean web fonts to your whole site in a few clicks - no CSS editing required. 한글 폰트를 고르고 저장하면 끝!

== Description ==

**한글 (Rocket Font)** lets you use Korean (Hangul) web fonts on your WordPress site without touching any CSS. Pick a font, check the live preview, save - that's all.

= 이런 분께 추천합니다 =

CSS 를 잘 다루지 못하는 분들을 위해 최대한 쉽게 만들었습니다.

1. **설정 > 한글 폰트** 메뉴를 엽니다.
2. 마음에 드는 한글 폰트를 검색해서 고르고, 오른쪽 **미리보기**로 확인합니다.
3. **변경 사항 저장**을 누르면 사이트 전체에 적용됩니다.

= Features =

* One-click Korean web fonts (Pretendard, SUIT, Nanum, Noto Sans KR, Google Fonts Korean families and more).
* Searchable font list (select2) and a live preview that stays in view while you scroll.
* Live preview while you change fonts, colors, sizes and line heights.
* Change the font of just the text you select while writing a post: a "한글" button in the block editor toolbar and in the classic editor toolbar (searchable font list). Only the fonts used in a post are loaded on the front end.
* Optional separate English font (choose from a list or enter a Google Fonts URL).
* Three one-click presets: whole site (body), per tag, or off.
* "Whole site" sizes headings automatically from the body size: p is the base, h6 is +2px, up to h1 at +12px, with proportional line heights.
* Or set body, h1-h6 and p separately: font on/off, text color, font size and line height.
* Apply the font only to specific classes / IDs (advanced).
* Alternative "force" application mode for themes that override fonts with `!important`.
* Use the same font in the classic editor (TinyMCE) - optional.
* Optional asynchronous font loading (experimental).
* Multisite ready: each site keeps its own settings and CSS file.
* Lightweight: your settings are written to a small minified CSS file. No front-end JavaScript unless you enable an option that needs it.

= 사용할 수 있는 폰트 =

모든 폰트는 **웹 사용이 허용된 라이선스**(대부분 SIL Open Font License)이고, 폰트 파일이 **CDN(Google Fonts, jsDelivr)** 에서 제공되는 것만 등록되어 있습니다. 폰트 파일은 내 서버에 저장되지 않습니다.

**고딕** : 프리텐다드(Pretendard), SUIT, 원티드 산스, 노토 산스(Noto Sans KR), IBM Plex Sans KR, 나눔 고딕, 고운 돋움, 고딕 A1, 나눔 스퀘어, 나눔 바른 고딕

**바탕 / 명조** : 노토 세리프(Noto Serif KR), 나눔 명조, 고운 바탕, 함렛, 송명

**손글씨** : 나눔 펜글씨, 나눔 붓글씨, 개구, 하이멜로디, 감자꽃, 푸어 스토리, 동해 독도, 싱글 데이, 기랑 해랑, 흑백사진

**제목 / 장식** : 검은고딕, 도현, 주아, 베이글 팻 원, 가석 원, 동글, 해바라기, 오르빗, 구기, 스타일리시, 귀여운 폰트, 연성, 독도, 배민 을지로체

**코딩** : 나눔 고딕 코딩

폰트별 라이선스는 각 폰트 제공처의 안내를 따릅니다.

== External services ==

This plugin loads web font stylesheets and font files from third-party CDNs. **Fonts are loaded by the visitor's browser**, so the CDN receives the visitor's IP address and browser information (User-Agent, Referer) when a page that uses the plugin is opened. The plugin itself does not send any other data to these services.

* **Google Fonts** (`fonts.googleapis.com`, `fonts.gstatic.com`) - used for the fonts that are hosted by Google. Privacy information: https://developers.google.com/fonts/faq/privacy / Terms: https://policies.google.com/terms
* **jsDelivr** (`cdn.jsdelivr.net`) - used for the fonts that are hosted on jsDelivr (Pretendard, SUIT, Wanted Sans, Nanum, etc.) and for the optional WebFont Loader script when asynchronous loading is enabled. Privacy policy: https://www.jsdelivr.com/terms/privacy-policy-jsdelivr-net / Terms: https://www.jsdelivr.com/terms
* **cdnjs** (`cdnjs.cloudflare.com`) - only if you enter a font CSS address from this host yourself. Privacy policy: https://www.cloudflare.com/privacypolicy/

If you enter your own font CSS address in the "영문 폰트 > 직접 입력하기" field, only `https` addresses on the hosts above are accepted.

The settings screen loads the selected font stylesheets from the same services to show the preview. No JavaScript is loaded from external sites: the search box library (select2, MIT license) is bundled with the plugin.

== Installation ==

1. Upload the `rocket-font` folder to `/wp-content/plugins/`, or install the plugin from the WordPress plugin directory.
2. Activate the plugin through the **Plugins** screen.
3. Go to **Settings > 한글 폰트** (Settings > Rocket Font), choose a font and press **변경 사항 저장**.

== Frequently Asked Questions ==

= 폰트가 바뀌지 않아요 =

1. 캐시 플러그인(또는 호스팅의 캐시)과 브라우저 캐시를 먼저 비워 보세요.
2. 설정 화면의 **"2. 어디에 적용할까요?"** 에서 "사이트 전체"를 선택하고 저장해 보세요.
3. 그래도 안 되면 **"3. 폰트가 바뀌지 않을 때"** 에서 "강제로 적용하기"를 선택해 보세요.
4. 특정 영역만 안 바뀐다면 "특정 영역에만 폰트 적용하기"에 그 영역의 class 또는 ID 를 추가하세요.

= Does it work with multisite? =

Yes. Each site has its own settings and generated CSS file (`uploads/rocket-font/`).

= Where is the generated CSS stored? =

In `wp-content/uploads/rocket-font/`. The folder is created when you first save the settings and is removed when the plugin is deleted.

= Does the plugin slow down my site? =

The front end only loads one very small CSS file plus the font stylesheet from the CDN. If you want the page to open faster, try the experimental asynchronous loading option.

== Screenshots ==

1. 설정 화면 - 폰트를 고르면 오른쪽 미리보기에 바로 반영됩니다.
2. 더 자세히 조절하기 - 태그별 글자 색, 크기, 줄 간격.
3. 폰트가 적용된 사이트 화면.

== Changelog ==

= 2.0.0 =
* change : 폰트 목록을 점검해 **라이선스를 확인할 수 없거나 폰트 파일을 더 이상 CDN 에서 받을 수 없는 폰트를 목록에서 뺐습니다.** (제주 고딕/명조/한라산, 한나, KoPub 바탕, 이롭게 바탕, 오버워치, KCC은영체, 윤봉길체, 스포카 한 산스) 이 폰트를 사용 중이던 사이트에는 설정 화면에 안내가 표시되며, 다른 폰트를 골라 저장하면 됩니다.
* new : 글 편집 화면에서 **드래그한 글자만 원하는 한글 폰트로** 바꿀 수 있습니다. 블록 에디터(툴바의 "한글" 버튼, 이름 검색 가능)와 클래식 에디터(도구 모음의 "한글" 메뉴)를 모두 지원합니다. 글에서 쓴 폰트만 사이트에서 불러오며, 설정의 "글 편집 화면에서 글자별 폰트 사용"에서 끌 수 있습니다.
* change : "사이트 전체(body)"를 선택하면 제목(h1~h6)과 문단(p)은 본문 크기를 기준으로 자동으로 정해집니다. 문단이 기준 크기이고 h6부터 2px씩 커지며, 줄 간격은 글자 크기에 비례합니다. 이때 제목/문단 줄은 직접 바꿀 수 없습니다. 항목별로 따로 정하려면 "사이트 전체"를 끄고 "항목별로 따로 설정"을 선택하세요. (이전 버전에서 사이트 전체와 제목 크기를 함께 지정해 두었다면 업데이트 후 설정 화면을 확인해 주세요.)
* fix : 영문 폰트를 Tahoma 로 고르면 한글 폰트가 시스템 기본 글꼴로 바뀌던 문제.
* new : 설정 화면의 한글 폰트 선택 상자에서 이름으로 검색할 수 있습니다 (select2).
* fix : 설정 화면의 미리보기가 화면이 열릴 때부터 저장된 폰트로 보이고, 스크롤해도 화면에 고정됩니다.
* security : 설정 저장과 화면 출력의 보안을 강화했습니다. 권한 확인, nonce 확인, 입력값 검증, 출력 이스케이프를 추가하고 워드프레스 Settings API 를 사용하도록 변경했습니다. 반드시 업데이트해 주세요.
* new : 설정 화면을 새로 만들었습니다. 실시간 미리보기, 적용 범위 프리셋, 저장되지 않은 변경 사항 표시, 기본값으로 되돌리기.
* new : 설정 화면의 외부 JavaScript 라이브러리를 모두 제거했습니다 (워드프레스 기본 컴포넌트 사용).
* change : 생성되는 CSS 파일을 `wp-content/uploads/rocket-font/` 에 저장합니다. 플러그인을 업데이트해도 사라지지 않습니다.
* change : 영문 폰트가 `Arial, Helvetica` 처럼 여러 이름으로 구성된 경우 올바른 font-family 로 출력되도록 수정했습니다.
* change : 영문 폰트 CSS 주소는 허용된 호스트(Google Fonts, jsDelivr, cdnjs)의 https 주소만 사용할 수 있습니다.
* fix : PHP 8 호환성 (사용되지 않는 문법, 정의되지 않은 변수 등).
* fix : "나눔 바른 고딕 웹", "Gugi" 폰트가 적용되지 않던 이름 오류.
* new : 폰트 목록을 새로 정리했습니다. 프리텐다드, SUIT, 원티드 산스, IBM Plex Sans KR, 노토 세리프, 고운 돋움/바탕, 함렛 등을 추가하고 폰트를 용도별(고딕, 바탕, 손글씨, 제목/장식)로 묶어 보여 줍니다.
* change : Noto Sans KR 을 전체 글자를 지원하는 Google Fonts 버전으로 바꾸고, 나눔 폰트 등 Google Fonts 의 폰트는 현재 주소 방식으로 갱신했습니다. 기존 설정은 업데이트 후 자동으로 반영됩니다.
* fix : 줄 간격에 소수점 값이 저장되지 않던 현상.
* add : 플러그인 삭제 시 설정과 생성된 CSS 파일을 정리합니다.

= 1.2.5 =
* add : 폰트 추가 - 이롭게 바탕
* add : 폰트 추가 - 오버워치
* add : 언어파일 추가
* fix : 설정 메뉴의 툴팁을 일부 수정했습니다.

= 1.2.4 =
* add : 폰트 추가 - 나눔 스퀘어
* update : 폰트 버전업( 2.0 > 2.1.0 ) - 스포카 한 산스

= 1.2.2 =
* fix : 백업 폰트를 설정하지 않았을경우 클래스 및 ID 에 폰트가 적용되지 않는(css) 현상

= 1.2.0 =
* add : 기능 추가 - 특정 class 및 ID에 폰트 적용 기능

= 1.1.0 =
* fix : 옵션중 [워드프레스 기본 에디터에서 폰트 사용]을 사용으로 했을때 적용되지 않던 현상
* add : 폰트 추가 - 나눔 바른 고딕 light
* add : 폰트 추가 - 나눔 바른 고딕 ultra light
* add : 각 태그별 사용 설정 - 사용, 사용하지 않음으로 명확하게 표시

= 1.0.2 =
* fix : 일부 사이트에서 플러그인 활성화 및 저장시 오류가 나던 현상
* fix : KoPub 돋음 폰트 삭제 - CDN 의 css 설정이 잘못되어 있음
* 플러그인을 활성화 했지만 아직 저장을 하지 않았을 경우 css 파일을 불러오지 않도록 처리

= 1.0.1 =
* fix : 코펍 돋움을 선택했을때 코펍 바탕으로 적용되는 현상

= 1.0.0 =
* 멀티 사이트 대응 : 각 사이트별로 css 생성하고 사용합니다.
* NotoSansKR 폰트 추가

= 0.0.2 =
* body 태그 폰트적용 기본값 사용으로 변경

= 0.0.1 =
* 첫 버전

== Upgrade Notice ==

= 2.0.0 =
보안 업데이트입니다. 폰트 목록이 바뀌어 일부 폰트(제주 고딕 등)는 더 이상 제공되지 않습니다. 이전 버전에서는 설정 저장 처리에 권한 및 nonce 확인이 없었습니다. 반드시 업데이트해 주세요. 업데이트 후 설정 화면에서 한 번 저장하면 새 위치(uploads/rocket-font/)에 CSS 가 생성됩니다.
