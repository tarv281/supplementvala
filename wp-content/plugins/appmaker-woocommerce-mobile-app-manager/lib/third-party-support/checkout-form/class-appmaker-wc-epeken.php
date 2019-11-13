<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_Epeken {
	public static $function_kecamatan;
	public static $function_kabupaten;

	public static function className() {
		return 'APPMAKER_WC_Epeken';
	}

	public static function init() {
		add_filter( 'appmaker_wc_checkout_fields', array( self::className(), 'indonesia_field' ), 10, 2 );
		add_filter( 'appmaker_wc_dependency_billing_address_2', array(
			self::className(),
			'address_2_dependency',
		), 10, 2 );
		add_filter( 'appmaker_wc_dependency_shipping_address_2', array(
			self::className(),
			'address_2_dependency',
		), 10, 2 );

		if ( function_exists( 'epeken_get_list_of_kecamatan' ) ) {
			self::$function_kabupaten = 'epeken_get_list_of_kota_kabupaten';
			self::$function_kecamatan = 'epeken_get_list_of_kecamatan';
		} else {
			self::$function_kabupaten = 'get_list_of_kota_kabupaten';
			self::$function_kecamatan = 'get_list_of_kecamatan';
		}
	}

	public static function address_2_dependency( $dependency, $key ) {
		if ( 'billing_address_2' === $key ) {
			$dependency = array( 'on' => 'billing_city' );
		} elseif ( 'shipping_address_2' === $key ) {
			$dependency = array( 'on' => 'shipping_city' );
		}
		return $dependency;
	}

	/**
	 * @param $fields
	 * @param $section
	 *
	 * @return array|mixed
	 * @internal param array $args
	 *
	 */
	public static function indonesia_field( $fields, $section ) {
		if ( 'billing' === $section || 'shipping' === $section ) {
			$fields[ $section . '_address_2' ]['type'] = 'dependent-select';
			$fields[ $section . '_address_2' ]['options'] = self::indonesia_country_override();
			$fields[ $section . '_address_2' ]['dependent'] = true;
		}

		return $fields;
	}

	public static function indonesia_country_override() {
		$countries = call_user_func( self::$function_kabupaten );
		$return    = array();
		foreach ( $countries as $key => $country ) {
			$return[ $key ] = array(
				'items' => array(),
			);
			$states         = call_user_func( self::$function_kecamatan, $key );
			if ( is_array( $states ) ) {
				foreach ( $states as $key1 => $state ) {
					$return[ $key ]['items'][ $key1 ] = $state;
				}
			}
		}

		return $return;
	}
}
