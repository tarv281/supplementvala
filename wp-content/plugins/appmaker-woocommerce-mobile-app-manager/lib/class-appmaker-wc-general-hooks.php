<?php


class APPMAKER_WC_General_hooks {
	/**
	 * Holds the values to be used in the fields callbacks
	 *
	 * @var object
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		require_once( APPMAKER_WC::$root . '/lib/vendor/fcm/class-appmaker-wc-fcm-helper.php' );

		if ( ! empty( $_GET['from_app'] ) && ! empty( $_GET['key'] ) || isset($_COOKIE['from_app_set']) ) {
            add_action( 'wp_head', array( $this, 'appmaker_wc_hide_header_and_footer' ) );
        }
        if( ! empty( $_GET['app_mailchimp'] ) ) {
            add_action( 'wp_head', array( $this, 'appmaker_wc_style_mailchimp' ) );
        }

        if ( !empty( $_GET['from_app_wallet'] ) || isset($_COOKIE['from_app_wallet_set'])){
            add_action('wp_head',array($this,'appmaker_wc_hxp_wallet_hide'));
        }

		if ( ! empty( $_GET['payment_from_app'] ) ) {

			add_action( 'wp_head', array( $this, 'hook_stripe_enable_headers' ) );
			add_action( 'wp_footer', array( $this, 'hook_payment_footer' ) );
		}
        $order_statuses = wc_get_order_statuses();
		foreach($order_statuses as $status_id => $status){
            $order_status = str_replace("wc-","",$status_id);
            add_action( 'woocommerce_order_status_'.$order_status, array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
        }

//		add_action( 'woocommerce_order_status_completed', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
//		add_action( 'woocommerce_order_status_processing', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
//		add_action( 'woocommerce_order_status_refunded', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
//		add_action( 'woocommerce_order_status_cancelled', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
//		add_action( 'woocommerce_order_status_on-hold', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
//		add_action( 'woocommerce_order_status_failed', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );
//		add_action( 'woocommerce_order_status_pending', array( $this, 'appmaker_wc_order_status_changed' ), 10, 1 );

        add_action( 'woocommerce_loaded', array( $this, 'load_persistent_cart' ), 10, 1 );
        $this->options = get_option('appmaker_wc_settings');


        if (empty( $this->options['project_id']) && !(isset($_POST['appmaker_wc_settings']) && !empty($_POST['appmaker_wc_settings']['project_id'])) && !(isset($_GET['page']) && $_GET['page'] == 'appmaker-wc-admin')) {
            add_action( 'admin_notices', array( $this, 'show_settings_admin_message' ) );
        }
        //woocommerce all in one currency converter
        if ( class_exists( 'WooCommerce_All_in_One_Currency_Converter_Frontend' ) ) {
            add_action('init',array($this,'Currency_converter') , 0);
        }
        add_filter( 'locale', array($this,'set_my_locale'),1,1);
        add_action('woocommerce_update_order', array($this,'appmaker_order_details'), 2, 1);

	}

    /**
     * @param $order_id
     */
   public function appmaker_order_details($order_id )
    {
        if (!$order_id) {
            return;
        } else if((isset( $_GET['from_app'] ) && !empty($_GET['from_app']) ) || isset($_COOKIE['from_app_set']) ) {
            $order = wc_get_order($order_id);
            if (is_a($order, 'WC_Order')) {
               if (!get_post_meta($order_id, 'from_app')) {
                   $order->add_order_note(__('Order from App', 'appmaker-woocommerce-mobile-app-manager'));
                   add_post_meta($order_id, 'from_app', true);
               }
                $key = method_exists($order, 'get_order_key') ? $order->get_order_key() : $order->order_key;
                WC()->session->set('last_order_key', $key);
            }
        }
    }

   public  function set_my_locale( $lang ) {

       if(APPMAKER_WC::$api->get_settings( 'locale_code',false )){
           $locale_code = array(
                    'en' => 'en_US',
                    'fa' => 'fa_IR',
                    'de' => 'de_DE',
                    'fr' => 'fr_FR',
                    'es' => 'es_ES',
                    'it' => 'it_IT'

               );
        if (isset($_GET['language'])) {
            $lang = $_GET['language'];
            if (array_key_exists($_GET['language'], $locale_code)) {
                $lang = $locale_code[$_GET['language']];
            }
         }
           return $lang;

        }else
            return $lang;
    }

