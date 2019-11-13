<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class APPMAKER_WC_booster
{
    public $post_type='shop_order';
    private $options;
    public $plugin = 'appmaker_wc';
    public function __construct()
    {
        $this->options = get_option( $this->plugin . '_settings' );
        add_filter("woocommerce_rest_prepare_{$this->post_type}", array($this,'order_invoice'),2,2);
       // add_filter( 'appmaker_wc_payment_gateways_response',array($this,'payment_gateways_icon'),2,1);
    }

  /*  public function payment_gateways_icon($return){
        foreach ($return['gateways'] as $gateways =>$gateway ){
            if ( 'yes' === get_option( 'wcj_gateways_icons_' . $gateway['id'] . '_icon_remove', 'no' ) ) {
                return $return;
            }
            $custom_icon_url = get_option( 'wcj_gateways_icons_' . $gateway['id'] . '_icon', '' );
            if($custom_icon_url!='') {
                $return[$gateways]['icon'] = $custom_icon_url;
            }
        }
        return $return;
    }*/

    public function order_invoice($response,$post){
        $order_id = $post->ID;
        $invoice_types_ids = wcj_get_enabled_invoice_types_ids();
        $base_url = site_url();
        $api_key = $this->options['api_key'];
        $user_id= $user_id = get_current_user_id();
        $access_token = apply_filters( 'appmaker_wc_set_user_access_token', $user_id );
        $message = get_option('wcj_invoicing_invoice_link_text');
        $url='';
        foreach ($invoice_types_ids as $invoice_type_id) {
            //$the_invoice = wcj_get_pdf_invoice($order_id, $invoice_type_id);
            $url = base64_encode($base_url . '/my-account/orders/?order_id=' . $order_id . '&invoice_type_id='.$invoice_type_id.'&get_invoice=1');
        }
        $url = $base_url.'/?rest_route=/appmaker-wc/v1/user/redirect/&url='.$url.'&api_key='.$api_key.'&access_token='.$access_token.'&user_id='.$user_id;
        $url = apply_filters('appmaker_invoice_download_url',$url,$order_id);
        $response->data['top_notice'][]=array(
            'icon' => array(
                'android' => 'file-download',
                'ios'     => 'ios-download-outline',
            ),
            'message'=> empty($message)?'Invoice'.' '.$order_id:$message.' '.$order_id,
            'button'=> array(
                'type'=> 'button',
                'text'=>empty($message)?'Invoice':$message,
                'action'=>array(
                    'type'   => 'OPEN_URL',
                    'params' => array( 'url' =>$url)
                )
            ),
        );

        return $response;


    }

}
new APPMAKER_WC_booster();