# WordPress Plugin - 한글 (Rocket Font)

<p align="center">
<a href="https://github.com/qwerty23/rocket-font/releases"><img src="https://img.shields.io/github/v/release/qwerty23/rocket-font" alt="release"></a>
<img src="https://img.shields.io/badge/PHP-7.0%2B-777bb4" alt="php">
<img src="https://img.shields.io/badge/WordPress-5.0%2B-21759b" alt="wordpress">
<img src="https://img.shields.io/badge/license-GPL--2.0--or--later-blue" alt="license">
</p>

워드프레스에서 **한글 폰트를 CSS 편집 없이** 사용할 수 있는 플러그인입니다.

- CSS 를 잘 다루지 못하는 분들을 위해 최대한 심플하게 만들었습니다.
- 사용하고 싶은 폰트를 고르고 저장하면 끝입니다.
- 폰트 파일은 내 서버에 저장되지 않고 CDN(Google Fonts, jsDelivr)에서 불러옵니다.

> 정말 오랜만의 업데이트입니다. 오랫동안 손을 놓고 있었더니 보안 문제가 발견되어 플러그인이 폐쇄되어 버렸네요. 이번에 보안 문제를 고치고, 폰트를 일부 업데이트 및 추가했으며 설정 화면도 새로 만들었습니다. 사용하다가 버그 및 오류가 있다면 알려주세요.

## 주요 기능

- **폰트 검색** : 설정 화면의 폰트 선택 상자에서 이름으로 검색할 수 있습니다 (select2).
- **실시간 미리보기** : 화면이 열릴 때부터 저장된 폰트로 보이고, 폰트, 색, 크기, 줄 간격을 바꾸면 바로 반영됩니다. 스크롤해도 화면에 고정됩니다.
- **적용 범위 프리셋 3가지** : `사이트 전체` / `항목별로 따로 설정` / `적용 안 함` 버튼으로 한 번에 설정할 수 있습니다.
- **사이트 전체(본문 기준)** : 문단(p)을 기준 크기로 하고 h6 은 +2px, h5 는 +4px … h1 은 +12px 로 자동 계산합니다. 줄 간격도 글자 크기에 비례합니다.
- **글자별 폰트 (신기능)** : 글 편집 화면에서 **드래그한 글자만** 원하는 한글 폰트로 바꿀 수 있습니다.
  - 블록 에디터 : 툴바의 **"한글"** 버튼 (폰트 이름 검색 가능)
  - 클래식 에디터 : 도구 모음의 **"한글"** 메뉴
  - 글에는 `<span class="rocketfont" data-rocketfont="슬러그">` 형태로 저장되어 다른 서식과 겹치지 않으며, 사이트에서는 **글에서 실제로 쓴 폰트만** 불러옵니다.
- **한글 단어 들여쓰기** : 줄 끝에서 한글이 글자 중간이 아니라 띄어쓰기(단어) 단위로 줄 바꿈되게 합니다 (CSS `word-break: keep-all`). 폰트를 적용하는 곳에 폰트 CSS와 함께 추가됩니다.
- 영문 폰트를 따로 지정 (목록 선택 또는 Google Fonts 주소 직접 입력)
- 특정 class / ID 에만 폰트 적용, `!important` 를 쓰는 테마를 위한 강제 적용 모드
- 워드프레스 기본 에디터(TinyMCE)에서 같은 폰트 사용, 비동기 폰트 로딩(실험적)
- 멀티사이트 지원 : 사이트마다 설정과 CSS 파일을 따로 가집니다.

## 사용할 수 있는 한글 폰트 (42종)

**고딕** : 프리텐다드(Pretendard), SUIT, 원티드 산스(Wanted Sans), 노토 산스(Noto Sans KR), IBM Plex Sans KR, 나눔 고딕, 고운 돋움, 고딕 A1, 나눔 스퀘어, 나눔 바른 고딕 (Light, Ultra Light 포함)

**바탕 / 명조** : 노토 세리프(Noto Serif KR), 나눔 명조, 고운 바탕, 함렛, 송명

