<?php
/*
	Plugin Name: Flexible Product Fields
	Plugin URI: https://wordpress.org/plugins/flexible-product-fields/
	Description: Allow customers to customize WooCommerce products before adding them to cart. Add fields text or textarea.
	Version: 1.0.6
	Author: WP Desk
	Author URI: https://www.wpdesk.net/
	Text Domain: flexible-product-fields
	Domain Path: /lang/
	Requires at least: 4.5
	Tested up to: 4.9
	WC requires at least: 2.6.14
    WC tested up to: 3.2.3

	Copyright 2017 WP Desk Ltd.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/


if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( plugin_basename( 'classes/wpdesk/class-plugin.php' ) );
require_once( 'inc/wpdesk-woo27-functions.php' );

require_once('classes/tracker.php');

$plugin_data = array();

class Flexible_Product_Fields extends WPDesk_Plugin_1_2 {

    static 		$_instance = null;
    private     $fpf_post_type = null;
    private     $scripts_version = '4';

    public function __construct( $plugin_data ) {

        $this->_plugin_namespace = 'flexible-product-fields';
        $this->_plugin_text_domain = 'flexible-product-fields';

        $this->_plugin_has_settings = false;

        //$this->_default_settings_tab = 'welcome';

        parent::__construct( $plugin_data );
        $this->init();
        $this->hooks();
    }

    public function wp_enqueue_scripts() {
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        wp_register_style( 'fpf_front', trailingslashit( $this->get_plugin_assets_url() ) . 'css/front' . $suffix . '.css', array(), $this->scripts_version );
        wp_enqueue_style( 'fpf_front' );
        if ( is_singular( 'product' ) ) {
            wp_register_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting' . $suffix . '.js', array( 'jquery' ), $this->scripts_version );
            wp_enqueue_script('fpf_product', trailingslashit($this->get_plugin_assets_url()) . 'js/fpf_product' . $suffix . '.js', array( 'jquery', 'accounting' ), $this->scripts_version);
            if ( ! function_exists( 'get_woocommerce_price_format' ) ) {
                $currency_pos = get_option( 'woocommerce_currency_pos' );
                switch ( $currency_pos ) {
                    case 'left' :
                        $format = '%1$s%2$s';
                        break;
                    case 'right' :
                        $format = '%2$s%1$s';
                        break;
                    case 'left_space' :
                        $format = '%1$s&nbsp;%2$s';
                        break;
                    case 'right_space' :
                        $format = '%2$s&nbsp;%1$s';
                        break;
                }

                $currency_format = esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), $format ) );
            }
            else {
                $currency_format = esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) );
            }
            wp_localize_script( 'fpf_product', 'fpf_product',
                array(
                    'total'                         => __( 'Total', 'flexible-product-fields'),
                    'currency_format_num_decimals'  => absint( get_option( 'woocommerce_price_num_decimals' ) ),
                    'currency_format_symbol'        => get_woocommerce_currency_symbol(),
                    'currency_format_decimal_sep'   => esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ),
                    'currency_format_thousand_sep'  => esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ),
                    'currency_format'               => $currency_format,
                )
            );
        }
    }


    public function admin_enqueue_scripts( $hook ) {
	    $pl = get_locale() === 'pl_PL';
	    $domain = 'net';
	    $pro_link = 'https://www.wpdesk.net/products/flexible-product-fields-pro-woocommerce/?utm_source=Flexible%20Product%20Fields&utm_medium=Settings';
	    if ( $pl ) {
		    $domain = 'pl';
		    $pro_link = 'https://www.wpdesk.pl/sklep/flexible-product-fields-pro-woocommerce/?utm_source=Flexible%20Product%20Fields&utm_medium=Settings';
	    }
	    $screen = get_current_screen();
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		if ( isset( $screen ) && ( $screen->id == 'edit-fpf_fields' || $screen->id == 'fpf_fields' ) ) {

			wp_register_script( 'fpf_react', 'https://cdnjs.cloudflare.com/ajax/libs/react/15.4.2/react-with-addons' . $suffix . '.js', array(), null, false );
			wp_enqueue_script( 'fpf_react' );


			wp_register_script( 'fpf_react_dom', 'https://cdnjs.cloudflare.com/ajax/libs/react/15.4.2/react-dom' . $suffix . '.js', array(), null, false );
			wp_enqueue_script( 'fpf_react_dom' );

			//wp_register_script( 'fpf_react_babel', 'https://unpkg.com/babel-standalone@6/babel' . $suffix . '.js', array(), null, false );
			wp_register_script( 'fpf_react_babel', trailingslashit( $this->get_plugin_assets_url() ) . 'js/babel' . $suffix . '.js', array(), $this->scripts_version, false );
			wp_enqueue_script( 'fpf_react_babel' );
			//wp_register_script( 'fpf_react_index', 'https://unpkg.com/classnames/index.js', array(), null, false );
			wp_register_script( 'fpf_react_index', trailingslashit( $this->get_plugin_assets_url() ) . 'js/classnames' . $suffix . '.js', array(), $this->scripts_version, false );
			wp_enqueue_script( 'fpf_react_index' );
			//wp_register_script( 'fpf_react_autosize', 'https://unpkg.com/react-input-autosize/dist/react-input-autosize' . $suffix . '.js', array(), null, false );
			//wp_register_script( 'fpf_react_autosize', 'https://unpkg.com/react-input-autosize@1.1.0/dist/react-input-autosize' . $suffix . '.js', array(), null, false );
			wp_register_script( 'fpf_react_autosize', trailingslashit( $this->get_plugin_assets_url() ) . 'js/react-input-autosize' . $suffix . '.js', array(), $this->scripts_version, false );
			wp_enqueue_script( 'fpf_react_autosize' );
			//wp_register_script( 'fpf_react_select', 'https://unpkg.com/react-select/dist/react-select' . $suffix . '.js', array(), null, false );
			//wp_register_script( 'fpf_react_select', 'https://unpkg.com/react-select@1.0.0-rc.3/dist/react-select' . $suffix . '.js', array(), null, false );
			wp_register_script( 'fpf_react_select', trailingslashit( $this->get_plugin_assets_url() ) . 'js/react-select' . $suffix . '.js', array(), $this->scripts_version, false );
			wp_enqueue_script( 'fpf_react_select' );
			//wp_register_script( 'fpf_react_sortable', 'https://npmcdn.com/react-sortable-hoc/dist/umd/react-sortable-hoc' . $suffix . '.js', array(), null, false );
			wp_register_script( 'fpf_react_sortable', trailingslashit( $this->get_plugin_assets_url() ) . 'js/react-sortable-hoc' . $suffix . '.js', array(), $this->scripts_version, false );
			wp_enqueue_script( 'fpf_react_sortable' );

			wp_enqueue_script( 'fpf_admin', trailingslashit( $this->get_plugin_assets_url() ) . 'js/fpf_admin.jsx', array(), $this->scripts_version, false );

			$number_step = '1';

			$price_decimals = wc_get_price_decimals();
			if ( $price_decimals == 1 ) {
				$number_step = '0.1';
			}
			else {
				$number = '0.';
				for ( $i = 1; $i < $price_decimals; $i++ ) {
					$number_step .= '0';
				}
				$number_step .= '1';
			}

			wp_localize_script( 'fpf_admin', 'fpf_admin',
				array(
					'rest_url'                  => esc_url_raw( rest_url() ),
					'rest_nonce'                => wp_create_nonce( 'wp_rest' ),
					'add_field_label'           => __( 'Add Field', 'flexible-product-fields'),
                    'section_label'             => __( 'Section', 'flexible-product-fields'),
                    'assign_to_label'           => __( 'Assign this group to', 'flexible-product-fields'),
                    'products_label'            => __( 'Select products', 'flexible-product-fields'),
                    'categories_label'          => __( 'Select categories', 'flexible-product-fields'),
					'select_placeholder'        => __( 'Select ...', 'flexible-product-fields'),
                    'field_title_label'         => __( 'Label', 'flexible-product-fields'),
                    'field_type_label'          => __( 'Field Type', 'flexible-product-fields'),
                    'field_required_label'      => __( 'Required', 'flexible-product-fields'),
                    'field_css_class_label'     => __( 'CSS Class', 'flexible-product-fields'),
                    'field_placeholder_label'   => __( 'Placeholder', 'flexible-product-fields'),
					'field_value_label'         => __( 'Value', 'flexible-product-fields'),
                    'field_price_type_label'    => __( 'Price type', 'flexible-product-fields'),
                    'field_price_label'         => __( 'Price', 'flexible-product-fields'),
					'field_options_label'       => __( 'Options', 'flexible-product-fields'),
                    'new_field_title'           => __( 'New field', 'flexible-product-fields'),
                    'edit_label'                => __( 'Edit field', 'flexible-product-fields'),
                    'delete_label'              => __( 'Delete field', 'flexible-product-fields'),
                    'add_option_label'          => __( 'Add Option', 'flexible-product-fields'),
                    'option_value_label'        => __( 'Value', 'flexible-product-fields'),
                    'option_label_label'        => __( 'Label', 'flexible-product-fields'),
                    'option_price_type_label'   => __( 'Price Type', 'flexible-product-fields'),
                    'option_price_label'        => __( 'Price', 'flexible-product-fields'),
					'select_type_to_search'     => __( 'Type to search', 'flexible-product-fields'),
                    'save_error'                => __( 'Sorry, there has been an error. Please try again later. Returned status code: ', 'flexible-product-fields' ),
					'fields_adv'                => __( 'This field type is available in PRO version.' ),
					'fields_adv_link'           => $pro_link,
					'fields_adv_link_text'      => __( 'Upgrade to PRO →' ),
					'assign_to_adv'             => __( 'This option is available in PRO version.' ),
					'assign_to_adv_link'        => $pro_link,
					'assign_to_adv_link_text'   => __( 'Upgrade to PRO →' ),
					'assign_to_fields_adv'      => __( 'Fields are available in PRO version.' ),
					'assign_to_fields_adv_link' => $pro_link,
					'assign_to_fields_adv_link_text'   => __( 'Upgrade to PRO →' ),
					'price_adv'                 => __( 'Price fields are available in PRO version.' ),
					'price_adv_link'            => $pro_link,
					'price_adv_link_text'       => __( 'Upgrade to PRO →' ),
					'number_step'               => $number_step
				)
			);

			wp_register_style( 'fpf_react_select', 'https://unpkg.com/react-select/dist/react-select.css', array(), null );
			wp_enqueue_style( 'fpf_react_select' );

            wp_register_style( 'fpf_admin', trailingslashit( $this->get_plugin_assets_url() ) . 'css/admin' . $suffix . '.css', array(), $this->scripts_version );
            wp_enqueue_style( 'fpf_admin' );
		}
	}

    public function init() {
        require_once ( 'classes/fpf-product-fields.php' );
        $this->product_fields = new FPF_Product_Fields( $this );
        require_once ( 'classes/fpf-product.php' );
        $this->product = new FPF_Product( $this, $this->product_fields );
        require_once ( 'classes/fpf-cart.php' );
        $this->cart = new FPF_Cart( $this, $this->product_fields, $this->product );
        require_once ( 'classes/fpf-post-type.php' );
	    $this->fpf_post_type = new FPF_Post_Type( $this, $this->product_fields );
    }

    public function hooks() {
        parent::hooks();
	    add_filter( 'script_loader_tag', array( $this, 'script_loader_tag' ), 10, 3 );
	    add_action( 'init', array($this, 'init_polylang') );
	    add_action( 'admin_init', array($this, 'init_wpml') );
    }

	function init_polylang() {
		if ( function_exists( 'pll_register_string' ) ) {
			$this->product_fields->init_polylang();
		}
	}

	function init_wpml() {
		if ( function_exists( 'icl_register_string' ) ) {
			$this->product_fields->init_wpml();
		}
	}


	public static function get_instance( $plugin_data ) {
        if ( self::$_instance == null ) {
            self::$_instance = new self( $plugin_data );
        }
        return self::$_instance;
    }

    public function script_loader_tag( $tag, $handle, $src ) {
	    // Check that this is output of JSX file
	    if ( 'fpf_admin' == $handle ) {
		    $tag = str_replace( "<script type='text/javascript'", "<script type='text/babel'", $tag );
	    }
	    return $tag;
    }

	public function links_filter( $links ) {
		$pl = get_locale() === 'pl_PL';
		$domain = 'net';
		$domain_wp = '';
		if ( $pl ) {
			$domain = 'pl';
			$domain_wp = 'pl.';
		}
		$plugin_links = array(
			'<a href="' . admin_url( 'edit.php?post_type=fpf_fields') . '">' . __( 'Settings', 'flexible-product-fields' ) . '</a>',
			'<a href="https://www.wpdesk.' . $domain . '/docs/flexible-product-fields-woocommerce/?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-product-fields-docs-link">' . __( 'Documentation', 'flexible-product-fields' ) . '</a>',
			'<a href="https://' . $domain_wp . 'wordpress.org/support/plugin/flexible-product-fields">' . __( 'Support', 'flexible-product-fields' ) . '</a>',
		);

        $pro_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/flexible-product-fields-pro-woocommerce/' : 'https://www.wpdesk.net/products/flexible-product-fields-pro-woocommerce/';
        $utm = '?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-product-fields-plugins-upgrade-link';

        if ( ! wpdesk_is_plugin_active( 'flexible-product-fields-pro/flexible-product-fields-pro.php' ) )
            $plugin_links[] = '<a href="' . $pro_link . $utm . '" target="_blank" style="color:#d64e07;font-weight:bold;">' . __( 'Upgrade', 'flexible-shipping' ) . '</a>';

		return array_merge( $plugin_links, $links );
	}

}

$flexible_product_fields_plugin_data = $plugin_data;

function flexible_product_fields_init() {
	global $flexible_product_fields_plugin_data;
	$GLOBALS['plugin_template'] = new Flexible_Product_Fields( $flexible_product_fields_plugin_data );
}
flexible_product_fields_init();


add_action( 'plugins_loaded', 'flexible_product_fields_plugins_loaded', 9 );
function flexible_product_fields_plugins_loaded() {
	if ( !class_exists( 'WPDesk_Tracker' ) ) {
		include( 'inc/wpdesk-tracker/class-wpdesk-tracker.php' );
		WPDesk_Tracker::init( basename( dirname( __FILE__ ) ) );
	}
}


/**
 * Checks if Flexible Product Fields PRO is active
 *
 */
function is_flexible_products_fields_pro_active() {
	return wpdesk_is_plugin_active( 'flexible-product-fields-pro/flexible-product-fields-pro.php' );
}


if ( !function_exists( 'wpdesk__' ) ) {
    function wpdesk__( $text, $domain ) {
        if ( function_exists( 'icl_sw_filters_gettext' ) ) {
            return icl_sw_filters_gettext( $text, $text, $domain, $text );
        }
        if ( function_exists( 'pll__' ) ) {
            return pll__( $text );
        }
        return __( $text, $domain );
    }
}

if ( !function_exists( 'wpdesk__e' ) ) {
    function wpdesk__e( $text, $domain ) {
        echo wpdesk__( $text, $domain );
    }
}
