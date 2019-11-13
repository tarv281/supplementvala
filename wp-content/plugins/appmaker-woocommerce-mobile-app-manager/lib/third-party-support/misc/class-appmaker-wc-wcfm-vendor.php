<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_WCFM_Vendors {

	public function __construct() {

		add_filter( 'appmaker_wc_product_widgets', array( $this, 'get_vendor_name' ), 10, 3 );
	}

	public function get_vendor_name( $return, $product, $data ){
      
    global $WCFM, $WCFMmp;
       // print_r($return);exit;
       foreach ( $return as $id => $tab ){
          // echo $id;exit;
          if( 'wcfm_product_store_tab' == $id ){ 
            $sold_by_text = $WCFMmp->wcfmmp_vendor->sold_by_label();
            if( $WCFMmp->wcfmmp_vendor->is_vendor_sold_by() ) {
                $product_id = $product->get_id();
                $vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id ); 
                //if( 0 !== $vendor_id) {             
                  $shop_name = html_entity_decode(strip_tags($WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($vendor_id) )));
                  $return[$id]['content'] = $shop_name;
                  $return[$id]['title']   = ! empty( $sold_by_text ) ? $sold_by_text : $return[$id]['title'];                  
               // }
            }
           
          }
       }
		return $return;
	}



}
new APPMAKER_WC_WCFM_Vendors();
