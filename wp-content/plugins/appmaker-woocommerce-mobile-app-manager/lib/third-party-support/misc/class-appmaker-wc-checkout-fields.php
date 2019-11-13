<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_checkout_fields {


    public function __construct() {
        add_filter( 'appmaker_wc_checkout_fields', array( $this, 'checkout_fields' ),10, 2 );
    }


    public function checkout_fields( $return,$section) {

        $billing_settings_key      = 'pcfme_billing_settings';
        $shipping_settings_key     = 'pcfme_shipping_settings';
        $pcfme_additional_settings = 'pcfme_additional_settings';



        $billing_fields                = (array) get_option( $billing_settings_key );

        $shipping_fields               = (array) get_option( $shipping_settings_key );

        $additional_fields             = (array) get_option( $pcfme_additional_settings );


        $additional_fields =  array_filter($additional_fields);


        if($section ==='order') {
            $return = array_merge($additional_fields,$return);
        }

       /*  if(!empty($return)){
            foreach($return as $item => $field){
              switch($field['type']){
                  case 'multiselect':
                  case 'pcfmeselect':$return[$item]['type']='select';break;
              }

            }
         }*/
    foreach ($return as $addon => $value) {

        switch ($value['type']) {
            case 'multiselect' :
            case 'pcfmeselect' :
            case 'radio'   :
                $return[$addon]['type'] = 'select';
                break;
        }
        if ($value['type'] == 'heading') {
            unset($return[$addon]);
        } else {

            if (isset($value['options'])) {
                if (is_array($value['options'])) {
                    $tempoptions = $value['options'];
                } else {
                    $tempoptions = explode(',', $value['options']);
                }

            }
            $optionsarray = array();

            if (isset($tempoptions)) {
                foreach ($tempoptions as $val) {

                    $optionsarray[$val] = $val;

                }
            }

            if ($return[$addon]['type'] === 'select') {
                $return[$addon]['options']=array();
                if (!empty($optionsarray))
                    foreach ($optionsarray as $option_key => $option_text) {
                        $return[$addon]['options'][$option_text]=$option_text;
                    }
            }
        }
    }

        return $return;
    }


}

new APPMAKER_WC_checkout_fields();
