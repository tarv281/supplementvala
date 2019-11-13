<?php /*
Plugin Name: Paytm WooCommerce payment gateway
Plugin URI:  https://wordpress.org/plugins/woo-paylabs-paytm/
Description: Paytm WooCommerce Payment Gateway accept payments using any payment method - Credit / Debit Cards, UPI, QR payment, Netbanking, Wallet, EMI and Refund options from WP admin developed by PluginStar.
Version:     2.0
Author:      Subhadeep Mondal
Author URI:  https://www.linkedin.com/in/subhadeep-mondal
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 2.0.0
WC tested up to: 3.7.1
*/
if (!defined('ABSPATH')) exit;
Class Ps_Paylabs_Paytmpay
{
    /**
    * construct function for this plugin Init __construct()
    *
    */
	public function __construct()
    {
    	define('PSPTM_VER', '1.9');
    	define('PSPTM_PATH', plugin_dir_path(__FILE__));
    	define('PSPTM_BASENAME', plugin_basename(__FILE__));
        define('PSPTM_URL', plugin_dir_url(__FILE__));
    	add_action('plugins_loaded', array(
            $this,
            'wpl_paytm_init_gateway'
        ));
        $this->wpl_ps_ptm_init();
    }//construct function for this plugin Init __construct() End

    /**
    * Init the payment gateway wpl_paytm_init_gateway()
    *
    */
    function wpl_paytm_init_gateway() 
    {
    	 if (!class_exists('WC_Payment_Gateway'))
            return;
        require_once(PSPTM_PATH . 'lab-inc/paytm.functions.php');
    }// Init the payment gateway wpl_paytm_init_gateway() end

    /**
    * Init function wpl_ps_ptm_init()
    *
    */
	public function wpl_ps_ptm_init()
    {
    	add_filter('woocommerce_payment_gateways', array(
            $this,
            'wpl_add_paylabs_paytm_gateway'
        ));
        add_filter('plugin_action_links_'.PSPTM_BASENAME, array(
            $this,
            'wpl_paylabs_paytm_add_action_links'
        ));
        add_filter('woocommerce_currencies', array(
            $this,
            'wpl_paylabs_paytm_add_indian_rupee'
        ));
        add_filter('woocommerce_currency_symbol', array(
            $this,'wpl_paylabs_paytm_add_indian_rupee_currency_symbol'), 10, 2);
    }// Init function wpl_ps_ptm_init() end

    /**
    * Add this payment gateway to woocommerce wpl_add_paylabs_paytm_gateway()
    *
    */
    function wpl_add_paylabs_paytm_gateway($methods) 
    {
		$methods[] = 'Wpl_PayLabs_WC_Paytm'; return $methods;
	}// Add this payment gateway to woocommerce wpl_add_paylabs_paytm_gateway() end

    /**
    * Add action list for WP admin plugin list wpl_paylabs_paytm_add_action_links()
    *
    */
	public function wpl_paylabs_paytm_add_action_links($links)
    {
        $mylinks = array(
            '<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout&section=wpl_paylabs_paytm').'"><b>' . esc_html__('Settings') . '</b></a>'
        );
        return array_merge($mylinks, $links);
    }// Add action list for WP admin plugin list wpl_paylabs_paytm_add_action_links() end
   
    /**
    * add Indian rupee wpl_paylabs_paytm_add_indian_rupee()
    *
    */
	function wpl_paylabs_paytm_add_indian_rupee( $currencies ) 
	{
		$currencies['INR'] = __( 'Indian Rupee', 'woocommerce' );
		return $currencies;
	}// add indian rupee wpl_paylabs_paytm_add_indian_rupee() end

    /**
    * Add Indian rupee currency symbol if not exists wpl_paylabs_paytm_add_indian_rupee_currency_symbol()
    *
    */
	function wpl_paylabs_paytm_add_indian_rupee_currency_symbol( $currency_symbol, $currency ) 
	{
		switch( $currency ) 
		{
			case 'INR': $currency_symbol = 'Rs.'; break;
		}
		return $currency_symbol;
	}// Add Indian rupee currency symbol if not exists wpl_paylabs_paytm_add_indian_rupee_currency_symbol() end

    /**
    * Uninstall this plugin from website wpl_paylabs_paytm_uninstall()
    *
    */
	public function wpl_paylabs_paytm_uninstall()
	{
		delete_option('woocommerce_wpl_paylabs_paytm');
		delete_option('woocommerce_wpl_paylabs_paytm_settings');
	}// Uninstall this plugin from website wpl_paylabs_paytm_uninstall() end
}// Class end Ps_Paylabs_Paytmpay

    // Let's Gateway ready !!!!
	new Ps_Paylabs_Paytmpay(); 
	register_uninstall_hook(__FILE__,array('Ps_Paylabs_Paytmpay','wpl_paylabs_paytm_uninstall'));