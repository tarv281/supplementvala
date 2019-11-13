<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
class APPMAKER_WC_Order_Delivery_Date
{
    public function __construct()
    {
       // add_action('appmaker_wc_before_checkout',array($this, 'checkout_fields_response'));
       add_filter('appmaker_wc_checkout_fields', array( $this,'checkout_fields_response_fix'), 10, 2 );
        add_filter('appmaker_wc_validate_checkout',array($this,'Validation_checkout'));
    }

    public function  Validation_checkout($return){
        $date = $return['e_deliverydate'];
        preg_match("/([a-zA-z]{3}[\s][\d]{2}[\s][\d]{4}[\s][\d\:]+)[\s](.*)/i",$date,$new_date);
        $new_date = $new_date[1];
        $timezone = $new_date[2];
        $date = new DateTime($new_date, new DateTimeZone($timezone));
        $timestamp = $date->format('d-m-Y');
        $date=strtotime($timestamp);
        if($date == strtotime(date('d-m-Y'))){
            $timezone = date_default_timezone_get();
            date_default_timezone_set($timezone);
            $current_time = current_time('h:i A');
            $current_time = strtotime($current_time);
            if (strtotime($return['e_deliverydate']) <= $current_time) {
                return new wp_error('invalid_time_slot', "Please select a valid slot");
            }
        }

        return $return ;

    }
    public function get_delivery_intervals(){

        if ( get_option( 'orddd_enable_time_slot' ) == 'on' ) {
            $time_slot_str = get_option( 'orddd_delivery_time_slot_log' );
            $time_slots = json_decode( $time_slot_str, true );
            //$result = array ( __( "Select a time slot", "order-delivery-date" ) );
            $time_format = get_option( 'orddd_delivery_time_format' );
            $time_format_to_show = ( $time_format == '1' ) ? 'h:i A' : 'H:i';
            $options=array();
            if ( count( $time_slots ) >0 ) {
                if ($time_slots == 'null') {
                    $time_slots = array();
                }
                foreach ($time_slots as $k => $v) {
                    $from_time = $v['fh'].":".$v['fm'];
                    $ft =  date( $time_format_to_show, strtotime( $from_time ) );
                    if ( $v['th'] != 00 ){
                        $to_time = $v['th'].":".$v['tm'];
                        $tt = date( $time_format_to_show, strtotime( $to_time ) );
                        $key = $ft." - ".$tt;
                    } else {
                        $key = $ft;
                    }
                    $options[$key]=$key;
                    if(!empty($v['additional_charges'])){
                        $price = APPMAKER_WC_Helper::get_display_price($v['additional_charges']);
                       $options[$key]=$key.'('.$price.')';
                    }
                }
            }
          return $options;

        }else {
            $from = get_option('orddd_delivery_from_hours');
            $from = $from . date(':i');
            $from = (strtotime($from));
            $to = get_option('orddd_delivery_to_hours');
            $to = $to . date(':i');
            $to = strtotime($to);
            $return = array();
            do {
                $time = date('h:i A', $from);
                $return[$time] = $time;
                $from = strtotime("+15 minutes", $from);
            } while ($from <= $to);
            return $return;
        }
      
    }
        
    public function checkout_fields_response_fix( $return, $section )
    {
        $additional_fields = array();
       // $date = date('d-m-y');
        $min_date=date('d-m-Y', strtotime( ' + 1 days'));
        if( 'on' == get_option( 'orddd_enable_same_day_delivery' ) ) {
            $min_date=date('d-m-Y');        
         }         

       // $result = array ( __( "Select a time slot", "order-delivery-date" ) );
        if ( $section === 'order' ) {
            $delivery_enabled = orddd_common::orddd_is_delivery_enabled();
            $is_delivery_enabled = 'yes';
            if ($delivery_enabled == 'no') {
                $is_delivery_enabled = 'no';
            }
            if ($is_delivery_enabled == 'yes') {
                $date_field_label = get_option( 'orddd_delivery_date_field_label' );
                if( '' == $date_field_label ) {
                    $date_field_label = 'Delivery Date';
                }
                $additional_fields['e_deliverydate'] = array(
                    'type' => 'datepicker',
                    'label' => __( $date_field_label, 'order-delivery-date' ),
                    'required' => true,
                    'minDate' => $min_date,
                    'placeholder' => 'Select date',
                    'default' => $min_date,

                );
                $validate_wpefield = false;
    				if (  get_option( 'orddd_time_slot_mandatory' ) == 'checked' ) {
    					$validate_wpefield = true;
                    }
                    if( is_cart() ) {
                        $custom_attributes = array( 'disabled'=>'disabled', 'style'=>'cursor:not-allowed !important;max-width:300px;' );
                    } else {
                        $custom_attributes = array( 'disabled'=>'disabled', 'style'=>'cursor:not-allowed !important;' );
                    }
                $time_field_label = get_option( 'orddd_delivery_timeslot_field_label' );
                if( '' == $time_field_label ) {
                    $time_field_label = 'Delivery Time';
                }
                $additional_fields['time_slot'] = array(
                    'type' => 'select',
                    'label' => __( $time_field_label, 'order-delivery-date' ),
                    'required' =>$validate_wpefield,
                    'placeholder' => 'select time',
                    'options'=> $this->get_delivery_intervals(),
                    'custom_attributes' => $custom_attributes,
                );
            }
        }
        return array_merge( $additional_fields, $return );
    }
}
new APPMAKER_WC_Order_Delivery_Date();