<?php 
/**
* 	Paytm WooCommerce payment gateway
* 	Author: Subhadeep Mondal
*	Author URI: https://www.linkedin.com/in/subhadeep-mondal
*	Created: 02/08/2018
*	Modified: 23/10/2019
**/
if (!defined('ABSPATH')) exit;
class Wpl_PayLabs_WC_Paytm extends WC_Payment_Gateway 
{
	/**
    * construct function for this plugin __construct()
    *
    */
	public function __construct() 
	{
		global $woocommerce;
		$this->id				= 'wpl_paylabs_paytm';
		$this->method_title = __('Paytm PluginStar', 'wpl_woo_paylabs_paytm');
		$this->method_description = __('Direct payment via Paytm. Paytm accepts Credit / Debit Cards, UPI, QR payment, Netbanking, Wallet, EMI options and Refund options', 'wpl_woo_paylabs_paytm');
		$this->icon 			= PSPTM_URL.'images/paytm_icon.png';
		$this->has_fields 		= true;
		$this->supports = array('refunds');
		$this->liveurl			= 'https://securegw.paytm.in/';
		$this->testurl			= 'https://securegw-stage.paytm.in/';
		$this->init_form_fields();
		$this->init_settings();
		$this->responseVal		= '';
		if(get_option( 'woocommerce_currency')=='INR') 
		{
			$paylabs_paytm_enabled = $this->settings['enabled'];
		}
		else 
		{
			$paylabs_paytm_enabled = 'no';
		} 
		$this->enabled			= $paylabs_paytm_enabled;
		$this->testmode			= $this->settings['testmode'];

		if(isset($this->settings['industry_type_id']) && $this->settings['industry_type_id']!='')
			$this->industry_type_id	= $this->settings['industry_type_id'];
		else
			$this->industry_type_id = 'Retail'; 

		if(isset($this->settings['thank_you_message']))
			$this->thank_you_message = __($this->settings['thank_you_message'], 'wpl_woo_paylabs_paytm');
		else
			$this->thank_you_message = __('Thank you! your order has been received.', 'wpl_woo_paylabs_paytm');

		if(isset($this->settings['redirect_message']) && $this->settings['redirect_message']!='')
			$this->redirect_message = __( $this->settings['redirect_message'], 'wpl_woo_paylabs_paytm' );
		else
			$this->redirect_message = __( 'Thank you for your order. We are now redirecting you to Pay with Paytm to make payment.', 'woo-paylabs-amazonpay' );

		$this->merchantid   		= $this->settings['merchantid'];
		$this->merchant_website   	= $this->settings['merchant_website'];
		$this->mkey   				= $this->settings['mkey'];

		if('yes'==$this->testmode) 
		{
			$this->title 		= 'Sandbox Paytm';
			$this->description 	= '<a href="https://developer.paytm.com/docs/testing-integration/" target="_blank">Development Guide and Test Account details</a>';
		}
		else
		{
			$this->title 				= $this->settings['title'];
			$this->description  		= $this->settings['description'];
		}

		if(isset($_GET['wpl_paylabs_paytm_callback']) && isset($_GET['results']) && esc_attr($_GET['wpl_paylabs_paytm_callback'])==1 && esc_attr($_GET['results']) != '') 
		{
			$this->responseVal = $_GET['results'];
			add_filter( 'woocommerce_thankyou_order_received_text', array($this, 'wpl_paytm_thankyou'));
		}

		add_action('init', array(&$this, 'wpl_paytm_transaction'));
		add_action( 'woocommerce_api_'.strtolower(get_class( $this )) , array( $this, 'wpl_paytm_transaction' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'wpl_paytm_receipt_page' ) );
	} // End Constructor

