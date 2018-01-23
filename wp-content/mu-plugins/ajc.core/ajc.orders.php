<?php
/**
 * @package ajc.orders
 */

define( 'AJC_TRACKING_META', 'ajc_tracking_code' );
define( 'AJC_SHIPPING_SERVICE_META', 'ajc_shipping_service' );
// define( 'AJC_PACKAGING_META', 'ajc_packaging' );
define( 'AJC_AFTERSHIP_META', 'ajc_aftership' );

/**
 * Tidy class to override WC checkout fields
 */
//class AJC_Checkout {
//
//	var $checkout = array();
//
//	
//
//	function filter_checkout( $fields_array ) {
//		return array_replace( $fields_array, $this->checkout );
//	}
//
//	function save_fields( $order_id, $posted ) {
//		if( isset( $_POST['packaging_'] ) ) // wc adds a underscore
//			update_post_meta( $order_id, AJC_PACKAGING_META, $_POST['packaging_'] );
//	}
//
//
//}
//
//new AJC_Checkout;

class AJC_Order extends Post {

	function get_tracking_info() {
		
		$transient = 'ajc_tracking_' . $this->get_id();

		/*if( $info = get_transient( $transient ) ) {

			return $info;

		} else {*/

			$code = $this->get_meta( AJC_TRACKING_META, true );
			$courier = $this->get_meta( AJC_SHIPPING_SERVICE_META, true );
			
			if( !$code || !$courier )
				return false;

			$aftership = AJC_AfterShip::get_instance();
			$result = $aftership->get_tracking_info( $code , $courier );

			//set_transient( $transient, $result, 1200 );
			return $result;

		//}


	}
}

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ajc_metaboxes( array $meta_boxes ) {

	$meta_boxes[] = array(
		'title' => 'Tracking information',
		'pages' => 'shop_order',
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(

			array( 'id' => AJC_TRACKING_META, 'name' => 'Tracking Code', 'type' => 'text', 'cols' => 12 ),
			array( 'id' => AJC_SHIPPING_SERVICE_META, 'name' => 'Shipping Service', 'type' => 'select', 'options' => array(
				array( 'name' => 'Royal Mail', 'value' => 'royal-mail' ),
				array( 'name' => 'FedEx', 'value' => 'fedex' ) 
			) ),
			array( 'id' => AJC_AFTERSHIP_META, 'name' => 'Aftership status', 'type' => 'text', 'readonly' => true )
		)
	);

	//$meta_boxes[] = array(
	//	'title' => 'Packaging preferences',
	//	'pages' => 'shop_order',
	//	'context'    => 'normal',
	//	'priority'   => 'high',
	//	'show_names' => true, // Show field names on the left
	//	'fields' => array(
	//		array( 'id' => AJC_PACKAGING_META, 'name' => 'Selected Packaging', 'type' => 'text', 'cols' => 12, 'readonly' => true )
	//	)
	//);

	return $meta_boxes;

}
add_filter( 'cmb_meta_boxes', 'ajc_metaboxes' );

add_action( 'save_post', function( $post_id ) {
	
	if ( is_admin() && !wp_is_post_revision( $post_id ) ) {

		// send the tracking code to aftership

		if( 'shop_order' !== get_post_type( $post_id ) )
			return;

		if( !isset( $_POST['meta'] ) )
			return;
		
		$meta = $_POST['meta'];

		$post = new Post( $post_id );

		$new_code = end( $_POST[AJC_TRACKING_META] );
		$new_carrier = end( $_POST[AJC_SHIPPING_SERVICE_META] );

		$existing_code = $post->get_meta( AJC_TRACKING_META, true );
		$existing_carrier = $post->get_meta( AJC_SHIPPING_SERVICE_META, true );

		if( $existing_code == $new_code && $existing_carrier == $new_carrier )
			return;

		$aftership = AJC_AfterShip::get_instance();

		try {
			$success = $aftership->submit_tracking_code( $new_code, $new_carrier );
			if( is_wp_error( $success ) ) {
				$post->update_meta( AJC_AFTERSHIP_META, $success->get_error_message() );
			} else if ( $success ) {
				$post->update_meta( AJC_AFTERSHIP_META, 'Registered OK' );
			}
		} catch( Exception $e ) {
			error_log( $e->getMessage() );
		}

	}
	
});

add_filter( 'woocommerce_checkout_must_be_logged_in_message', function() {
	tn_add_message( 'success', 'You need to register before visiting the checkout' );
	wp_redirect( '/signup' ); die();
} );