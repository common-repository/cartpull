<?php 
class HookMax{
	
	private static $instance = null;
	public  $key;
	private function __construct()
	{
		add_action( 'admin_menu', array($this, 'cart_pull_menu_page' ) );

		$this->key = get_option('cart-pull-key','');
		if( empty($this->key) ){
			$this->key = $this->generateRandomStr(32);
			update_option('cart-pull-key', $this->key);
		}

		HookMax_Connector::get_instance();
		HookMax_API::get_instance();

	}

	public static function get_instance(){
		if( self::$instance === null )
			self::$instance = new HookMax();
		
		return self::$instance;
	}

    public static function generateRandomStr($length = 32) {
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}

	/**
	 * Register menu page.
	 */
	public function cart_pull_menu_page() { 
		add_menu_page(
			__( 'HookMax', 'cart-pull' ),
			__( 'HookMax', 'cart-pull' ),
			'manage_options',
			'hookmax/admin/cart-pull-page.php',
			function(){
				require dirname(__FILE__).'/cart-pull-page.php';
			},
			'dashicons-email-alt'
		);

		add_action( 'admin_print_styles-hookmax/admin/cart-pull-page.php', array($this,'admin_css') );
		add_action( 'admin_init', array( $this, 'register_cart_pull_settings' ) );
	}

	public function register_cart_pull_settings(){ 
		register_setting( 'cart-pull-settings-group', 'enable_checkoput_registration' );
	}
	public function admin_css(){ 
		wp_enqueue_style( 'cart_pull', plugin_dir_url(__FILE__).'/style.css' );
	}


}