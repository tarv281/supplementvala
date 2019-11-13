<?php
/**
 * Created by IntelliJ IDEA.
 * User: shifa
 * Date: 11/17/18
 * Time: 12:33 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Will only work for chart position : Additional tab
 */
class APPMAKER_WC_Product_Size_Chart
{

    public function __construct()
    {
        add_filter('appmaker_wc_product_widgets', array($this, 'size_chart'), 2, 2);
    }
    public function size_chart($return,$product ) {
        $tabs    = apply_filters( 'woocommerce_product_tabs', array() );
        $tabs    = apply_filters( 'appmaker_wc_product_tabs', $tabs);

        foreach ( $tabs as $key => $tab ) {
            if ( $key == 'custom_tab' ) {
                $content = APPMAKER_WC_REST_Products_Controller::return_data( $tab['callback'], array( $key, $tab ) );
                $content='<head>'.
                          ' <style>#size-chart {clear:both; margin:10px 0; width:100%}#size-chart tr th{font-weight:bold;}'.
                    '#size-chart tr td,#size-chart tr th{color:#000;padding:8px; text-align:left;}'.
                    '.remodal p{color:#000;}'.
                    'h2#modal1Title,h3#modal1Title{color:#000;}'.
                    '#size-chart tr:nth-child(odd){background:#ebe9eb;}'.
                    '.remodal{padding:35px;}'.'</style>'.'</head>'.$content;
                $return[ $key ] = array(
                    'type'       => 'menu',
                    'expandable' => isset( $tab['expandable'] ) ? $tab['expandable'] && true : true,
                    'expanded'   => isset( $tab['expanded'] ) ? $tab['expanded'] && true : false,
                    'title'      => APPMAKER_WC_REST_Products_Controller::decode_html( apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ) ),
                    'content'    => $content,
                    'action'     => array(
                        'type'   => 'OPEN_IN_WEB_VIEW',
                        'params' => array(
                            'html'  => $content,
                            'title' => APPMAKER_WC_REST_Products_Controller::decode_html( apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ) ),
                        ),
                    ),
                );
            }

            }
            return $return;
    }
}
new APPMAKER_WC_Product_Size_Chart();