   	/**
	* init Gateway Form Fields init_form_fields()
	*
	*/
	function init_form_fields() 
	{
		$this->form_fields = array(
			'enabled' => array(
				'title'			=> __('Enable/Disable:','wpl_woo_paylabs_paytm'),
				'type'			=> 'checkbox',
				'label' 		=> __( 'Enable Paytm', 'wpl_woo_paylabs_paytm' ),
				'default'		=> 'yes'
			),
			'title' => array(
				'title' 		=> __( 'Title:', 'wpl_woo_paylabs_paytm' ),
				'type' 			=> 'text',
				'description'	=> __( 'This controls the title which the user sees during checkout.', 'wpl_woo_paylabs_paytm' ),
				'custom_attributes' => array( 'required' => 'required' ),
				'default' 		=> __( 'Paytm', 'wpl_woo_paylabs_paytm' )
			),
			'description' => array(
				'title' 		=> __( 'Description:', 'wpl_woo_paylabs_paytm' ),
				'type' 			=> 'textarea',
				'description' 	=> __( 'This controls the description which the user sees during checkout.', 'wpl_woo_paylabs_paytm' ),
				'default' 		=> __( 'Direct payment via Paytm. Paytm accepts VISA, MasterCard, Debit Cards and the Net Banking of all major banks.', 'wpl_woo_paylabs_paytm' ),
			),
			'merchantid' => array(
				'title' 		=> __( 'Merchant ID:', 'wpl_woo_paylabs_paytm' ),
				'type' 			=> 'text',
				'custom_attributes' => array( 'required' => 'required' ),
				'description' 	=> __( 'This Merchant ID is generated at the time of activation of your site and helps to uniquely identify you to Paytm Merchant', 'wpl_woo_paylabs_paytm' ),
				'custom_attributes' => array( 'required' => 'required', 'autocomplete'=> 'off' ),
				'default' 		=> ''
			),
			'mkey' => array(
				'title' 		=> __( 'Merchant Key:', 'wpl_woo_paylabs_paytm' ),
				'type'	 		=> 'text',
				'custom_attributes' => array( 'required' => 'required' ),
				'description' 	=> __( 'String of Key characters provided by Paytm', 'wpl_woo_paylabs_paytm' ),
				'custom_attributes' => array( 'required' => 'required', 'autocomplete'=> 'off' ),
				'default' 		=> ''
			),
			'industry_type_id' => array(
				'title' 		=> __( 'Industry Type:', 'wpl_woo_paylabs_paytm' ),
				'type'	 		=> 'text',
				'custom_attributes' => array( 'required' => 'required' ),
				'description' 	=> __( 'INDUSTRY TYPE ID provided by Paytm use <b>Retail</b> for sandbox/test mode', 'wpl_woo_paylabs_paytm' ),
				'custom_attributes' => array( 'required' => 'required', 'autocomplete'=> 'off' ),
				'default' 		=> ''
			),
			'merchant_website' => array(
				'title' 		=> __( 'Website:', 'wpl_woo_paylabs_paytm' ),
				'type'	 		=> 'text',
				'custom_attributes' => array( 'required' => 'required' ),
				'description' 	=> __( 'Website url provided by Paytm use <b>WEBSTAGING</b> for sandbox/test mode', 'wpl_woo_paylabs_paytm' ),
				'custom_attributes' => array( 'required' => 'required', 'autocomplete'=> 'off' ),
				'default' 		=> ''
			),
			'testmode' => array(
				'title' 		=> __('Mode of transaction:', 'wpl_woo_paylabs_paytm'),
				'type' 			=> 'select',
				'label' 		=> __('Paytm Tranasction Mode.', 'wpl_woo_paylabs_paytm'),
				'options' 		=> array('yes'=>'Test / Sandbox Mode','no'=>'Live Mode'),
				'default' 		=> 'no',
				'description' 	=> __('Mode of Paytm activities'),
				'desc_tip' 		=> true
                ),
			'thank_you_message' => array(
				'title' 		=> __( 'Thank you page message:', 'wpl_woo_paylabs_paytm' ),
				'type' 			=> 'textarea',
				'description' 	=> __( 'Thank you page order success message when order has been received', 'wpl_woo_paylabs_paytm' ),
				'default' 		=> __( 'Thank you! your order has been received.', 'wpl_woo_paylabs_paytm' ),
				),
			'redirect_message' => array(
				'title' 		=> __( 'Redirecting you to Pay with Paytm:', 'woo-paylabs-amazonpay' ),
				'type' 			=> 'textarea',
				'description' 	=> __( 'We are now redirecting you to Paytm to make payment', 'woo-paylabs-amazonpay' ),
				'default' 		=> __( 'Thank you for your order. We are now redirecting you to Pay with Paytm to make payment.', 'wpl_woo_paylabs_paytm' ),
				),
			);
	} // End init Gateway Form Fields init_form_fields()

