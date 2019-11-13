<?php

/**
 * Class APPMAKER_WP_WC_CONVERTER
 */
class APPMAKER_WP_WC_Converter {
	static $key = 1;

	public static function convert_inAppPage_data( $data_obj, $key ) {
		if ( empty( $data_obj ) ) {
			return array();
		}
		$app_value            = array();
		$app_value['id']      = $key;
		$app_value['title']   = html_entity_decode( $data_obj->title );
		$app_value['widgets'] = array();
		$language             = $data_obj->language;
		if ( $data_obj->language == 'default' ) {
			$language = APPMAKER_WC::$api->get_settings( 'default_language', 'default' );
			if ( $language == 'default' ) {
				$language = false;
			}
		}
		if ( class_exists( 'APPMAKER_WC' ) && ! isset( APPMAKER_WC::$api->APPMAKER_WC_REST_Products_Controller ) ) {
			$product_controller = new APPMAKER_WC_REST_Products_Controller();
		} elseif ( class_exists( 'APPMAKER_WC' ) ) {
			$product_controller = APPMAKER_WC::$api->APPMAKER_WC_REST_Products_Controller;
		}

		foreach ( $data_obj->widgets as $value_obj ) {
			$skip         = false;
			$skip_shuffle = false;

			$value  = clone( $value_obj );
			$widget = array(
				'type'  => $value->type,
				'title' => $value->title,
				'data'  => array(),
			);
			if ( 'pre_build_product_scroller' === $widget['type'] ) {
				switch ( $value->data[0]->data->action_value->value ) {
					case 'FEATURED':
						$value->data = APPMAKER_WC_Helper::wc_get_featured_product_ids( $language );
						break;
					case 'SALE':
						$value->data = APPMAKER_WC_Helper::wc_get_product_ids_on_sale( $language );
						break;
					case 'RECENT':
						$value->data  = APPMAKER_WC_Helper::get_recent_products( $language );
						$skip_shuffle = true;
						break;
					case 'BEST_SELLING':
						$value->data = APPMAKER_WC_Helper::get_best_selling_products( $language );
						break;
					case 'TOP_RATED':
						$value->data = APPMAKER_WC_Helper::get_top_rated_products( $language );
						break;
					case 'CATEGORY':
						$value->data = APPMAKER_WC_Helper::get_products_by_tax( 'product_cat', $value->data[0]->data->id->value, $language );
						break;
					case 'TAG':
						$value->data = APPMAKER_WC_Helper::get_products_by_tax( 'product_tag', $value->data[0]->data->id->value, $language );
						break;
					default:
						$value->data = array();
				}
				if ( empty( $value->data ) ) {
					$skip = true;
				} else {
					if ( ! $skip_shuffle ) {
						shuffle( $value->data );
					}
					$value->data = array_slice( $value->data, 0, 15 );
				}
			}
			foreach ( $value->data as $value_data ) {
				$data = false;
				if ( 'product_scroller' === $widget['type'] ) {
					if ( ! isset( $value_data->data->action_value->value ) && ( is_string( $value_data ) || is_numeric( $value_data ) ) ) {
						$product = APPMAKER_WC_Helper::get_product( $value_data );
					} else {
						$product = APPMAKER_WC_Helper::get_product( $value_data->data->action_value->value );

					}
					if ( ! empty( $product ) ) {
						$data = $product_controller->get_product_data( $product );
					}
				} elseif ( 'pre_build_product_scroller' === $widget['type'] ) {
					$product = APPMAKER_WC_Helper::get_product( $value_data );

					if ( ! empty( $product ) ) {
						$data = $product_controller->get_product_data( $product );
					}
				} elseif ( 'html' === $widget['type'] ) {
					$data['html'] = wpautop( do_shortcode( $value_data->data->html->value ) );
				} else {
					$data = array(
						'image'      => self::getImageUrl( $value_data->data->image ),
						'dimensions' => self::getImageDimensions( $value_data->data->image ),
						'title'      => isset( $value_data->data->title ) ? self::getValue( $value_data->data->title ) : $app_value['title'],
						'action'     => array(
							'type'   => self::get_type( $value_data->data->action->value ),
							'params' => self::get_params( $value_data->data->action->value, isset( $value_data->data->action_value->value ) ? $value_data->data->action_value->value : '' ),
						),
					);
				}
				if ( false !== $data ) {
					$widget['data'][] = $data;
				}
			}
			if ( ! empty( $value->data_main ) ) {
				$widget['meta'] = array();
				foreach ( $value->data_main as $value_key => $value_data ) {
					$value = self::getValue( $value_data );
					if ( preg_match( '/(.*)_action$/i', $value_key, $matches ) ) {
						if ( ! isset( $widget['meta'][ $value_key ] ) ) {
							$widget['meta'][ $value_key ]['type'] = array(
								'type'   => '',
								'params' => '',
							);
						}
						if ( is_string( $value ) ) {
							$widget['meta'][ $value_key ]['original_type'] = $value;
						} else {
							$widget['meta'][ $value_key ]['original_type'] = isset( $value->action->value ) ? $value->action->value : $value->action->id;
						}
						$widget['meta'][ $value_key ]['type'] = self::get_type( $value );
						if ( isset( $widget['meta'][ $value_key ]['params'] ) ) {
							$widget['meta'][ $value_key ]['params'] = self::get_params( $widget['meta'][ $value_key ]['original_type'], $widget['meta'][ $value_key ]['params'] );
						}
					} elseif ( preg_match( '/(.*)_action_value$/i', $value_key, $matches ) ) {
						$action_key = $matches[1] . '_action';
						if ( ! isset( $widget['meta'][ $action_key ]['type'] ) ) {
							$widget['meta'][ $action_key ]['params'] = $value;
						} else {
							$widget['meta'][ $action_key ]['params'] = self::get_params( $widget['meta'][ $action_key ]['original_type'], $value );
						}
					} else {
						$widget['meta'][ $value_key ] = $value;
					}
				}
			}
			if ( 'pre_build_product_scroller' === $widget['type'] ) {
				$widget['type'] = 'product_scroller';
			}
			if ( true !== $skip && ! empty( $widget['data'] ) ) {
				$app_value['widgets'][] = $widget;
			}
		}
		$app_value['hash'] = md5( serialize( $app_value ) );

		return ( $app_value );
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected static function getValue( $value ) {
		if ( is_string( $value ) || is_numeric( $value ) ) {
			return $value;
		} else {
			return $value->value;
		}
	}

	protected static function getImageUrl( $image ) {
		if ( is_string( $image ) ) {
			return $image;
		} elseif ( is_array( $image->value ) && isset( $image->value['url'] ) ) {
			return $image->value['url'];
		} elseif ( isset( $image->value->url ) ) {
			return $image->value->url;
		}  elseif ( isset( $image->value->code ) ) {
			return "https://storage.googleapis.com/stateless-appmaker-pages-wp/2019/02/32357a5c-c65321b4-logo.png";
		} else {
			return $image->value;
		}
	}

	protected static function getImageDimensions( $image ) {
		if ( empty( $image ) ) {
			return false;
		}  elseif ( isset( $image->value->code ) ) {
			return array('width'=>174,'height' =>43);
		} elseif ( is_array( $image->value ) && isset( $image->value['meta'] ) ) {
			return $image->value['meta'];
		} elseif ( isset( $image->value->meta ) ) {
			return $image->value->meta;	
		} else {
			return false;
		}
	}

	public static function get_params( $action, $action_value ) {
		switch ( $action ) {
			case 'OPEN_URL':
			case 'OPEN_IN_WEB_VIEW' : {
				$params = array( 'url' => $action_value );
				break;
			}
			case 'LIST_POST' : {
				$params = array( 'categories' => $action_value );
				break;

			}
			case 'LIST_PRODUCT' : {
				$params = array( 'category' => $action_value );
				break;
			}
			case 'LIST_PRODUCT_TAG' : {
				$params = array( 'tag' => $action_value );
				break;
			}
			case 'OPEN_IN_APP_PAGE' : {
				$params = array( 'id' => $action_value );
				break;

			}
			case 'OPEN_POST' : {
				$params = array( 'id' => $action_value );
				break;

			}
			case 'OPEN_PRODUCT' : {
				$params = array( 'id' => $action_value );
				break;
			}
			default : {
				$params = array();
				break;

			}
		}

		return $params;
	}

	public static function convert_navMenu_data( $data ) {
		$app_value         = array();
		$app_value['data'] = self::convert_navMenu_data_item( $data );
		$app_value['hash'] = md5( serialize( $app_value ) );

		return ( $app_value );
	}

	public static function convert_navMenu_data_item( $data ) {
		$return = array();
		foreach ( $data as $value ) {
			if ( isset( $value->children ) ) {
				$nodes = $value->children;
			} elseif ( isset( $value->nodes ) ) {
				$nodes = $value->nodes;
			} else {
				$nodes = array();
			}

			$menu = array(
				'id'         => self::$key ++,
				'title'      => html_entity_decode( $value->title ),
				'icon'       => self::getImageUrl( $value->icon ),
				'dimensions' => self::getImageDimensions( $value->icon ),
				'type'       => isset( $value->type->value ) ? $value->type->value : $value->type->id,
				'action'     => array(),
				'nodes'      => self::convert_navMenu_data_item( $nodes ),
			);
			if ( 'title' !== $menu['type'] ) {
				if ( is_string( $value->action_value ) ) {
					$val = $value->action_value;
				} else {
					$val = isset( $value->action_value->value ) ? $value->action_value->value : $value->action_value->id;
				}
				$menu['action'] = array(
					'type'   => self::get_type( $value ),
					'params' => self::get_params( isset( $value->action->value ) ? $value->action->value : $value->action->id, $val ),
				);
			}
			$return[] = $menu;
		}

		return $return;
	}

	public static function get_type( $value ) {
		if ( is_string( $value ) ) {
			$type = $value;
		} else {
			$type = isset( $value->action->value ) ? $value->action->value : $value->action->id;
		}
		switch ( $type ) {
			case 'LIST_PRODUCT_TAG':
				return 'LIST_PRODUCT';
			default:
				return $type;
		}
	}


}
