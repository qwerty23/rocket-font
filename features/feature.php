<?php
namespace rocket_font;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/*
	 *
	 * features폴더 안에 구현할 파일은 반드시 이 파일을 상속받아야 함
	 */
class Feature {

	/**
	 * 이 클래스를 상속받은 자식 클래스들은 add_action( 'dnp_init' , array( 'DNP\클래스명' , 'init'  )  , 1 ,  1 );으로 호출하는데, 이때의 init
	 */
	public static function init() {
		if ( function_exists( 'get_called_class' ) ) {
			$current_class = get_called_class();
			return new $current_class();
		}
		return NULL;
	}
	
	public function get_template() {
		return new Template();
	}

	public function get_option( $name, $default = NULL ) {
		return PluginOptions::get_option( $name, $default );
	}
	
	public function get_current_all_options(){
		return PluginOptions::get_current_all_options();
	}

	public function set_option( $name, $value ) {
		return PluginOptions::update_option( $name, $value );
	}
	
	/**
	 * 검증이 끝난 여러 옵션값을 한번에 저장 (사용자 입력은 반드시 Sanitizer 를 먼저 거칠 것)
	 *
	 * @param array $values 키 => 값
	 * @return boolean
	 */
	public function set_options( $values ) {
		return PluginOptions::update_options( $values );
	}

	public function marshal( $method_name ) {
		return array( &$this, $method_name );
	}

	/**
	 * 클래스에서 add_action 명령어를 사용할 때 클래스명을 일일히 쓸 필요없이 $this 를 넣으면 됨 
	 *
	 * @param string $action        
	 * @param string $method_name   
	 * @param int    $priority      
	 * @param int    $accepted_args 
	 */
	public function add_action( $action, $method_name, $priority = 10, $accepted_args = 2 ) {
		add_action( $action, $this->marshal( $method_name ), $priority, $accepted_args );
	}

	public function add_filter( $filter, $method_name, $priority = 10, $accepted_args = 2 ) {
		add_filter( $filter, $this->marshal( $method_name ), $priority, $accepted_args );
	}

	public function add_admin_ajax( $action ) {
		$this->add_action( 'wp_ajax_' . $action, $action );
	}

	public function add_client_ajax( $action ) {
		$this->add_admin_ajax( $action );
		$this->add_action( 'wp_ajax_nopriv_' . $action, $action );
	}
} // End Class