	/**
	* WP Admin Options admin_options() 
	*
	*/
	public function admin_options() 
	{
    	?>
    	<h3><?php _e( 'Paytm PluginStar', 'wpl_woo_paylabs_paytm' ); ?></h3>
    	<p><?php _e( 'Paytm works by sending the user to Paytm to enter their payment information to complete their payment process. Note that Paytm will only take payments in Indian Rupee(INR).', 'wpl_woo_paylabs_paytm' ); ?></p>
		<?php
			if ( get_option( 'woocommerce_currency' ) == 'INR' ) 
			{
			?>
				<table class="form-table">
					<?php $this->generate_settings_html(); ?>
				</table>
			<?php
			} 
			else 
			{
				?>
				<div class="inline error">
					<p><strong><?php _e( 'Paytm PluginStar Gateway Disabled', 'wpl_woo_paylabs_paytm' ); ?></strong>
						<?php echo sprintf( __( 'Choose Indian Rupee (Rs.) as your store currency in 
						<a href="%s">Pricing Options</a> to enable the Paytm WooCommerce payment gateway.', 'wpl_woo_paylabs_paytm' ), admin_url( 'admin.php?page=wc-settings' ) ); ?>
					</p>
				</div>
				<?php
			} // End check currency
	} // End WP Admin Options admin_options()

	/**
	* Build the form after click on Paytm Paylabs button wpl_generate_paylabs_paytm_form()
	*
	*/
    private function wpl_generate_paylabs_paytm_form($order_id) 
    {
    	$this->wpl_paytm_clear_cache();
		global $wp;
		global $woocommerce;
		$order = new WC_Order($order_id);
		$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
		update_post_meta( $order_id, '_transaction_id', $txnid);
		$returnURL = $woocommerce->api_request_url(strtolower(get_class( $this )));

		$paylabs_paytm_args_data = array(
			'MID'					=> $this->merchantid,
			'ORDER_ID'				=> $txnid,
			'CUST_ID'				=> $order->get_billing_email(),
			'INDUSTRY_TYPE_ID'		=> $this->industry_type_id,
			'CHANNEL_ID'			=> 'WEB',
			'TXN_AMOUNT'			=> $order->get_total(),
			'WEBSITE'				=> $this->merchant_website,
			'CALLBACK_URL'			=> $returnURL,
			'MOBILE_NO'				=> $order->get_billing_phone(),
			'EMAIL'					=> $order->get_billing_email(),
		);

		$paylabs_paytm_args = array_filter($paylabs_paytm_args_data);
		$paylabs_paytm_args["CHECKSUMHASH"] = $this->wpl_paylabs_paytm_getChecksumFromArray($paylabs_paytm_args,$this->mkey);

		$checkoutform = '';
		foreach($paylabs_paytm_args as $name => $value) 
		{
			if($value) 
   			{
				$checkoutform .='<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
		}
		$posturl = $this->liveurl;
		if($this->testmode=='yes') 
		{
			$posturl=$this->testurl;
		}
		return __('<form action="'.$posturl.'order/process" method="POST" name="paytmform" id="paytmform">
				' . $checkoutform . '
				<input type="submit" class="button" id="submit_paylabs_paytm_payment_form" value="' . __( 'Pay with Paytm', 'wpl_woo_paylabs_paytm' ) . '" /> <a class="button cancel" href="' . $order->get_cancel_order_url() . '">'.__( 'Cancel order &amp; restore cart', 'wpl_woo_paylabs_paytm' ) . '</a>
				<script type="text/javascript">
					jQuery(function(){
						jQuery("body").block(
							{
								message: "'.__($this->redirect_message, 'wpl_woo_paylabs_paytm').'",
								overlayCSS:
								{
									background: "#fff",
									opacity: 0.6
								},
								css: {
							        padding:        20,
							        textAlign:      "center",
							        color:          "#555",
							        border:         "3px solid #aaa",
							        backgroundColor:"#fff",
							        cursor:         "wait"
							    }
							});
							jQuery("#paytmform").submit();
							jQuery("#submit_paylabs_paytm_payment_form").click();
					});
				</script>
			</form>');
	} // End Paytm Paylabs button wpl_generate_paylabs_paytm_form()

	/**
	* Process the payment for checkout process_payment() 
	*
	*/
	function process_payment($order_id) 
	{
		$this->wpl_paytm_clear_cache();
		global $woocommerce;
		$order = new WC_Order( $order_id );
		return array(
			'result' 	=> 'success',
			'redirect'	=> $order->get_checkout_payment_url(true)
		);
	} // checkout process_payment()  end

	/**
	 * Page after cheout button and redirect to Paytm payment page wpl_paytm_receipt_page()
	 * 
	 */
	function wpl_paytm_receipt_page($order_id) 
	{
		$this->wpl_paytm_clear_cache();
		global $woocommerce;
		$order = new WC_Order($order_id);
		printf('<h3>%1$s</h3>',__('Thank you for your order, please click the button below to Pay with Paytm.', 'wpl_woo_paylabs_paytm'));
		_e($this->wpl_generate_paylabs_paytm_form($order_id ));
	} // Cheout button and redirect wpl_paytm_receipt_page() end

	/**
	* Check the status of current transaction and get response with $_POST wpl_paytm_transaction()
	*
	*/
	function wpl_paytm_transaction() 
	{
		global $woocommerce;
		global $wpdb;

		if(isset($_POST['ORDERID']) && $_POST['ORDERID'] != '')
		{
			$trnid = $_POST['ORDERID'];
		}
		$args = array(
	        'post_type'   => 'shop_order',
	        'post_status' => array('wc'), 
	        'numberposts' => 1,
	        'meta_query' => array(
	               array(
	                   'key' => '_transaction_id',
	                   'value' => $trnid,
	                   'compare' => '=',
	               )
	           )
	        );
	    $post_id_arr = get_posts( $args );
	    if(isset($post_id_arr[0]->ID) && $post_id_arr[0]->ID !='')
	    	$order_id = $post_id_arr[0]->ID;
	    $order = new WC_Order($order_id);
		$mkey = $this->mkey;
		if(!empty($_POST)) 
		{
			foreach($_POST as $key => $value) 
			{
				$this->responseVal[$key] = htmlentities($value, ENT_QUOTES);
			}
		}
		else 
		{
			wc_add_notice( __('Error on payment: Paytm payment failed!', 'wpl_woo_paylabs_paytm'), 'error');
			wp_redirect($order->get_cancel_order_url());
		}

		$postResp = $_POST;
		$postRespChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";

		/* Checking hash for the transaction wheather this response is true or false */
		if($this->wpl_check_paytm_hash_after_transaction($postResp, $mkey, $postRespChecksum)) 
		{
			if(isset($this->responseVal['TXNID']) && $this->responseVal['TXNID']!='')
				update_post_meta( $order_id, '_ptm_authorization_id', $this->responseVal['TXNID'] );
			
			if($postResp['STATUS'] == 'TXN_SUCCESS')
			{
				$order_note = sprintf( __('Reference Order ID: %1$s<br>Paytm Transaction ID: %2$s<br>Bank Ref: %3$s<br>Transaction method: %4$s', 'woo-paylabs-amazonpay' ), $this->responseVal['ORDERID'], $this->responseVal['TXNID'], $this->responseVal['BANKTXNID'], $this->responseVal['GATEWAYNAME'].' ( '.$this->responseVal['PAYMENTMODE'].' )') ;
				$order->add_order_note($order_note);
				$order->payment_complete();
			}
			elseif($postResp['STATUS'] == 'PENDING')
			{
				$order_note = sprintf( __('Reference Order ID: %1$s<br>Paytm Transaction ID: %2$s<br>Bank Ref: %3$s<br>Transaction method: %4$s', 'woo-paylabs-amazonpay' ), $this->responseVal['ORDERID'], $this->responseVal['TXNID'], $this->responseVal['BANKTXNID'], $this->responseVal['GATEWAYNAME'].' ( '.$this->responseVal['PAYMENTMODE'].' )') ;
				$order->add_order_note($order_note);
				$order->update_status('on-hold');
			}
			else
			{
				$order_note = sprintf( __('Paytm payment is failed.<br>Reference Order ID: %1$s<br>Error: %2$s' ), $this->responseVal['ORDERID'], $this->responseVal['RESPMSG']) ;
				$order->add_order_note($order_note);
				wc_add_notice( __('Error on payment: Paytm payment failed! Reference Order ID: '.$this->responseVal['ORDERID'].' ('.$this->responseVal['RESPMSG']. ' )', 'wpl_woo_paylabs_paytm'), 'error');
				wp_redirect($order->get_cancel_order_url_raw()); die();
			}
			
			$results = urlencode(base64_encode(json_encode($_POST)));
			$return_url = add_query_arg(array('wpl_paylabs_paytm_callback'=>1,'results'=>$results), $this->get_return_url($order));
	        wp_redirect($return_url);
		}
	} // get response wpl_paytm_transaction() end

	/**
	* Clear cache for the previous value wpl_paytm_clear_cache()
	*
	*/
	private function wpl_paytm_clear_cache()
	{
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
	} // Clear cache for the previous value wpl_paytm_clear_cache() end

	/**
	* get Checksum From Array wpl_paylabs_paytm_getChecksumFromArray()
	* 
	*/
	private function wpl_paylabs_paytm_getChecksumFromArray($arrayList, $key, $sort=1) 
	{
		if ($sort != 0) {
			ksort($arrayList);
		}
		$str = $this->wpl_paylabs_paytm_getArray2Str($arrayList);
		$salt = $this->wpl_paylabs_paytm_generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash = hash("sha256", $finalString);
		$hashString = $hash . $salt;
		$checksum = $this->wpl_paylabs_paytm_encrypt_e($hashString, $key);
		return $checksum;
	}// function wpl_paylabs_paytm_getChecksumFromArray() end

	/**
	* calculate hash value before transaction wpl_paytm_calculate_hash_before_transaction()
	* 
	*/
	private function wpl_paytm_calculate_hash_before_transaction($arrayList, $key, $sort=1) 
	{
		if ($sort != 0) {
			ksort($arrayList);
		}
		$str = $this->wpl_paylabs_paytm_getArray2Str($arrayList);
		$salt = $this->wpl_paylabs_paytm_generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash = hash("sha256", $finalString);
		$hashString = $hash . $salt;
		$checksum = $this->wpl_paylabs_paytm_encrypt_e($hashString, $key);
		return $checksum;
	} // function wpl_calculate_hash_before_transaction() end

	/**
	* calculate hash value after transaction wpl_check_paytm_hash_after_transaction()
	* 
	*/
	private function wpl_check_paytm_hash_after_transaction($arrayList, $key, $checksumvalue) 
	{
		$arrayList = $this->wpl_paylabs_paytm_removeCheckSumParam($arrayList);
		ksort($arrayList);
		$str = $this->wpl_paylabs_paytm_getArray2StrForVerify($arrayList);
		$paytm_hash = $this->wpl_paylabs_paytm_decrypt_e($checksumvalue, $key);
		$salt = substr($paytm_hash, -4);

		$finalString = $str . "|" . $salt;

		$website_hash = hash("sha256", $finalString);
		$website_hash .= $salt;

		$validFlag = "FALSE";
		if ($website_hash == $paytm_hash) {
			$validFlag = "TRUE";
		} else {
			$validFlag = "FALSE";
		}
		return $validFlag;
	} // function wpl_check_paytm_hash_after_transaction() end

	/**
	* remove CheckSum Param wpl_paylabs_paytm_removeCheckSumParam()
	* 
	*/
	private function wpl_paylabs_paytm_removeCheckSumParam($arrayList) 
	{
		if (isset($arrayList["CHECKSUMHASH"])) {
			unset($arrayList["CHECKSUMHASH"]);
		}
		return $arrayList;
	}// function wpl_paylabs_paytm_removeCheckSumParam() end

	/**
	* get Array 2 Str For Verify wpl_paylabs_paytm_getArray2StrForVerify()
	* 
	*/
	private function wpl_paylabs_paytm_getArray2StrForVerify($arrayList) 
	{
		$paramStr = "";
		$flag = 1;
		foreach ($arrayList as $key => $value) {
			if ($flag) {
				$paramStr .= $this->wpl_paylabs_paytm_checkString_e($value);
				$flag = 0;
			} else {
				$paramStr .= "|" . $this->wpl_paylabs_paytm_checkString_e($value);
			}
		}
		return $paramStr;
	}// function wpl_paylabs_paytm_getArray2StrForVerify() end

	/**
	* check String wpl_paylabs_paytm_checkString_e()
	* 
	*/
	private function wpl_paylabs_paytm_checkString_e($value)
	{
	    $myvalue = ltrim($value);
	    $myvalue = rtrim($myvalue);
	    if ($myvalue == 'null')
	        $myvalue = '';
	    return $myvalue;
	}// function wpl_paylabs_paytm_checkString_e() end

	/**
	* encrypt the post value for transaction wpl_paylabs_paytm_encrypt_e()
	* 
	*/
	private function wpl_paylabs_paytm_encrypt_e($input, $ky) 
	{
	    $key   = html_entity_decode($ky);
	    $iv = "@@@@&&&&####$$$$";
	    $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
	    return $data;
	}// function wpl_paylabs_paytm_encrypt_e() end

	/**
	* decrypt the post value for transaction wpl_paylabs_paytm_decrypt_e()
	* 
	*/
	private function wpl_paylabs_paytm_decrypt_e($crypt, $ky) 
	{
	    $key   = html_entity_decode($ky);
	    $iv = "@@@@&&&&####$$$$";
	    $data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, $iv );
	    return $data;
	}// function wpl_paylabs_paytm_decrypt_e() end

	/**
	* get Array 2 Str value wpl_paylabs_paytm_getArray2Str()
	* 
	*/
	private function wpl_paylabs_paytm_getArray2Str($arrayList) 
	{
		$findme   = 'REFUND';
		$findmepipe = '|';
		$paramStr = "";
		$flag = 1;	
		foreach ($arrayList as $key => $value) {
			$pos = strpos($value, $findme);
			$pospipe = strpos($value, $findmepipe);
			if ($pos !== false || $pospipe !== false) 
			{
				continue;
			}
			
			if ($flag) {
				$paramStr .= $this->wpl_paylabs_paytm_checkString_e($value);
				$flag = 0;
			} else {
				$paramStr .= "|" . $this->wpl_paylabs_paytm_checkString_e($value);
			}
		}
		return $paramStr;
	}// function wpl_paylabs_paytm_getArray2Str() end

	/**
	* generate Salt wpl_paylabs_paytm_generateSalt_e()
	* 
	*/
	private function wpl_paylabs_paytm_generateSalt_e($length)
	{
	    $random = "";
	    srand((double) microtime() * 1000000);
	    
	    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
	    $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
	    $data .= "0FGH45OP89";
	    
	    for ($i = 0; $i < $length; $i++) {
	        $random .= substr($data, (rand() % (strlen($data))), 1);
	    }
	    
	    return $random;
	}// function wpl_paylabs_paytm_generateSalt_e() end

	/**
	* get Checksum From String wpl_paylabs_paytm_getChecksumFromString()
	* 
	*/
	private function wpl_paylabs_paytm_getChecksumFromString($str, $key) 
	{
		$salt = $this->wpl_paylabs_paytm_generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash = hash("sha256", $finalString);
		$hashString = $hash . $salt;
		$checksum = $this->wpl_paylabs_paytm_encrypt_e($hashString, $key);
		return $checksum;
	}// function wpl_paylabs_paytm_getChecksumFromString() end
	
	/**
	* Thank you page success data wpl_paytm_thankyou()
	* 
	*/
	function wpl_paytm_thankyou() 
	{
		$wpl_paylabs_response = json_decode(base64_decode(urldecode($this->responseVal)), true);
		global $woocommerce;
		global $wpdb;

		if(isset($wpl_paylabs_response['ORDERID']) && $wpl_paylabs_response['ORDERID'] != '')
		{
			$trnid = $wpl_paylabs_response['ORDERID'];
		}
		$args = array(
	        'post_type'   => 'shop_order',
	        'post_status' => array('wc'), 
	        'numberposts' => 1,
	        'meta_query' => array(
	               array(
	                   'key' => '_transaction_id',
	                   'value' => $trnid,
	                   'compare' => '=',
	               )
	           )
	        );
	    $post_id_arr = get_posts( $args );
	    if(isset($post_id_arr[0]->ID) && $post_id_arr[0]->ID !='')
	    	$order_id = $post_id_arr[0]->ID;
	    $order = new WC_Order($order_id);

		$added_text = '';
		if(strtolower($wpl_paylabs_response['STATUS'])=='txn_success')
		{
			$added_text .= printf('<section class="woocommerce-order-details">
										<h3>'.$this->thank_you_message.'</h3>
										<h2 class="woocommerce-order-details__title">Transaction details</h2>
										<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
											<thead>
												<tr>
													<th class="woocommerce-table__product-name product-name">Reference Order ID:</th>
													<th class="woocommerce-table__product-table product-total">'.$wpl_paylabs_response['ORDERID'].'</th>
												</tr>
											</thead>
											<tbody>
												<tr class="woocommerce-table__line-item order_item">
													<td class="woocommerce-table__product-name product-name">Paytm Transaction ID:</td>
													<td class="woocommerce-table__product-total product-total">'.$wpl_paylabs_response['TXNID'].'</td>
												</tr>
											</tbody>
											<tfoot>
												<tr class="woocommerce-table__line-item order_item">
													<td class="woocommerce-table__product-name product-name">Bank Ref:</td>
													<td class="woocommerce-table__product-total product-total">'.$wpl_paylabs_response['BANKTXNID'].'</td>
												</tr>
												<tr>
													<th scope="row">Transaction method:</th>
													<td>'.$wpl_paylabs_response['GATEWAYNAME'].' ( '.$wpl_paylabs_response['PAYMENTMODE'].' )</td>
												</tr>
											</tfoot>
										</table>
									</section>');
		}
		elseif(strtolower($wpl_paylabs_response['STATUS'])=='pending')
        {
            $added_text .= printf('<section class="woocommerce-order-details">
										<h3>Paytm payment is pending</h3>
										<h2 class="woocommerce-order-details__title">Transaction details</h2>
										<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
											<thead>
												<tr>
													<th class="woocommerce-table__product-name product-name">Reference Order ID</th>
													<th class="woocommerce-table__product-table product-total">'.$wpl_paylabs_response['ORDERID'].'</th>
												</tr>
											</thead>
											<tbody>
												<tr class="woocommerce-table__line-item order_item">
													<td class="woocommerce-table__product-name product-name">Paytm Transaction ID:</td>
													<td class="woocommerce-table__product-total product-total">'.$wpl_paylabs_response['TXNID'].'</td>
												</tr>
											</tbody>
										</table>
									</section>');
		}
        else
        {
			wp_redirect($order->get_checkout_payment_url(false));
        }

	}// function wpl_paytm_thankyou() end

	/**
	* Process refund call process_refund()
	*
	*/
	function process_refund( $order_id, $amount = null, $reason='' ) 
	{
		global $woocommerce;
		$order = new WC_Order($order_id);

		$authorization_id 		= get_post_meta( $order_id, '_ptm_authorization_id', true );
		$transaction_id 		= get_post_meta( $order_id, '_transaction_id', true );
		$reference_id = 'REF-'.substr(hash('sha256', mt_rand() . microtime()), 0, 10);

		$paytmParams = array();
		$paytmParams["body"] = array(	"mid" => $this->merchantid,
										"txnType" => "REFUND",
		    							"orderId" => $transaction_id,
		    							"txnId" => $authorization_id,
		    							"refId" => $reference_id,
										"refundAmount" => $amount,
										"comments"	=> $reason
									);

		$checksum = $this->wpl_paylabs_paytm_getChecksumFromString(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES),$this->mkey);
		$paytmParams["head"] = array(
									"clientId"	=> "C11",
		    						"signature"	=> $checksum
		);
		$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		$posturl = $this->liveurl;
		if($this->testmode=='yes') 
		{
			$posturl=$this->testurl;
		}
		$refund_url = $posturl."refund/apply";
		$response = $this->wpl_paytm_apiCall($refund_url,$post_data);
		$ref_response = json_decode($response, true);

		if(isset($ref_response['body']['resultInfo']['resultStatus']) && ($ref_response['body']['resultInfo']['resultStatus']=='TXN_SUCCESS' || $ref_response['body']['resultInfo']['resultStatus']=='PENDING'))
		{
			$refund_note =  sprintf(__( 'Refund: %1$s %2$s<br>Paytm Refund ID: %3$s<br>Reference ID: %4$s', 'woo-paylabs-amazonpay'), $amount, get_option( 'woocommerce_currency' ), $ref_response['body']['refundId'], $ref_response['body']['refId']);
			$order->add_order_note($refund_note);
			return true;
		}
		elseif(isset($ref_response['body']['resultInfo']['resultStatus']) && $ref_response['body']['resultInfo']['resultStatus']=='TXN_FAILURE')
		{
			return new WP_Error( 'error', __( $ref_response['body']['resultInfo']['resultMsg'] ) );
		}

	}// Process refund call process_refund() end

	/**
	* Paytm API call wpl_paytm_apiCall()
	*
	*/
	function wpl_paytm_apiCall($url,$post_data)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
		$response = curl_exec($ch);
		return $response;
	}// Paytm API call wpl_paytm_apiCall() end
} //  End Wpl_PayLabs_WC_Paytm Class