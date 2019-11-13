<?php

class APPMAKER_WC_General_Helper {   


    public static function get_custom_html(){

        $output = '<script type="text/javascript">';
        $output .= "\nwindow.onload = function(e){";
        $output .= "    if(jQuery('#wrapper').length) { \n";
        $output .= "       if(jQuery('.woocommerce').length) { \n";
        $output .= "          jQuery('.woocommerce').parents().siblings().hide()\n";
        $output .= "          jQuery('.coupon-wrapper').hide() \n";
        $output .= "         jQuery('.woocommerce-cart-notice').hide()\n";
        $output .= "       } else  \n";
        $output .= "          jQuery('#wrapper').html(jQuery('#main').html())\n";
        $output .= "    } else if (jQuery('.website-wrapper').length) { \n";
        $output .= "      jQuery('.site-content').siblings().hide() \n";
        $output .= "      jQuery('.site-content').parents().siblings().hide() \n";
        $output .= "      jQuery('.woocommerce-form-login-toggle').hide() \n";
        $output .= "      jQuery('body > div.website-wrapper > header').html('') \n";
        $output .= "    } else if (jQuery('.entry-content').length) { \n";
        $output .= "     jQuery('.entry-content').siblings().hide() \n";
        $output .= "      jQuery('.entry-content').parents().siblings().hide() \n";
        $output .= "      jQuery('#checkout_paypal_message').hide()\n";
        $output .= "      jQuery('.woocommerce-form-login-toggle').hide() \n";
        $output .= "      jQuery('.woocommerce-form-coupon-toggle').hide() \n";
        $output .= "      jQuery('#dokan-navigation').hide()\n";
        $output .= "    } else if (jQuery('#main').length) { \n";
        $output .= "          if (jQuery('#payment').length) { \n";
        $output .= "            jQuery('#payment').parents().siblings().hide()\n";
        $output .= "            jQuery('.shop_table').hide() \n";
        $output .= "            setTimeout(function(){";
        $output .= "            jQuery('.zopim').hide() \n";
        $output .= "            },3000);\n";
        $output .= "            jQuery('#main').css('margin-bottom','00px') \n";
        $output .= "            jQuery('#main').css('margin-left','00px') \n";
        $output .= "            jQuery('#main').css('height','100vh') \n";
        $output .= "            jQuery('#main').css('background-color','#f4f6fd') \n";
        $output .= "            jQuery('#main').css('position','relative') \n";
        $output .= "            jQuery('.button').css('position','absolute') \n";
        $output .= "            jQuery('.button').css('bottom','27px') \n";
        $output .= "            jQuery('.button').css('right','75px') \n";
        $output .= "          } else {\n";
        $output .= "            jQuery('#main').parents().siblings().hide()\n";
        $output .= "            setTimeout(function(){";
        $output .= "            jQuery('#wh-widget-send-button').hide()\n";
        $output .= "           },3000);\n";
        $output .= "          } \n";
        $output .= "    } else if (jQuery('.page-wrapper').length) { \n";
        $output .= "      jQuery('.page-wrapper').html(jQuery('.main-content ').html())\n";
        $output .= "      jQuery('#launcher').hide() \n";
        $output .= "    } \n";
//        $output .= "    jQuery('body').css('margin-left','20px')\n";
//        $output .= "    jQuery('body').css('margin-bottom','300px')\n";
        $output .= "};\n";
        $output .= "</script>\n";
        $output .='<style>.whb-sticky-header.whb-clone.whb-main-header.whb-sticked{box-shadow: none !important;}</style>';

        return $output;
    }    

}
new APPMAKER_WC_General_Helper();
