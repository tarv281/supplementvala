<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	//Exit if accessed directly.

class APPMAKER_WC_Third_party_support {
	static function init() {
		if ( function_exists( 'get_list_of_kecamatan' ) || function_exists( 'epeken_get_list_of_kecamatan' ) ) {
			require 'checkout-form/class-appmaker-wc-epeken.php';
			APPMAKER_WC_Epeken::init();
		}
		if ( function_exists( 'WC_JNE' ) ) {
			require 'checkout-form/class-appmaker-wc-jne.php';
			APPMAKER_WC_Jne::init();
		}

		if ( function_exists( 'WC_TIKI' ) ) {
			require 'checkout-form/class-appmaker-wc-tiki.php';
		}

		// Payment Gateways.
		if ( ! class_exists( 'APPMAKER_WC_Gateway_Appmaker' ) ) {
			require 'payment-gateway/class-appmaker-wc-payment-gateway.php';
		}

		// Payment Gateways.
		if ( class_exists( 'woocommerce_pnpdirect' ) ) {
			require 'payment-gateway/class-appmaker-wc-pnpdirect.php';
		}

		// Payment Gateways.
		if ( class_exists( 'SPYR_AuthorizeNet_AIM' ) ) {
			require 'payment-gateway/class-appmaker-wc-xmmlib.php';
		}

		// Payment Gateways.
		if ( class_exists( 'WC_Razorpay' ) ) {
			require 'payment-gateway/class-appmaker-wc-razorpay.php';
		}

        // Payment Gateways.
        if ( class_exists( 'WC_GetSimpl' ) ) {
            require 'payment-gateway/class-appmaker-wc-simpl.php';
        }

		if ( class_exists( 'PLL_Choose_Lang' ) ) {
			require 'misc/class-appmaker-wc-polylang.php';
		}

		if ( class_exists( 'SitePress' ) ) {
			require 'misc/class-appmaker-wc-wpml.php';
		}

		if ( class_exists( 'WC_Points_Rewards' ) ) {
			require 'points-and-rewards/class-appmaker-wc-points-and-rewards.php';

		}

		if ( class_exists( 'WC_Bulk_Variations' ) ) {
			require 'bulk-variations/class-appmaker-wc-bulk-variations.php';
		}

		if ( class_exists( 'RegisterPlusReduxAutoLoginPlugin' ) ) {
			require 'misc/class-appmaker-wc-invitaion-code.php';
		}

		if ( class_exists( 'WC_Product_Gift_Wrap' ) ) {
			require 'misc/class-appmaker-wc-product-gift-wrap.php';
		}

		if ( function_exists( 'picodecheck_ajax_submit' ) ) {
			require 'misc/class-appmaker-wc-pincode-check-pro.php';
		}

		if ( class_exists( 'WC_Vendors' ) ) {
			require 'misc/class-appmaker-wc-vendors.php';
		}

		if ( class_exists( 'WC_Product_Addons' ) ) {
			require 'misc/class-appmaker-wc-product-add-ons.php';
		}

		if ( class_exists( 'MGWB' ) ) {
			require 'misc/class-appmaker-wc-product-brand.php';
		}

        if ( class_exists( 'WooCommerceWholeSalePrices' ) ) {
            require 'misc/class-appmaker-wc-wholesale-price.php';
        }

		if ( class_exists( 'orddd_lite_common' ) ) {
			require 'misc/class-appmaker-wc-order-date.php';
		}
        if ( class_exists( 'woocommerce_booking' ) ) {
            require 'misc/class-appmaker-wc-booking-date.php';
        }
        if ( class_exists( 'WC_Bookings' ) ) {
            require 'misc/class-appmaker-wc-booking.php';
        }
        if ( class_exists( 'byconsolewooodt_widget' ) ) {
            require 'misc/class-appmaker-wc-delivery-date-time.php';
        }
        if ( class_exists( 'order_delivery_date' ) ) {
            require 'misc/class-appmaker-wc-order-delivery-date.php';
        }

        if ( class_exists( 'WCJ_Order_Min_Amount' ) ) {
			require 'misc/class-appmaker-wc-jetpack-min-order-cart.php';
		}

		if ( class_exists( 'JEM_Controller' ) ) {
			require 'misc/class-appmaker-wc-woocommece-minimum-order.php';
		}

		if ( class_exists( 'SmsAlertUtility' ) ) {
			require 'misc/class-appmaker-sms-verify.php';
		}
        if(class_exists('WC_Cancel_Order')) {
            require 'misc/class-appmaker-wc-cancel-order.php';
        }
        if(function_exists( 'gglcptch_check' ) ) {
            require 'misc/class-appmaker-wc-google-captcha.php';
        }

        if ( class_exists( 'Wcff' ) ) {
			require 'misc/class-appmaker-wc-wcff-custom-fields.php';
		}
        if ( function_exists( 'pcmfe_admin_form_field' ) ) {

            require 'misc/class-appmaker-wc-checkout-fields.php';
        }

		if ( class_exists( 'SimpleVendor' ) ) {
			require 'misc/class-appmaker-wc-simple-vendor.php';
		}
        if ( class_exists( 'Wad' ) ) {
            require 'misc/class-appmaker-wc-all-discounts.php';
        }
        if(class_exists( 'WPSEO_Local_Search' ) ) {
            require 'misc/class-appmaker-wc-yoast-seo.php';
        }
        if(class_exists( 'WC_Ncr_No_Captcha_Recaptcha' ) ) {
            require 'misc/class-appmaker-wc-captcha.php';
        }
        if(class_exists( 'Dokan_Registration' ) ) {
            require 'misc/class-appmaker-wc-dokan.php';
        }
        if(class_exists( 'WPGDPRC' ) ) {
            require 'misc/class-appmaker-wc-wpgdpr-compliance.php';
        }

        if(class_exists( 'WCISPlugin' ) ) {
            require 'misc/class-appmaker-wc-instant-search.php';
        }

        if ( class_exists("WCMp_Product" ) ) {
            require 'misc/class-appmaker-wc-wcmp.php';
        }
        //woocommerce gateway beanstream
        if ( class_exists( 'WC_Bambora_Loader' ) ) {
            require 'payment-gateway/class-appmaker-wc-beanstream.php';
        }
     // product specifications woocommerce
        if ( class_exists( 'DW_specs' ) ) {
            require 'misc/class-appmaker-wc-specifications.php';
        }
        // amazon pay
        if ( class_exists( 'WC_Amazon_Payments_Advanced' ) ) {
            require 'payment-gateway/class-appmaker-wc-amazonpay.php';
        }
        // aftership-woocommerce tracking
        if ( class_exists( 'AfterShip' ) ) {
            require 'misc/class-appmaker-wc-aftership-order-tracking.php';
        }
        //woocommerce mailchimp integration
        if ( class_exists( 'WC_Mailchimp' ) ) {
            require 'misc/class-appmaker-wc-mailchimp.php';
        }
        //yith woocommerce order tracking
        if ( class_exists( 'YITH_WooCommerce_Order_Tracking' ) ) {
            require 'misc/class-appmaker-wc-yith-order-tracking.php';
        }
        if(APPMAKER_WC::$api->get_settings( 'out_of_stock', 0) == 1 ){
		    require  'misc/class-appmaker-wc-out-of-stock-order.php';
        }
        //product size chart for woocommerce
        if ( class_exists( 'productsize_chart' ) ) {
            require 'misc/class-appmaker-wc-size-chart.php';
        }
        //Improved badgets woocommerce -premium version
        if ( class_exists( 'WC_Improved_Sale_Badges_Init' ) ) {
            require 'misc/class-appmaker-wc-improved-badges.php';
        }
        //perfect woocommerce brands
        if ( class_exists( 'Perfect_Woocommerce_Brands\Perfect_Woocommerce_Brands' ) ) {
            require 'misc/class-appmaker-wc-product-filter.php';
        }
        //collivery woocommerce
        if ( class_exists( 'MdsCheckoutFields' ) ) {
            require 'misc/class-appmaker-wc-collivery.php';
        }

        //phphive-WooCommerce Bookings And Appointments
        if ( class_exists( 'phive_booking_initialze_premium' ) ) {
            require 'misc/class-appmaker-wc-phphive-booking.php';
        }

        //woo custom fee
        if(function_exists( 'wacf_check_woocommerce_plugin' ) ) {
            require 'misc/class-appmaker-wc-custom-fee.php';
        }

        //digits
        if(function_exists( 'digits_load_plugin_textdomain' ) ) {
            require 'misc/class-appmaker-wc-digits-otp.php';
        }

        //https://wordpress.org/plugins/advanced-nocaptcha-recaptcha/
        if ( class_exists( 'anr_captcha_class' ) ) {
            require 'misc/class-appmaker-wc-invisible-captcha.php';
        }
        //https://tw.wordpress.org/plugins/ecpay-invoice-for-woocommerce/
        if ( class_exists( 'WC_ECPayinvoice' ) ) {
            require 'misc/class-appmaker-wc-ecway-invoice.php';
        }
        //Yith woocommerce points and rewards
        if ( class_exists( 'YITH_WC_Points_Rewards' ) ) {
            require 'points-and-rewards/class-appmaker-wc-yith-points-rewards.php';
        }
        //Booster for WooCommerce
        if ( class_exists( 'WC_Jetpack' ) ) {
            require 'misc/class-appmaker-wc-booster.php';
        }
        //woocommerce waitlist
        if ( class_exists( 'Xoo_WL_Public' ) ) {
            require 'misc/class-appmaker-wc-waitlist.php';
        }
        //woocommerce and qtranslatex plugin
        if (function_exists( 'qwc_init_language' ) ) {
            require 'misc/class-appmaker-wc-qtranslatex.php';
        }
        //suntech payment gateways- atm and buy safe
        if(class_exists('WC_Gateway_Suntech_Base')){
		    require 'payment-gateway/class-appmaker-wc-suntech-payments.php';
        }
        //Advanced woo search
        if(class_exists('AWS_Search')){
            require 'misc/class-appmaker-wc-advanced-woo-search.php';
        }
        //flexible checkout fields
        if(class_exists('Flexible_Checkout_Fields_Disaplay_Options')){
            require 'misc/class-appmaker-wc-flexible-checkout-fields.php';
        }
        //woo wallet
        if(class_exists('WooWallet')){
            require 'payment-gateway/class-appmaker-wc-woo-wallet.php';
        }
        //woocommerce 360 image
        if(class_exists('WC_360_Image_Display')){
		    require 'misc/class-appmaker-wc-360-image.php';
        }
        //rede woocommerce api
        if(class_exists('WC_Rede')){
            require 'payment-gateway/class-appmaker-wc-rede.php';
        }
        //WC Simulador de parcelas e descontos
        if(class_exists('WC_Simulador_Parcelas')){
            require 'misc/class-appmaker-wc-simulador-de-parcelas.php';
        }
        //order hours for woocommerce
        if(class_exists('Zhours\Aspect\InstanceStorage')){
            require 'misc/class-appmaker-wc-order-hours.php';
        }
        //sumo payment plan
        if(class_exists('SUMOPaymentPlans')){
            require 'misc/class-appmaker-wc-sumopayment.php';
        }
         //WC Simulador frete
         if(class_exists('WC_Shipping_Simulator')){
            require 'misc/class-appmaker-wc-simulador-frete.php';
        }
        //WC city select
        if(class_exists('WC_City_Select')){
            require 'checkout-form/class-appmaker-wc-city-select.php';
        }
        //woocommerce simple auction
        if(class_exists('WooCommerce_simple_auction')){
            require 'misc/class-appmaker-wc-simple-auction.php';
        }
         //WC paymes gateway
         if(class_exists('WC_Paymes_Gateway')){
            require 'payment-gateway/class-appmaker-wc-paymes.php';
        }
         //knawat drop shipping plugin - image size issue
         if(class_exists('Featured_Image_By_URL_Common')){
            require 'misc/class-appmaker-wc-knawat-image.php';
        }
        //wcfm multivendor plugin
        if(class_exists('WCFMmp')){
            require 'misc/class-appmaker-wc-wcfm-vendor.php';
        }
        APPMAKER_WC_Gateway_Appmaker::init();
	}

}