    public function Currency_converter(){
	    if(!empty($_REQUEST['currency'])) {
            $_POST['wcaiocc_change_currency_code'] = $_REQUEST['currency'];
        }
    }

    function show_settings_admin_message() {
        ?>
        <div class="notice notice-error" style="display: flex;">
                <a href="https://appmaker.xyz/woocommerce?utm_source=woocommerce-plugin&utm_medium=admin-notice&utm_campaign=after-plugin-install" class="logo" style="margin: auto;"><img src="https://storage.googleapis.com/stateless-appmaker-pages-wp/2019/04/10b81502-mask-group-141.png" alt="Appmaker.xyz"/></a>
                <div style="flex-grow: 1; margin: 15px 15px;">
                    <h4 style="margin: 0;">Configure app to continue</h4>
                    <p><?php echo __( 'Ouch!😓 It appears that your eCommerce App is not configured correctly. Kindly configure with correct API details.', 'appmaker-woocommerce-mobile-app-manager' ); ?></p>
                </div>
                <a href="admin.php?page=appmaker-wc-admin" class="button button-primary" style="margin: auto 15px; background-color: #f16334; border-color: #f16334; text-shadow: none; box-shadow: none;">Take me there !</a>
        </div>
        <?php
    }
	/*public function appmaker_wc_hide_header_and_footer(){
	    $output = '<script type="text/javascript">';
	    $output .= "\njQuery(function() {";
	    $output .= "    jQuery('#wrapper').html(jQuery('#main').html())\n";
        $output .= "    jQuery('body').css('margin-left','20px')\n";
        $output .= "    jQuery('body').css('margin-bottom','300px')\n";
        $output .= "});\n";
        $output .= "</script>\n";
        echo $output;
    }*/


    public function appmaker_wc_hide_header_and_footer(){
        
        $output       =APPMAKER_WC_General_Helper::get_custom_html();
        $custom_style = APPMAKER_WC::$api->get_settings( 'custom_webview_head', $output );
        echo $custom_style;
    }

    public function appmaker_wc_hxp_wallet_hide()
    {
        $output = '<script type="text/javascript">';
        $output .= "\nwindow.onload = function(e){";
        $output .= "      if (jQuery('.wrap').length) { \n";
        $output .= "      jQuery('.wrap').html(jQuery('.col-12').html())\n";
        $output .= "      jQuery('.woocommerce-button').hide()\n";
        $output .= "    } else if (jQuery('.woocommerce-MyAccount-content').length) { \n";
        $output .= "     jQuery('.woocommerce-MyAccount-content').parents().siblings().hide() \n";
        $output .= "     jQuery('.woocommerce-MyAccount-navigation').hide() \n";
        $output .= "     jQuery('html').css('background-color', 'white')\n";
        $output .= "     setTimeout(function(){";
        $output .= "            jQuery('.ACCW-BUTTON-WRAPER').hide() \n";
        $output .= "    },3000);\n";
        $output .= "    } \n";
        $output .= "    jQuery('body').css('margin-left','0px')\n";
        $output .= "    jQuery('body').css('margin-bottom','0px')\n";
        $output .= "};\n";
        $output .= "</script>\n";
        $output .='<style>.whb-sticky-header.whb-clone.whb-main-header.whb-sticked{box-shadow: none !important;}';
        $output .= '.large-3,.my-account-header,.woocommerce-breadcrumb, header, footer, #footer { display: none; }';
        $output .= '</style>';
        echo $output;
    }

