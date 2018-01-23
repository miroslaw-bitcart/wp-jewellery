<?php
/**
 * @package ajc.frontend
 */

class Archetype_Signup_Form_Processor extends Archetype_Form_Processor {

	function process( $fields ) {

		foreach( $fields as $field )
			$fielddata[$field->name] = $field->get_posted_value();

		$user = $this->do_signup( $fielddata );

		if ( is_wp_error( $user ) ) {
			$this->add_error( $user );
			return;
		}

		$user->login();

		return true;
	}

	function succeed( $data ) {
		tn_add_message( 'success', 'Your account has been created' );
		wp_redirect( '/', $status = 302 );
		die();
	}

	/**
	 * Attempt to register the user
	 *
	 * @return mixed WP_Error|User user on success, otherwise false
	 */
	private function do_signup( $fielddata ) {

		$user = User::register_by_email( $fielddata['email'], $fielddata['password_1'], $fielddata['username'] );
		
		if( is_wp_error( $user ) )
			return $user;

		// try to update the user with firstname and lastname from the form

		$userdata = array( 
			'ID' => $user->get_id(),
			'first_name' => $fielddata['first_name'],
			'last_name' => $fielddata['last_name'],
			'nicename' => $fielddata['first_name'] . ' ' . $fielddata['last_name'] 
		);

		wp_update_user( $userdata );

		error_log( print_r( $user, true ) );

		return $user;
	}

}

class Archetype_Account_Form_Processor extends Archetype_Form_Processor {

	function process( $fields ) {
		$user = User::current_user();
		foreach( $fields as $field ) {
			$user->update_meta( $field->name, $field->get_posted_value() );
		}
		if( isset( $_POST['gender'] ) ) {
			$user->set_gender( $_POST['gender'] ); // will check for ok value
		}
	}

}

class Archetype_Enquire_Form_Processor extends Archetype_Form_Processor {

	function process( $fields ) {
	
		foreach( $fields as $field )
			$fielddata[$field->name] = $field->get_posted_value();

		$data = $this->get_base_fields( $fielddata );

		$email = hm_get_template_part( 'emails/enquiry', array_merge(
			array( 'return' => true ),
			$data ) );

		// send the email
		wp_mail( get_option( 'admin_email' ), 'Enquiry', $email );
		
		return $fielddata['ajc_enquiry_product']; // pass id back to success func so we can redirect properly
	}

	protected function get_base_fields( $fielddata ) {

		if( $fielddata['ajc_enquiry_product'] )		
			$product = new AJC_Product( $fielddata['ajc_enquiry_product'] );
		else 
			$product = false;

		$data = array( 
			'product' => $product,
			'name' => $fielddata['ajc_enquiry_name'],
			'email' => $fielddata['ajc_enquiry_email'],
			'query' => $fielddata['ajc_enquiry_query'],
			'user_link' => is_user_logged_in() ? admin_url( 'user-edit.php?user_id=' . get_current_user_id(), 'http' ) : false,
		);
		
		return $data;
	}

	function succeed( $product_id ) {
		tn_add_message( 'success', 'Thank you for your enquiry, you will hear from us shortly' );
		wp_redirect( get_permalink( $product_id ), $status = 302 );
		die();
	}

}

class Archetype_Viewing_Form_Processor extends Archetype_Enquire_Form_Processor {

	function process( $fields ) {

		foreach( $fields as $field )
			$fielddata[$field->name] = $field->get_posted_value();

		$data = $this->get_base_fields( $fielddata );

		$data = array_merge( $data, array(
			'date' => $fielddata['ajc_enquiry_date'],
			'time' => $fielddata['ajc_enquiry_time'] 
			)
		);

		$email = hm_get_template_part( 'emails/viewing', array_merge(
			array( 'return' => true ),
			$data 
		) );

		// send the email
		wp_mail( get_option( 'admin_email' ), 'Viewing Enquiry', $email );
		
		return $fielddata['ajc_enquiry_product']; // passed to success func so we can redirect properly
	}

	function succeed( $product_id ) {
		tn_add_message( 'success', 'Thank you for your enquiry, you will hear from us shortly' );
		wp_redirect( get_permalink( $product_id ), $status = 302 );
		die();
	}
}

class Archetype_Email_Form_Processor extends Archetype_Form_Processor {

	function process( $fields ) {

		$user = User::current_user();
		foreach( $fields as $field ) {
			$user->update_meta( $field->name, $field->get_posted_value() );
			$field->opts['value'] = $field->get_posted_value();
		}
	}
}


class Archetype_Survey_Form_Processor extends Archetype_Form_Processor {

	function process( $fields ) {

		$user = User::current_user();
		$tastes = array();

		foreach ( $fields as $field ) {
			$tastes[$field->name] = array_map( 'intval', $field->get_posted_value() );
		}

		$user->update_meta( AJC_USER_TASTES, $tastes );

		return true;
	}

	function succeed( $data ) {
		tn_add_message( 'success', 'Preferences saved' );
		wp_redirect( site_url( '/recommended' ) ); die();
	}
}