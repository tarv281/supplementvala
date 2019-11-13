<?php
/**
 * Created by IntelliJ IDEA.
 * User: muneef
 * Date: 11/08/17
 * Time: 4:41 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_wcff_custom_fields {


	public function __construct() {
		add_filter( 'appmaker_wc_product_fields', array( $this, 'product_fields' ), 2, 2 );
	}



	public function product_fields( $fields, $product ) {
		$all_fields = apply_filters( 'wcff/load/all_fields', $product->get_id(), 'wccpf' );
		$fields         = array();
		$previous_value = null;
		foreach ( $all_fields as $title => $field_props ) {
			if ( count( $field_props ) > 0 ) {
				foreach ( $field_props as $x  => $field_prop ) {

					if ( ! in_array( $field_prop['type'] ,
						array(
						'text',
						'number',
						'email',
						'textarea',
						'checkbox',
						'radio',
						'select',
						'datepicker',
						'label',
					),true)
					) {
						continue;
					}

					$key = $field_prop['name'];
					$fields[ $key ]['label'] = $field_prop['label'];
					$fields[ $key ]['required'] = $field_prop['required'] === 'yes';

					if ( $previous_value && $field_prop['type'] != 'label' ) {
						$fields[ $key ]['label'] = $previous_value . ".\n \n" . $field_prop['label'];
						$previous_value = null;
					}
					switch ( $field_prop['type'] ) {
						case 'datepicker':
							$fields[ $key ]['type'] = 'datetime';
							break;
						case 'radio':
							$fields[ $key ]['type'] = 'select';
							break;
						case 'label':
							$fields[ $key ]['type'] = 'hidden';
							$previous_value = $previous_value . "\n \n" . $field_prop['message'];
							break;
						default:
							$fields[ $key ]['type'] = $field_prop['type'];
							break;
					}
					if ( $field_prop['type'] === 'select' || $field_prop['type'] === 'radio' ) {
						$options_array = explode( ';',$field_prop['choices'] );

						foreach ( $options_array as $option ) {
							$fields[ $key ]['options'][ $option ] = $option;
						}
					}
				}
			}
		}

		$fields = APPMAKER_WC_Dynamic_form::get_fields( $fields, 'product' );
		return $fields;
	}


}

new APPMAKER_WC_wcff_custom_fields();