    public function appmaker_wc_style_mailchimp()
    {           
        $output  = '<style>.mc4wp-form-fields input{width:100%;display:block;}';
        $output .= '.mc4wp-form-fields input[type=submit] {  color: white;background-color: #fb5c06;border: none;border-radius: 2px;padding: 15px 0;margin-top: 35px;text-transform: uppercase;font-size: 18px; }';
        $output .= '.woocommerce-breadcrumb,.site-header,.site-footer,.mf-navigation-mobile,.navigation-list{display:none !important;}';       
        $output .= '</style>';
        echo $output;
    }
	/**
	 * Order change callback function
	 *
	 * @param int $order_id order id.
	 */
	public function appmaker_wc_order_status_changed( $order_id ) {
	 	$fcm_key = APPMAKER_WC::$api->get_settings( 'fcm_server_key' );
		$order   = new WC_Order( $order_id );
		if ( ! empty( $fcm_key ) && APPMAKER_WC::$api->get_settings( 'enable_order_push', true ) ) {
			$fcm     = new Appmaker_WC_FCM_Helper( $fcm_key );
			$user_id = self::get_property($order,'user_id');
            $show_order_number = APPMAKER_WC::$api->get_settings( 'show_order_number', false );
			if ( class_exists( 'WC_Seq_Order_Number' ) ) {
				$display_order_id = self::get_property($order,'order_number');
			}  else if($show_order_number) {
                $display_order_id = $order->get_order_number();
            } else {
                $display_order_id = self::get_id($order);
            }

			if ( ! empty( $user_id ) && get_user_meta( $user_id, 'appmaker_wc_user_login_from_app' ) ) {
				sprintf( __( 'Order updated #%s', 'appmaker-woocommerce-mobile-app-manager' ), $display_order_id );
				$fcm->setTopic( "user-$user_id" )
				    ->setMessage( sprintf( __( 'Order updated #%s', 'appmaker-woocommerce-mobile-app-manager' ), $display_order_id ),
					sprintf( __( 'Order status changed to %s', 'appmaker-woocommerce-mobile-app-manager' ), $order->get_status() ))
				    ->setAction( array(
					    'type'   => 'OPEN_ORDER',
					    'params' => array(
						    'orderId' => $order_id,
					    ),
				    ) )
				    ->send();
			}
		}

	}

	public function hook_stripe_enable_headers() {
		$output = '<style> .stripe_checkout_app { height: 580px !important; } </style>
                   <meta name="mobile-web-app-capable" content="yes">
                   <meta name="viewport" content="width=device-width, initial-scale=1.0">';
		echo $output;
	}

	public function hook_payment_footer() {
		$gateway = isset( $_GET['payment_gateway'] ) ? $_GET['payment_gateway'] : '';
		$output  = '
				<script type="text/javascript">
				window.onload = function() { 
					setTimeout(function(){
				';
		if ( ! empty( $gateway ) ) {
			$output .= "\n\t\t" . 'document.getElementById("payment_method_' . $gateway . '").checked = true;';
			$output .= "\n\t\t" . 'document.getElementById("payment_method_' . $gateway . '").click();';
		}
		$output .= "\n\t\t" . 'if(document.getElementById("terms") != null ) {';
		$output .= "\n\t\t" . 'document.getElementById("terms").checked = true;
			}
		';
		if ( isset( $gateway ) && ! in_array( $gateway, apply_filters( 'appmaker_wc_checkout_skip_click', array( 'square','eway_payments' ) ) ) ) {
			$output .= "\n\t\t" . '
			setTimeout(function(){
			if(document.getElementById("CBAWidgets1") != null){
				document.getElementById("CBAWidgets1").click();			
			} else {
				document.getElementById("place_order").click();
			}			
			},1000);
			';
		} else {
			$output .= "\n" . 'document.getElementById("payment_method_' . $gateway . '").scrollIntoView();';
		}
		$output .= '
					},500);
			';
		$output .= '
			}
			</script>
		  ';

		echo $output;
	}

	/**
	 * Load the persistent cart make cart sync with app
	 *
	 * @return void|bool
	 */
	public function load_persistent_cart() {
		global $current_user;

		if ( ! $current_user ) {
			return false;
		}

		$saved_cart = get_user_meta( $current_user->ID, '_woocommerce_persistent_cart', true );

		if ( $saved_cart && is_array( $saved_cart ) && isset( $saved_cart['cart'] ) ) {
			WC()->session->set( 'cart', $saved_cart['cart'] );
		}

		return true;
	}

    public static function get_id( $object ) {
        if ( method_exists( $object, 'get_id' ) ) {
            return $object->get_id();
        } else {
            return $object->id;
        }
    }

    static function get_property( $object, $property ) {
        if ( method_exists( $object, 'get_'.$property ) ) {
            return call_user_func(array($object, 'get_'.$property));
        } else {
            return $object->{$property};
        }
    }
}

new APPMAKER_WC_General_hooks();
