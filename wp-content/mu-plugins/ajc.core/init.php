<?php
/**
 * @package AJC
 */

define( 'AJC_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'AJC_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'AJC_PRODUCT_PT', 'product' );
define( 'AJC_FLASH_SALE', 'ajc_flash_sale' );

// fields 
require( 'lib/custom-meta-boxes/custom-meta-boxes.php' );

// SES
//require( 'lib/ses/ses-for-wordpress.php' );


//router
include( 'ajc.route.php' ); 

// form handlers and fields
include( 'forms/ajc.formhandlers.php' ); // form handlers
include( 'forms/ajc.fields.php' ); // form fields

// products and orders
include( 'ajc.taxonomies.php' );  // taxonomies for products
include( 'ajc.products.php' ); // product class and associated
include( 'ajc.orders.php' ); // order customisations

// interface elements
include( 'ajc.modals.php' ); //modals
//include( 'ajc.frontpage.php' ); //slider config
include( 'ajc.menus.php' ); //menus

// general API
include( 'ajc.api.php' ); // AJAX api
include( 'ajc.search.php' ); // AJAX api
include( 'ajc.admin.php' ); // admin area
include( 'ajc.functions.php' ); // generic functions
include( 'ajc.users.php' ); // user class

include( 'ajc_importer/main.php' );

add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_script( 'ajc-admin', AJC_PLUGIN_URL . 'js/admin.js' );
} );

function ajc_product_to_post() {
	p2p_register_connection_type( array(
		'name' => 'products_to_posts',
		'from' => 'product',
		'to' => 'post'
	) );
}
add_action( 'p2p_init', 'ajc_product_to_post' );

add_action( 'init', function() {
	if( isset( $_GET['fff'] ) ) {
		apply_filters( 'woocommerce_order_status_completed', 11648 );
	}
});