<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_Vendors {

	public function __construct() {
		add_filter( 'appmaker_wc_product_widgets', array( $this, 'add_vendor_name' ), 10, 3 );
	}

	/**
	 * @param $return
	 * @param WC_Product $product
	 * @param $data
	 *
	 * @return mixed
	 */
	public function add_vendor_name( $return, $product, $data ){
		// Add sold by to product loop before add to cart
		if( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {

			$vendor_id     = WCV_Vendors::get_vendor_from_product( $product->get_id() );
			$sold_by_label = WC_Vendors::$pv_options->get_option( 'sold_by_label' );
			$sold_by = WCV_Vendors::is_vendor( $vendor_id ) ?  WCV_Vendors::get_vendor_sold_by( $vendor_id ) : get_bloginfo( 'name' );

			$return['vendor'] = array(
				'type'  => 'menu',
				'title' => apply_filters('wcvendors_sold_by_in_loop', $sold_by_label ).' '.$sold_by,

				'action' => array(
					'type'   => 'LIST_PRODUCT',
					'params' => array(
						'author'  => $vendor_id,
						'title' => $sold_by
					),
				)
			);
		}
		return $return;
	}
}

new APPMAKER_WC_Vendors();
