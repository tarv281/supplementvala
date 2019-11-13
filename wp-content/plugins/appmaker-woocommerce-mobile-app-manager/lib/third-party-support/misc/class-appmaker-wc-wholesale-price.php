<?php
/**
 * Created by IntelliJ IDEA.
 * User: shifa
 * Date: 22/6/18
 * Time: 12:47 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_wholesale_price{

    public function __construct() {
        add_filter( 'appmaker_wc_product_data', array( $this, 'product_wholesale_price' ), 2, 2 );
    }


    public function product_wholesale_price( $data, $product )
    {

        $user_wholesale_role = null;
        if (is_null($user_wholesale_role))
            $user_wholesale_role = WWP_Wholesale_Roles::getInstance()->getUserWholesaleRole();

        if (!empty($user_wholesale_role) && !empty($data['price'])) {

            $price_arr = WWP_Wholesale_Prices::get_product_wholesale_price_on_shop_v2(WWP_Helper_Functions::wwp_get_product_id($product), $user_wholesale_role);

            $raw_wholesale_price = $price_arr['wholesale_price'];
            if (!empty($raw_wholesale_price)){

                $data['price'] = $raw_wholesale_price;
            $data['price_display'] = APPMAKER_WC_Helper::get_display_price($raw_wholesale_price);
        }

    }

        return $data;
    }


}
new APPMAKER_WC_wholesale_price();
