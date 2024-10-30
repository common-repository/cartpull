<?php 
/* Plugin Name:       HookMax
 * Plugin URI:        https://HookMax.com
 * Description:       Increase sales in your WooCommerce store by connecting to HookMax - Abandoned abondant recovery by email.
 * Version:           1.0.2
 * Author:            wpdebuglog
 * Author URI:        https://hookmax.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cart-pull
 */

define('CART_PULL_URL', 'https://hookmax.com');

require_once plugin_dir_path( __FILE__ ).'/admin/admin.php';
require_once plugin_dir_path( __FILE__ ).'/inc/connector.php';
require_once plugin_dir_path( __FILE__ ).'/api/connect-store.php';


HookMax::get_instance();

add_action('init', 'cart_pull_init'); 

function cart_pull_init(){
	if( isset($_GET['customer_id']) && isset($_GET['cart-redirect']) == true && 
			isset($_GET['expiration']) && isset($_GET['expiring']) && isset($_GET['hash']) ){  
		$cookie = apply_filters( 'woocommerce_cookie', 'wp_woocommerce_session_' . COOKIEHASH );
		$customer_id         = esc_sql( $_GET['customer_id'] );
		$session_expiration  = esc_sql( $_GET['expiration'] );
		$session_expiring    = esc_sql( $_GET['expiring'] );
		$cookie_hash         = esc_sql( $_GET['hash'] );
		$to_hash             = $customer_id . '|' . $session_expiration;
		$cookie_hash         = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );
		$cookie_value        = $customer_id . '||' . $session_expiration . '||' . $session_expiring . '||' . $cookie_hash;

		wc_setcookie( $cookie, $cookie_value, $session_expiration, apply_filters( 'wc_session_use_secure_cookie', false ) );
		wp_safe_redirect( wc_get_cart_url() );
		die;
	}
}


add_action('woocommerce_order_status_changed', 'cart_pull_post_data_status_changed', 10, 4 );

function cart_pull_post_data_status_changed(  $woo_order_id, $status_from, $status_to,  $order ){
	$sore_key = get_option('cart-pull-key');
	$url = CART_PULL_URL.'/api/change-order-status';
	$response = wp_remote_post( $url, array(
		'method'      => 'POST',
		'timeout'     => 45,
		'blocking'    => true,
		'headers'     => array(),
		'body'        => compact('woo_order_id', 'sore_key', 'status_to') 
		)
	);

}