**손글씨** : 나눔 펜글씨, 나눔 붓글씨, 개구, 하이멜로디, 감자꽃, 푸어 스토리, 동해 독도, 싱글 데이, 기랑 해랑, 흑백사진

**제목 / 장식** : 검은고딕, 도현, 주아, 베이글 팻 원, 가석 원, 동글, 해바라기, 오르빗, 구기, 스타일리시, 귀여운 폰트, 연성, 독도, 배민 을지로체

**코딩** : 나눔 고딕 코딩

- **웹 사용이 허용된 라이선스**(대부분 SIL Open Font License)의 폰트만 등록되어 있습니다.
- 라이선스를 확인할 수 없거나 CDN 에서 더 이상 제공되지 않는 폰트(제주 고딕/명조/한라산, 한나, KoPub 바탕, 이롭게 바탕, 오버워치, KCC은영체, 윤봉길체, 스포카 한 산스)는 2.0.0 에서 제외했습니다. 이 폰트를 쓰던 사이트는 설정 화면의 안내에 따라 다른 폰트를 골라 저장하면 됩니다.

## 설치

일반적인 워드프레스 플러그인 설치와 같습니다.

1. [Releases](https://github.com/qwerty23/rocket-font/releases) 에서 압축 파일을 받거나, 이 저장소를 내려받아 `rocket-font` 폴더를 `wp-content/plugins/` 에 올립니다.
2. 관리자 > 플러그인에서 **한글** 을 활성화합니다.
3. `설정` > `한글 폰트` 에서 폰트를 고르고 **변경 사항 저장**을 누릅니다.
4. 더 자세히 조절하려면 "더 자세히 조절하기" 에서 항목별 색, 크기, 줄 간격을 설정하세요.

필요한 환경 : WordPress 5.0 이상, PHP 7.0 이상 (PHP 8.2 기준으로 개발)

## 폰트가 바뀌지 않을 때

1. 캐시 플러그인(또는 호스팅 캐시)과 브라우저 캐시를 먼저 비워 보세요.
2. "어디에 적용할까요?" 에서 **사이트 전체** 를 선택하고 저장해 보세요.
3. 그래도 안 되면 "폰트가 바뀌지 않을 때" 의 **강제로 적용하기** 를 선택해 보세요.
4. 특정 영역만 안 바뀐다면 "특정 영역에만 폰트 적용하기" 에 그 영역의 class 또는 ID 를 추가하세요.

## 외부 서비스

폰트는 **방문자의 브라우저가** 아래 CDN 에서 직접 불러옵니다. 이때 CDN 에 방문자의 IP 와 브라우저 정보가 전달될 수 있습니다. 플러그인이 그 외의 데이터를 보내지는 않습니다.

- Google Fonts (`fonts.googleapis.com`, `fonts.gstatic.com`)
- jsDelivr (`cdn.jsdelivr.net`)
- cdnjs (`cdnjs.cloudflare.com`) : 영문 폰트 주소를 직접 입력한 경우에만

설정 화면의 검색 상자(select2, MIT 라이선스)는 플러그인에 포함되어 있어 외부에서 JavaScript 를 불러오지 않습니다.

## 문의 혹은 건의사항

기타 플러그인에 관한 문의 및 건의사항은 [플러그인 소개글](http://in-web.co.kr/wordpress/plug-in/wordpress-%EC%97%90%EC%84%9C-%ED%95%9C%EA%B8%80-%ED%8F%B0%ED%8A%B8%EB%A5%BC-rocket-font/)에 부담없이 댓글이나 메일을 보내주세요. GitHub 에서는 Issues 를 이용해 주세요.

## 참고

아래는 워드프레스에서 업로드 파일 확장자를 쉽게 제어할 수 있는 플러그인입니다.
- <a href="https://wordpress.org/plugins/rocket-media-library-mime-type" target="_blank">워드프레스 플러그인 사이트 링크</a>
- <a href="https://github.com/qwerty23/rocket-media-library-mime-type" target="_blank">깃허브 사이트 링크</a>

## LICENSE
[GPL-2.0 license](https://opensource.org/licenses/GPL-2.0)
