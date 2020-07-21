<?php
namespace rocket_font;

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
	
	/**
	 * 옵션값이 변경되었을때 업데이트하는 펑션
	 *
	 * @static
	 */	
	public function detect_option_change(){
			
		if(!empty($_POST['action'])){
			$action = $_POST['action'];

			switch($action):
				case'update':
					self::update_option_check_match_default($_POST);
					break;
			endswitch;

		}
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
	 *  post 값과 default(이전에 저장된 옵션값) 를 비교 후 post 값에 변화가 있으면 업데이트
	 *
	 * @param $post  사용자가 입력한 폼값
	 * @return boolean
	 */
	public static function update_option_check_match_default($post){
		return PluginOptions::update_option_check_match_default( $post );
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