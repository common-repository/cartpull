<?php 

class HookMax_API{
    private static $instance = null;

    private function __construct(){
        add_action('rest_api_init', array($this, 'init_endpoints') );
    }

    public static function get_instance(){
		if( self::$instance === null )
			self::$instance = new HookMax_API();
		
		return self::$instance;
    }
    
    public function init_endpoints(){
        register_rest_route( 'cart-pull/v1/', 'verify',array(
            'methods'  => 'GET',
            'callback' => array( $this, 'verify_cart_pull' )
        ));

        register_rest_route( 'cart-pull/v1/', 'unverify',array(
            'methods'  => 'GET',
            'callback' => array( $this, 'unverify_cart_pull' )
        ));

        register_rest_route( 'cart-pull/v1/', 'orders',array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_orders' )
        ));
    }


    public function verify_cart_pull(){
        $key = get_option( 'cart-pull-key');
        if($key === $_GET['key'] ){
            update_option( 'cart_pull_verified', 'true' );
            return array('verified'=>'true');
        }
        return   array('verified'=>'false');
    }

    public function unverify_cart_pull(){
        $key = get_option( 'cart-pull-key');
        if($key === $_GET['key'] ){
            update_option( 'cart_pull_verified', 'false' );
            return array('unverified'=>'true');
        }
        return   array('unverified'=>'false');
    }

}