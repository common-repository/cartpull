<?php 
class HookMax_Connector{

    public static $instance;

    private function __construct(){
        //woocommerce_order_status_changed
        add_action('woocommerce_checkout_update_order_meta', array( $this, 'cart_pull_post_order_data'));
        add_filter('wc_session_expiring', array( $this, 'cart_pull_session_expiring') );
        add_filter('wc_session_expiration', array( $this, 'cart_pull_session_expiration') );
    } 

    public static function get_instance(){
        if( self::$instance === null ){
            self::$instance = new HookMax_Connector;
        }
        return self::$instance;
    }

    public function cart_pull_session_expiring( $data ){ 
        return 60 * 60 * 240;
    }

    public function cart_pull_session_expiration( $data ){
        return 60 * 60 * 241;  
    }

    /**
     * Post order data
     */
    public function cart_pull_post_order_data( $order_id ){  
        
        $order       = wc_get_order( $order_id ); 
        $order_data  = $order->get_data();
        $order_items = $order->get_items();
        $session     = WC()->session->get_session_cookie();
        $sore_key    = get_option('cart-pull-key');

        $items_data  = array();
        foreach($order_items as $item){
            $product         = $item->get_data();
            $attachment_id   = get_post_thumbnail_id( $product['product_id'] );
            $image           = wp_get_attachment_image_src( $attachment_id );
            $product['img']  = isset( $image[0] ) ? $image[0]: false;
            $items_data[]    = $product;
        }

        $url = CART_PULL_URL.'/api/connector';
        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'blocking'    => true,
            'headers'     => array(),
            'body'        => compact('order_data', 'items_data', 'session','sore_key') 
            )
        );

    }
}