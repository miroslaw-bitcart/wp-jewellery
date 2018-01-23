<?php

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FPF_Cart {

    private $_plugin = null;
    private $_product_fields = null;
    private $_product = null;
    private $_fields_types_by_type = array();

    public function __construct(WPDesk_Plugin_1_2 $plugin, FPF_Product_Fields $product_fields, FPF_Product $product ) {
        $this->_plugin = $plugin;
        $this->_product_fields = $product_fields;
        $this->_product = $product;
        $this->_fields_types_by_type = $product_fields->get_field_types_by_type();

        add_action( 'plugins_loaded', array( $this, 'hooks' ) );
        //$this->hooks();
    }

    public function hooks() {
        add_filter( 'woocommerce_add_cart_item', array( $this, 'woocommerce_add_cart_item' ), 22, 1 );
        add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'woocommerce_get_cart_item_from_session' ), 20, 2 );
        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'woocommerce_add_cart_item_data' ), 11, 3 );
        add_filter( 'woocommerce_get_item_data', array( $this, 'woocommerce_get_item_data' ), 10, 2 );

	    if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
		    add_action( 'woocommerce_add_order_item_meta', array( $this, 'woocommerce_add_order_item_meta' ), 10, 2 );
	    }
	    else {
		    add_action( 'woocommerce_new_order_item', array( $this, 'woocommerce_new_order_item' ), 10, 3 );
	    }

    }

    public function woocommerce_add_order_item_meta( $item_id, $values ) {
        if ( ! empty( $values['flexible_product_fields'] ) ) {
            foreach ( $values['flexible_product_fields'] as $field ) {
                $name = $field['name'];
                wc_add_order_item_meta( $item_id, $name, $field['value'] );
            }
        }
    }

    public function woocommerce_new_order_item( $item_id, $item, $order_id ) {
    	if ( $item instanceof WC_Order_Item_Product ) {
		    if ( !empty( $item->legacy_values) && !empty( $item->legacy_values['flexible_product_fields'] ) ) {
			    foreach ( $item->legacy_values['flexible_product_fields'] as $field ) {
				    $name = $field['name'];
				    wc_add_order_item_meta( $item_id, $name, $field['value'] );
			    }
		    }
	    }
    }

    public function woocommerce_get_cart_item_from_session( $cart_item, $values ) {
        if ( ! empty( $values['flexible_product_fields'] ) ) {
            $cart_item['flexible_product_fields'] = $values['flexible_product_fields'];
            $cart_item = $this->woocommerce_add_cart_item( $cart_item );
        }
        return $cart_item;
    }


    public function woocommerce_add_cart_item( $cart_item ) {
        if ( ! empty( $cart_item['flexible_product_fields'] ) ) {
            $extra_cost = 0;
            foreach ( $cart_item['flexible_product_fields'] as $field ) {
                if ( isset( $field['price_type'] ) && $field['price_type'] != '' && isset( $field['price'] ) && floatval( $field['price'] ) > 0 ) {
	                $price = floatval( $field['price'] );
                    $extra_cost += $price;
                }
            }
            //$cart_item['data']->adjust_price( $extra_cost );
	        $cart_item['data']->set_price( $cart_item['data']->get_price() + $extra_cost );
        }
        return $cart_item;
    }

    private function get_field_data( $field, $product_id, $variation_id ) {
        $ret = false;
        $value = null;
        if ( isset( $_POST[$field['id']] ) ) {
            $value = $_POST[$field['id']];
        }
        if ( $value == null && $field['required'] == '1' ) {
            return new WP_Error( 'error', sprintf( __( '%s is required.', 'flexible-product-fields' ), $field['title'] ) );
        }
        if ( $value != null ) {
            $ret = array(
                'name' => wpdesk__( $field['title'], 'flexible-product-fields' ),
                'value' => wp_kses_post($value),
            );
            if ( $field['type'] == 'checkbox' ) {
            	if ( !isset( $field['value'] ) ) {
            	    $ret['value'] = __( 'yes', 'flexible-product-fields' );
	            }
	            else {
		            $ret['value'] = $field['value'];
	            }
            }
            if ( $this->_fields_types_by_type[$field['type']]['has_price'] ) {
            	if ( !isset( $field['price_type'] ) ) {
		            $field['price_type'] = 'fixed';
	            }
                if ( isset($field['price_type']) && $field['price_type'] != '' && isset($field['price']) && $field['price'] != '' ) {
                    $ret['price_type'] = $field['price_type'];
                    $ret['price'] = $field['price'];
                }
            }
            if ( $this->_fields_types_by_type[$field['type']]['has_options'] ) {
                foreach ( $field['options'] as $option ) {
                    if ( $option['value'] == $ret['value'] ) {
                        $ret['value'] = $option['label'];
                        if ( isset($option['price_type']) && $option['price_type'] != '' && isset($option['price']) && $option['price'] != '' ) {
                            $ret['price_type'] = $option['price_type'];
                            $ret['price'] = $option['price'];
                        }
                    }
                }
            }
            if ( isset($ret['price_type']) && $ret['price_type'] != '' && isset($ret['price']) && $ret['price'] != '' ) {
                if ( isset( $variation_id ) && $variation_id != '' ) {
                    $product_id = $variation_id;
                }
                $product = wc_get_product( $product_id );
                $price = $this->_product_fields->calculate_price( floatval( $ret['price'] ), $ret['price_type'], $product, false );

	            $tax_display_mode = get_option( 'woocommerce_tax_display_cart' );
	            if ( $tax_display_mode == 'excl' ) {
		            $price = wpdesk_get_price_excluding_tax( $product, 1, $price );
	            } else {
		            $price = wpdesk_get_price_including_tax( $product, 1, $price );
	            }

	            $ret['price'] = $price;

                $price = $this->_product->wc_price( $price );

                $ret['value'] .= ' (' . $price . ')';

            }
        }
        return $ret;
    }

    public function woocommerce_get_item_data( $other_data, $cart_item ) {
		if ( ! empty( $cart_item['flexible_product_fields'] ) ) {
			foreach ( $cart_item['flexible_product_fields'] as $field ) {
				$name = $field['name'];
                $other_data[] = array(
                    'name'    => $name,
                    'value'   => $field['value'],
                    'display' => (isset( $field['display'] ) ? $field['display'] : '')
                );
			}
        }
        return $other_data;
    }

    public function woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
	    $product_data = wc_get_product( $product_id );
        //$product_data = wc_get_product( $variation_id ? $variation_id : $product_id );
        $fields = $this->_product->get_fields_for_product( $product_data );
        foreach ( $fields['fields'] as $field ) {
            $data = $this->get_field_data( $field, $product_id, $variation_id );
            if ( $data ) {
                if ( !isset( $cart_item_data['flexible_product_fields'] ) ) {
                    $cart_item_data['flexible_product_fields'] = array();
                }
                $cart_item_data['flexible_product_fields'][] = $data;
            }
        }
        return $cart_item_data;
    }


}