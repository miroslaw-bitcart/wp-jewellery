<?php

/**
 * @package AJC.users
 */

define( 'AJC_USER_TASTES', 'ajc_tastes' );

class AJC_User extends User {

	/**
	 * Get a recommendation object for this user
	 * @param  int $max the maximum number of products to include
	 * @return AJC_Recommendation       
	 */
	function get_recommendation( $max = false ) {

		$builder = new AJC_Recommendation_Builder;
		$prefs = $this->get_meta( AJC_USER_TASTES, true );

		if( $prefs ) {
			foreach( $prefs as $tax => $term ) {
				$builder->add_dimension( $tax, $term );
			}
		}

		if( $max ) {
			$builder->add_max( $max );
		}

		return $builder->get_recommendation();
	}
	
	/**
	 * Does the user have a product in the their cart?
	 * @param  int  $product_id to check
	 * @return boolean             
	 */
	function has_product_in_cart( $product_id ) {
		global $woocommerce;
		$cart = $woocommerce->cart->get_cart();
		if( $cart ) {
			foreach( $cart as $product ) {
				if( $product['product_id'] === $product_id ) {
					return true;
				}
			}
		}
		return false;
	}

}


add_action( 'init', function() {

		if ( !is_user_logged_in() )
			return;

		$phases = array( 
			new Archetype_Signup_Phase( array(
				'title' => 'Welcome',
				'slug' => 'welcome',
			) ),
			new Archetype_Signup_Phase( array( 
				'title' => 'What are you interested in?',
				'slug' => 'your-interests'
			) )
		);

		do_action( 'ajc_signup_funnel', new Archetype_Funnel( $phases ) );
} );

