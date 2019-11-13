<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_dokan_lite extends APPMAKER_WC_REST_Posts_Abstract_Controller {

    protected $namespace = 'appmaker-wc/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'products';

    private $options;
    public function __construct()
    {
        parent::__construct();
        register_rest_route($this->namespace, '/' . $this->rest_base . '/chatnow', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_chat_script' ),
                'permission_callback' => array( $this, 'api_permissions_check' ),
                'args'                => $this->get_collection_params(),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        $this->options = get_option('appmaker_wc_settings');

       // remove_filter( 'woocommerce_process_registration_errors',array( WeDevs_Dokan::init()->container['registration'],  'validate_registration'));
      //  remove_filter( 'woocommerce_registration_errors', array( WeDevs_Dokan::init()->container['registration'], 'validate_registration'  ));

        add_filter( 'appmaker_wc_register_username_required', '__return_false' );
       // add_filter( 'appmaker_wc_register_email_required', '__return_false' );
        add_filter( 'appmaker_wc_register_phone_required', '__return_false' );
        add_filter( 'appmaker_wc_register_password_required', '__return_false' );
        add_filter( 'appmaker_wc_login_after_register_required', '__return_false' );
        add_filter('appmaker_wc_registration_response',array($this,'dokan_registration_response'),2,1);
        add_filter( 'appmaker_wc_product_widgets', array( $this, 'get_vendor_info' ), 2, 3 );
        add_filter( 'appmaker_wc_account_page_response', array($this,'vendor_dashboard'),10,1 );
    }

    public function vendor_dashboard($return){

        $user_id = get_current_user_id();
        $user = get_user_by( 'id',$user_id);
        if ( in_array( 'seller', (array) $user->roles ) ) {
            $base_url = site_url();
            $url = $base_url . '/dashboard/orders/';
            $api_key = $this->options['api_key'];
            $access_token = apply_filters('appmaker_wc_set_user_access_token', $user_id);
            $url = add_query_arg(array('from_app' => true), $url);

            $url = base64_encode($url);
            $url = $base_url . '/?rest_route=/appmaker-wc/v1/user/redirect/&url=' . $url . '&api_key=' . $api_key . '&access_token=' . $access_token . '&user_id=' . $user_id;
            $wallet = array('received_orders' => array(
                'title' => __('Orders Dashboard', 'appmaker-woocommerce-mobile-app-manager'),
                'icon' => array(
                    'android' => 'event-note',
                    'ios' => 'ios-copy-outline',
                ),
                'action' => array(
                    'type' => 'OPEN_IN_WEB_VIEW',
                    'params' => array('url' => $url),
                ),
            ),
            );
            $return = array_slice($return, 0, 3, true) +
                $wallet +
                array_slice($return, 3, count($return) - 3, true);
        }

        return $return;
    }

    public function dokan_registration_response($return){

             $notice = dokan_get_option( 'registration_notice', 'dokan_email_verification' );
             $return = array(
                 'status'       => 1,
                 'message'      =>$notice,
             );
             return $return;


    }
    public  function get_vendor_info($return,$product,$data) {

        $user_id = get_current_user_id();
        $author_id  = get_post_field( 'post_author', $product->get_id() );
        $author     = get_user_by( 'id', $author_id );
        $store_info = dokan_get_store_info( $author->ID );

        foreach($return as $key => $tab){
            if($key == 'seller'){
                $return[$key]['content'] = $store_info['store_name'];
            }
        }

      if($user_id && class_exists('Dokan_Pro')) {
            $url = site_url();
          $api_key = $this->options['api_key'];
          $access_token = apply_filters('appmaker_wc_set_user_access_token', $user_id);

          array_splice($return, 1, 0, array('chat_now' => array(
              'type' => 'menu',
              'expandable' => true,
              'expanded' => false,
              'title' => __('Chat Now', 'dokan'),
              'content' => '',
              'action' => array(
                  'type' => 'OPEN_IN_WEB_VIEW',
                  'params' => array(
                      'url' => $url.'/?rest_route=/appmaker-wc/v1/products/chatnow'. '&api_key=' . $api_key . '&access_token=' . $access_token . '&user_id=' . $user_id,
                      'title' => __('Chat Now', 'dokan'),
                  ),
              ),
          )));
      }
        return $return;
    }

    public function get_chat_script(){
        ob_start();
        do_shortcode( '[dokan-live-chat]' );
        $output = ob_get_contents();
        $output = <<<HTML
<html>
<head>
    $output
    <script>
	window.onload = function(){
setTimeout(function(){let chat_btn = document.querySelector( '.dokan-live-chat' );
	chat_btn.click();
console.log("add");},500);
};
</script>
</head>
<body>
<button class="dokan-btn dokan-btn-theme dokan-btn-sm dokan-live-chat" style="display:none;">	
		Chat Now
            </button>
</body>
</html>
HTML;

        ob_end_clean();
        header('Content-Type:text/html');
        echo $output;exit;
    }

}
new APPMAKER_WC_dokan_lite();