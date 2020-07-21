<?php
namespace rocket_font;

/**
 * ClassLoader Class
 *
 * 아래 폴더에 있는 php 파일을 검색해 자동으로  include
 *   * features/core   - 항상 로드할 파일들
 *   * features/admin  - 관리자에서만 사용할 파일들
 *   * features/client - 프론트엔드(유저화면)에서만 사용할 파일들
 */
class ClassLoader {

	public static function load_plugin_classes() {
		$load_list = array();
		$load_list = array_merge( $load_list, self::glob_php( PLUGIN_DIR . 'features/core' ) );

		if ( is_admin() ) {
			$load_list = array_merge( $load_list, self::glob_php( PLUGIN_DIR . 'features/admin' ) );
		}
		else {
			$load_list = array_merge( $load_list, self::glob_php( PLUGIN_DIR . 'features/client' ) );
		}
		foreach ( $load_list as $file ) {
			include $file;
		}
	}

	/**
	 * @static
	 * @param string $absolute_path 절대경로를 포함한 검색할 폴더
	 * @return array 해당 폴더에 있는 모든 php 파일들
	 */
	private static function glob_php( $absolute_path ) {
		$absolute_path = untrailingslashit( $absolute_path );
		$files         = array();
		if ( !$dir = @opendir( $absolute_path ) ) {
			return $files;
		}

		while ( FALSE !== $file = readdir( $dir ) ) {
			if ( '.' == substr( $file, 0, 1 ) || '.php' != substr( $file, -4 ) ) {
				continue;
			}

			$file = "$absolute_path/$file";

			if ( !is_file( $file ) ) {
				continue;
			}

			$files[ ] = $file;
		}

		closedir( $dir );

		return $files;
	}
}

