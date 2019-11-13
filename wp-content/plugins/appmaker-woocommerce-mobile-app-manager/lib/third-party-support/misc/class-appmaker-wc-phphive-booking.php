<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_booking_and_appointments
{
    public $product;

    public function __construct()
    {
        add_filter('appmaker_wc_product_fields', array($this, 'add_booking_calendar'), 2, 2);
        add_filter( 'appmaker_wc_cart_items_response',array($this,'show_booking_date_cart'),2,1 );
        add_filter("woocommerce_rest_prepare_shop_order", array($this,'show_booking_date_order'),2,3);
        add_filter( 'appmaker_wc_add_to_cart_validate', array( $this, 'check_date_field' ), 2, 2 );
    }

    public function show_booking_date_order($response,$post,$request){
        //print_r($response->data);exit;
        global $wpdb;
        foreach($response->data['line_items'] as $key => $item){
            $id=$item['id'];
            $query = "SELECT meta_key,meta_value,order_item_id
           FROM {$wpdb->prefix}woocommerce_order_itemmeta where meta_key='from' AND order_item_id=$id";
            $booked = $wpdb->get_results( $query, OBJECT );
            foreach($booked as $items => $item_value){
                $date = ph_maybe_unserialize($item_value->meta_value);
                if(!empty($date)) {
                    //$date= str_replace(' GMT 0530 (IST)','',$date);
                    preg_match("/([a-zA-z]{3}[\s][\d]{2}[\s][\d]{4}[\s][\d\:]+)[\s](.*)/i", $date, $new_date);
                    $new_date = $new_date[1];
                    $timezone = $new_date[2];
                    $date = new DateTime($new_date, new DateTimeZone($timezone));
                    $date = $date->format('d-m-Y');
                    $response->data['line_items'][$key]['name']= $response->data['line_items'][$key]['name'].' - '.$date;
                }

            }
        }

       return $response;
    }

    public function show_booking_date_cart($return){
       /* global $wpdb;
        //Query for getting booked date
        $query = "SELECT meta_key , meta_value , order_item_id
			FROM {$wpdb->prefix}woocommerce_order_itemmeta where meta_key='From'";
        $booked = $wpdb->get_results( $query, OBJECT );print_r($booked);*/

       foreach($return['products'] as $key =>$field){
          $date = $field['phive_book_from_date'];
           if(!empty($date)) {
               //$date= str_replace(' GMT 0530 (IST)','',$date);
               preg_match("/([a-zA-z]{3}[\s][\d]{2}[\s][\d]{4}[\s][\d\:]+)[\s](.*)/i", $date, $new_date);
               $new_date = $new_date[1];
               $timezone = $new_date[2];
               $date = new DateTime($new_date, new DateTimeZone($timezone));
               $date = $date->format('d-m-Y');
               $return['products'][$key]['variation_string']="Booking Date " . ' : ' . $date . ' ';
           }

       }
        return $return;
    }

    public function check_date_field( $return, $params )
    {
        $date = $params['phive_book_from_date'];
        if (!empty($date)) {
            //$date= str_replace(' GMT 0530 (IST)','',$date);
            preg_match("/([a-zA-z]{3}[\s][\d]{2}[\s][\d]{4}[\s][\d\:]+)[\s](.*)/i", $date, $new_date);
            $new_date = $new_date[1];
            $timezone = $new_date[2];
            $date = new DateTime($new_date, new DateTimeZone($timezone));
            $date = $date->format('d-m-Y');
            $product_id = $params['product_id'];

            $booked=$this->is_booked($product_id,$date);
            if($booked==1){
                return new WP_Error( 'Booking full', __( 'This date is not available.Please choose another date', 'appmaker-woocommerce-mobile-app-manager' ) );
            }
        }
        return $return;
    }

    public function is_booked($product_id,$date){
        global $wpdb;
        // query for getting booked dates
        $query = "SELECT meta_key , meta_value , order_item_id
			FROM {$wpdb->prefix}woocommerce_order_itemmeta AS t1
			WHERE t1.order_item_id
			IN (
				SELECT order_item_id
				FROM {$wpdb->prefix}woocommerce_order_itemmeta
				WHERE	meta_key = '_product_id'
				AND	meta_value = $product_id
			)AND ( 
				meta_key = 'From'
				OR meta_key = 'To'
				OR meta_key = 'canceled'
				OR meta_key = 'person_as_booking'
				OR meta_key = 'Number of persons'
				OR meta_key = 'Buffer_before_From'
				OR meta_key = 'Buffer_before_To'
				OR meta_key = 'Buffer_after_From'
				OR meta_key = 'Buffer_after_To'
			)
			
			ORDER BY	t1.order_item_id DESC LIMIT 0,1000";
        $booked = $wpdb->get_results( $query, OBJECT );
        //Query for getting freezed dates
        $query_post = "SELECT meta_key, meta_value, post_id as order_item_id
			FROM {$wpdb->prefix}postmeta AS t1
			WHERE t1.post_id
			IN (
				SELECT post_id
				FROM  {$wpdb->prefix}postmeta
				WHERE meta_key = '_product_id'
				AND meta_value = $product_id
			)AND ( 
				meta_key = 'From'
				OR meta_key = 'To'
				OR meta_key = 'person_as_booking'
				OR meta_key = 'Number of persons'
				OR meta_key = 'Buffer_before_From'
				OR meta_key = 'Buffer_before_To'
				OR meta_key = 'Buffer_after_From'
				OR meta_key = 'Buffer_after_To'
			)
			AND t1.post_id 
			NOT IN (select post_id
				from {$wpdb->prefix}postmeta
				where meta_key='ph_canceled'
				AND meta_value = '1')
			ORDER BY  t1.post_id DESC LIMIT 0,300";
        $freezed = $wpdb->get_results( $query_post, OBJECT );
        $booked_array = array_merge( $booked, $freezed );
        $processed = array();
        $booked_date_time = array();
        $canceled = array();

        foreach ($booked_array as $key => $value) {
            if( $value->meta_key == 'From' ){
                $from_date = substr($value->meta_value, 0, 10);
                $processed[ $value->order_item_id ]['from'] = ph_maybe_unserialize($value->meta_value);
            }
            if( $value->meta_key == 'Number of persons' ){
                $number_of_person = substr($value->meta_value, 0, 10);
                $processed[ $value->order_item_id ]['Number of persons'] = $value->meta_value;
            }
            if( $value->meta_key == 'person_as_booking' ){
                $person_as_booking = substr($value->meta_value, 0, 10);
                $processed[ $value->order_item_id ]['person_as_booking'] = $value->meta_value;
            }
            if( $value->meta_key=='To' ){
                $processed[ $value->order_item_id ]['to'] = ph_maybe_unserialize($value->meta_value);
            }
            if( $value->meta_key=='Buffer_before_From' ){
                $processed[ $value->order_item_id ]['Buffer_before_From'] = $value->meta_value;
            }
            if( $value->meta_key=='Buffer_before_To' ){
                $processed[ $value->order_item_id ]['Buffer_before_To'] = $value->meta_value;
            }
            if( $value->meta_key=='Buffer_after_From' ){
                $processed[ $value->order_item_id ]['Buffer_after_From'] = $value->meta_value;
            }
            if( $value->meta_key=='Buffer_after_To' ){
                $processed[ $value->order_item_id ]['Buffer_after_To'] = $value->meta_value;
            }
            if( $value->meta_key == 'canceled' && $value->meta_value == 'yes' ){
                $canceled[$value->order_item_id] = '';
            }
        }
        $eliminated_cancelled =  array_diff_key($processed, $canceled);

        //if TO is missing, concider FROM as TO
        foreach ($eliminated_cancelled as $key => &$value) {
            if( empty($value['to']) && !empty($value['from'])){ // in the case of buffer, index 'from' wil be empty
                $value['to'] = $value['from'];
            }
        }
        $found = 0;
        foreach ( $eliminated_cancelled as $order_item_id => $booked_detail ) {

            preg_match("/([a-zA-z]{3}[\s][\d]{2}[\s][\d]{4}[\s][\d\:]+)[\s](.*)/i", $booked_detail['from'], $new_date);
            if(!empty($new_date)) {
                $new_date = $new_date[1];
                $timezone = $new_date[2];
                $book_date_from = new DateTime($new_date, new DateTimeZone($timezone));
                $book_date_from = $book_date_from->format('d-m-Y');
            }

            preg_match("/([a-zA-z]{3}[\s][\d]{2}[\s][\d]{4}[\s][\d\:]+)[\s](.*)/i", $booked_detail['to'], $new_date);
           if(!empty($new_date)) {
               $new_date = $new_date[1];
               $timezone = $new_date[2];
               $book_date_to = new DateTime($new_date, new DateTimeZone($timezone));
               $book_date_to = $book_date_to->format('d-m-Y');
           }
             $date=strtotime($date);
            //if date in between booked from and to
            if((isset($booked_detail['from']) && isset($booked_detail['to'])
                    && $date >= strtotime($book_date_from) && $date <= strtotime($book_date_to) ) ||  ( isset($booked_detail['Buffer_before_From']) && isset($booked_detail['Buffer_before_To'])
                    && $date >= strtotime($booked_detail['Buffer_before_From']) && $date <= strtotime($booked_detail['Buffer_before_To'])) || ( isset($booked_detail['Buffer_after_From']) && isset($booked_detail['Buffer_after_To'])
                    && $date >= strtotime($booked_detail['Buffer_after_From']) && $date <= strtotime($booked_detail['Buffer_after_To']))){
                if(!empty($booked_detail['person_as_booking'])){
                    $person_as_booking = maybe_unserialize( $booked_detail['person_as_booking'] );
                    if( !empty($person_as_booking[0]) && ($person_as_booking[0] == 'yes') && isset($booked_detail['Number of persons']) ){
                        $found += $booked_detail['Number of persons'];
                    }
                    else{
                        $found++;
                    }
                }else{
                    $found++;
                }
                $allowd_per_slot 			= get_post_meta( $product_id, '_phive_book_allowed_per_slot', 1);
                $allowd_per_slot 			= !empty($allowd_per_slot)?$allowd_per_slot:'1';
                //if reached maximum allowed booking.
                if( $found >= $allowd_per_slot ){
                    return true;
                }
            }

        }
        return false;


    }

    public function add_booking_calendar($fields,$product){
        $min_date=date('d-m-Y');
        $field = array();

        if ($product->is_type('phive_booking')) {

            $field['phive_book_from_date'] = array(
                'type' => 'datepicker',
                'label' =>__('Please pick a booking period', 'bookings-and-appointments-for-woocommerce'),
                'required' => true,
                'minDate'=>$min_date,
                'default' =>$min_date,
                'placeholder'=>$min_date,
            );

        }

        if(!empty($fields)) {
            $field = APPMAKER_WC_Dynamic_form::get_fields($field, 'product');
            $fields['items'] = array_merge($fields['items'], $field['items']);
            $fields['order'] = array_merge($fields['order'], $field['order']);
            $fields['dependencies'] = array_merge($fields['dependencies'], $field['dependencies']);
            return $fields;
        }else{
            $fields = APPMAKER_WC_Dynamic_form::get_fields($field, 'product');
            return $fields;
        }

    }
}
new APPMAKER_WC_booking_and_appointments();