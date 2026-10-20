<?php
/**
 * Rocket Font 삭제 시 실행
 *
 * 플러그인을 "삭제"할 때만 실행되며(비활성화에서는 실행되지 않음),
 * 이 플러그인이 만든 설정값과 CSS 파일을 정리한다.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * 현재 사이트의 설정과 생성된 CSS 폴더를 지운다
 */
function rocket_font_uninstall_site() {

	delete_option( 'rocket_font' );
	delete_option( 'rocket_font_version' );

	// uploads/rocket-font/ 안의 파일 삭제
	$upload = wp_upload_dir( null, false );
	if ( empty( $upload['error'] ) && ! empty( $upload['basedir'] ) ) {

		$dir = trailingslashit( $upload['basedir'] ) . 'rocket-font/';

		if ( is_dir( $dir ) ) {
			$files = glob( $dir . '*' );
			if ( is_array( $files ) ) {
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						@unlink( $file );
					}
				}
			}
			@rmdir( $dir );
		}
	}
}

if ( is_multisite() ) {

	$site_ids = get_sites( array( 'fields' => 'ids', 'number' => 0 ) );

	foreach ( $site_ids as $site_id ) {
		switch_to_blog( $site_id );
		rocket_font_uninstall_site();
		restore_current_blog();
	}

} else {

	rocket_font_uninstall_site();
}
