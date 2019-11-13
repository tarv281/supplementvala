<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_Third_WPML {
	public static function init() {
	    add_filter('appmaker_pre_build_product_scroller',array('APPMAKER_WC_Third_WPML','product_by_language'),2,2);
		global $sitepress, $sitepress_settings;
		if ( ! empty( $_REQUEST['language'] ) && $_REQUEST['language'] != 'default' ) {
			$language = $_REQUEST['language'];
		} else {
			$language = APPMAKER_WC::$api->get_settings( 'default_language', 'default' );
		}
		$wpml_post_types = new WPML_Post_Types( $sitepress );
		/*$custom_posts = $wpml_post_types->get_translatable_and_readonly();
		if ( $custom_posts ) {
			$translation_mode = WPML_CONTENT_TYPE_DONT_TRANSLATE;
			foreach ( $custom_posts as $k => $custom_post ){					 
					 if($k == 'product'){
			            if ( isset( $sitepress_settings['custom_posts_sync_option'][ $k ] ) ) {
							$translation_mode = (int) $sitepress_settings['custom_posts_sync_option'][ $k ];							
			            }
			            $unlocked = false;
			            if ( isset( $sitepress_settings['custom_posts_unlocked_option'][ $k ] ) ) {
				            $unlocked = (int) $sitepress_settings['custom_posts_unlocked_option'][ $k ];
						}
					}				    
			}
		}*/
				
		if ( ! empty( $language ) && $language != 'default' && ! empty( $sitepress ) ) {
			if ( preg_match( '/-/i',$language ) ) {
				$language = explode( '-',$language );
				$language = $language[0];
			}
			
			if ( $sitepress->get_current_language() != $language) {
				
				$lang_switch_enable = apply_filters( 'appmaker_switch_language', true );

				if ( $lang_switch_enable ) {
					$sitepress->switch_lang( $language, true );
				}				
				
			}
			
		}
	}

	public static function product_by_language($products,$language){
        if( !empty($products) && $language != false) {
            foreach ($products as $id => $product_id) {

                global $wpml_post_translations, $sitepress;
                if ($wpml_post_translations->get_element_lang_code($product_id) != $sitepress->get_current_language()) {
                    unset($products[$id]);
                }
            }
        }
        return $products;
    }
}

APPMAKER_WC_Third_WPML::init();
