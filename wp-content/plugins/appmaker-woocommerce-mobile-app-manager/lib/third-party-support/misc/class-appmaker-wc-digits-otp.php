<?php
/**
 * Created by IntelliJ IDEA.
 * User: shifa
 * Date: 3/27/19
 * Time: 5:46 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class APPMAKER_WC_digits_otp extends APPMAKER_WC_REST_Controller
{

    protected $namespace = 'appmaker-wc/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'user';


    public function __construct()
    {
        parent::__construct();
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/login_only_with_otp', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'login_only_with_otp' ),
                'permission_callback' => array( $this, 'api_permissions_check' ),
                'args'                => $this->get_login_otp_only_args(),
            ),
        ) );

        add_filter('appmaker_wc_send_otp', array($this, 'send_otp'), 2, 1);
        add_filter( 'appmaker_wc_login_validate',array($this,'login_otp'),2,2);
        add_filter( 'appmaker_wc_registration_validate',array($this,'register_otp'),2,2);
    }

    public function get_login_otp_only_args(){
        $params             = array();
        $params['code'] = array(
            'description'       => __( 'Token Number.', 'appmaker-woocommerce-mobile-app-manager' ),
            'type'              => 'string',
            'required'          =>false,
        );
        return $params;
    }

    public function login_only_with_otp($request){
       // $return = array( 'status' => true );
        $params = $request;
        $digit_tapp = get_option('digit_tapp',1);
//        if($digit_tapp==1) {
//            $code = sanitize_text_field($params['code']);
//            $json = getUserPhoneFromAccountkit($code);
//            $phoneJson = json_decode($json, true);
//            $mob = $phoneJson['phone'];
//            $countrycode = $phoneJson['countrycode'];
//            $phone = $phoneJson['nationalNumber'];
//            $username = str_replace("+","",$countrycode).$phone;
//            $user = get_user_by('login',$username);
//            if(!$user){
//                $defaultuserrole = get_option('defaultuserrole');
//                $username = str_replace("+","",$countrycode).$phone;
//                $username = sanitize_user($username,true);
//                $password = wp_generate_password();
//                $email = $phone;
//                $customer_id = wp_create_user($username, $password,$email);
//                if ( is_wp_error( $customer_id ) ) {
//                    return $customer_id;
//                }
//                update_user_meta($customer_id, 'digits_phone', $mob);
//                update_user_meta($customer_id, 'digt_countrycode', $countrycode);
//                update_user_meta($customer_id, 'digits_phone_no', $phone);
//
//                $cd = array('ID'=>$customer_id,'role' => $defaultuserrole);
//                wp_update_user($cd);
//
//                add_user_meta( $customer_id, '_registered_from_app', 1 );
//
//                if ( isset($phone) ) {
//                    update_user_meta( $customer_id, 'billing_phone', trim( $phone ) );
//                    update_user_meta( $customer_id, 'shipping_phone', trim( $phone) );
//                }
//                update_user_meta( $customer_id, 'appmaker_wc_user_login_from_app', true );
//                do_action( 'appmaker_wc_user_registered', $customer_id, $phone );
//            }
//            $user = get_user_by('login',$username);
//           return  APPMAKER_WC::$api->APPMAKER_WC_REST_User_Controller->set_current_user($user);
//        }else
//            return $return;

        switch($digit_tapp){
            case 1:if(empty($request['code'])){
                      return new WP_Error("invalid_code",__('Something went wrong','appmaker-woocommerce-mobile-app-manager'));
                   }
                   $code = sanitize_text_field($params['code']);
                    $json = getUserPhoneFromAccountkit($code);
                    $phoneJson = json_decode($json, true);
                    $mob = $phoneJson['phone'];
                    $countrycode = $phoneJson['countrycode'];
                    $phone = $phoneJson['nationalNumber'];
                    $username = str_replace("+","",$countrycode).$phone;
                    $user = get_user_by('login',$username);
                   return  $this->handle_user($user,$phone,$countrycode,$mob);
                    break;

            case 10: if (empty($params['phone']) || !preg_match("/^[0-9]+$/i", $params['phone'])) {
                        return new WP_Error("invalid_phone",__('Invalid phone number','appmaker-woocommerce-mobile-app-manager'));
                     }
                     else if (empty($params['otp']) || !preg_match("/^[0-9]+$/i", $params['otp'])) {
                         return new WP_Error("invalid_otp",__('Please enter valid OTP','appmaker-woocommerce-mobile-app-manager'));
                     }else
                      {
                        $mobile = trim($params['phone']);
                        $otp = trim($params['otp']);
                        $del = false;
                        $countrycode = getCountry();
                        $status = verifyOTP($countrycode,$mobile,$otp,$del);
                        //$ret['status'] = $status;
                        if ($status) {
                            $user = get_user_by( 'login', $request['phone'] );
                           return $this->handle_user($user,$mobile,$countrycode,0);
                        } else {
                            return new WP_Error("invalid_otp",__('Please enter valid OTP','appmaker-woocommerce-mobile-app-manager'));
                        }
                     }
                     break;
        }
           return new WP_Error("configuration_digits",__('Please use Account kit or Nexmo SMS gateway in digits plugin','appmaker-woocommerce-mobile-app-manager'));
    }

    public function handle_user($user,$phone,$countrycode,$mob){
        $username = str_replace("+","",$countrycode).$phone;
        if($mob == 0){
            $mob = $phone;
            $username = $phone;
        }
        if(!$user){
               $defaultuserrole = get_option('defaultuserrole');
               $username = sanitize_user($username,true);
               $password = wp_generate_password();
                $email = $phone;
               $customer_id = wp_create_user($username, $password,$email);
               if ( is_wp_error( $customer_id ) ) {
                    return $customer_id;
                }
               update_user_meta($customer_id, 'digits_phone', $mob);
               update_user_meta($customer_id, 'digt_countrycode', $countrycode);
               update_user_meta($customer_id, 'digits_phone_no', $phone);

               $cd = array('ID'=>$customer_id,'role' => $defaultuserrole);
               wp_update_user($cd);
               add_user_meta( $customer_id, '_registered_from_app', 1 );

              if ( isset($phone) ) {
                   update_user_meta( $customer_id, 'billing_phone', trim( $phone ) );
                   update_user_meta( $customer_id, 'shipping_phone', trim( $phone) );
               }
               update_user_meta( $customer_id, 'appmaker_wc_user_login_from_app', true );
               do_action( 'appmaker_wc_user_registered', $customer_id, $phone );
           }
            $user = get_user_by('login',$username);
          return  APPMAKER_WC::$api->APPMAKER_WC_REST_User_Controller->set_current_user($user);

    }

    public function send_otp($request)
    {
        $digit_tapp = get_option('digit_tapp',1);
        $code = dig_get_otp();
        $countrycode = getCountry();
        $mobile = $request['phone'];
        $result = digit_send_otp($digit_tapp, $countrycode, $mobile, $code, true);
        $result = (array) json_decode($result);

        if($digit_tapp!=13) {

                if (OTPexists($countrycode, $mobile)) {
                    echo "1";
                    die();
                }

                if (!$result) {
                    echo "0";
                    die();
                }


                $mobileVerificationCode = md5($code);

                global $wpdb;
                $table_name = $wpdb->prefix . "digits_mobile_otp";

                $db = $wpdb->replace($table_name, array(
                    'countrycode' => $countrycode,
                    'mobileno' => $mobile,
                    'otp' => $mobileVerificationCode,
                    'time' => date("Y-m-d H:i:s",strtotime("now"))
                ), array(
                        '%d',
                        '%s',
                        '%s',
                        '%s')
                );

                if(!$db){
                    echo "0";
                    die();
                }

            }

        return $result;
    }


    public function login_otp($return,$request){

        $params = $request;
        $ret=array();
        if (empty($params['phone']) || !preg_match("/^[0-9]+$/i", $params['phone'])) {
            return new WP_Error("invalid_phone", 'Invalid phone number');
        }
        else if (empty($params['otp']) || !preg_match("/^[0-9]+$/i", $params['otp'])) {
            return new WP_Error("invalid_otp",'Please enter valid OTP');
        }else {
            $mobile = trim($params['phone']);
            $otp = trim($params['otp']);
            $del = false;
            $countrycode = getCountry();
            $status = verifyOTP($countrycode,$mobile,$otp,$del);
            //$ret['status'] = $status;
            if ($status) {
                $user = get_user_by( 'login', $request['phone'] );
                if (!$user) {
                    return new  WP_Error("invalid_mobile_login", 'Please enter a registered phone number');
                }
                return $return;
            } else {
                return new WP_Error("invalid_otp",'Please enter valid OTP');
            }
        }


    }

    public function register_otp($return,$request)
    {
        $params=$request;
        $_POST['digit_ac_otp']=sanitize_text_field($params['otp']);
        $_POST['code']='0';
        $_POST['mobile/email']=$params['phone'];
        $_POST['digt_countrycode']=getCountry();
        $ret=array();
        if (empty($params['phone']) || !preg_match("/^[0-9]+$/i", $params['phone']) || strlen(trim($params['phone'])) != 10) {
            return new WP_Error("invalid_phone", 'Invalid phone number');
        } else if (username_exists($params['phone'])) {
            return new WP_Error("registered_phone", 'The mobile number is already registered');
        } else if (empty($params['otp']) || !preg_match("/^[0-9]+$/i", $params['otp'])) {
            return new WP_Error("invalid_otp",'Please enter valid OTP');
        }
        else if (empty($params['password']) || strlen($params['password']) < 6) {
            return new WP_Error("invalid_password", 'Please enter valid password');
        } else if (empty($params['email'])) {
            return new WP_Error("Invalid_email", 'Please enter valid email.');
        } else if (email_exists($params['email'])) {
            return new WP_Error("registered_mail",'The email is already registered');
        }
        else {
            $mobile = trim($params['phone']);
            $otp = trim($params['otp']);
            $del = false;
            $countrycode = getCountry();
            $status = verifyOTP($countrycode,$mobile,$otp,$del);
            // $ret['status'] = $status;
            if ($status) {
                return $return;
            } else {
                return new WP_Error("invalid_otp",'Please enter valid OTP');
            }
        }
    }

}
new APPMAKER_WC_digits_otp();