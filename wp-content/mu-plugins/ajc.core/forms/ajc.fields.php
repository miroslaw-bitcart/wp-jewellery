<?php 

add_action( 'init', function() {

	$fields = array( 
		
		array( 
			'name' 		=> 'first_name',
			'title' 	=> 'First Name',
			'type'		=> 'text',
			'opts' 		=> array(
				'placeholder' => 'First Name' )
		),

		array( 
			'name' 		=> 'last_name',
			'title' 	=> 'Last Name',
			'type'		=> 'text',
			'opts' 		=> array(
				'placeholder' => 'Last Name' )
		),

		array( 
			'name' 		=> 'email',
			'title' 	=> 'Email Address',
			'type'		=> 'email',
			'opts' 		=> array(
				'validation' => 'User::email_can_register',
				'placeholder' => 'Email address' )
		),

		array( 
			'name' 		=> 'password_1',
			'title' 	=> 'Password',
			'type'		=> 'password',
			'opts' 		=> array(
				'placeholder' => 'Password' )
		),
		array( 
			'name' 		=> 'password_2',
			'title' 	=> 'Confirm Password',
			'type'		=> 'password',
			'opts' 		=> array(
				'placeholder' => 'Confirm Password',
				'validation' => function( $val, $form ) {
					$p1 = $form->get_field( 'password_1' );
					if( !$p1 && !$val ) {
						return new WP_Error( 'password_missing', 'You need to supply a password' );
					}
					if( $p1->get_posted_value() !== $val )
						return new WP_Error( 'password_mismatch', 'Passwords do not match' );
					}
				)
		),

		array(
			'name' => 'receive_newsletter',
			'title'		=> 'Receive Newsletter',
			'type' 		=> 'checkbox' ),
		
		array( 
			'name' => AT_FB_ID_META, 
			'title' 	=> 'Facebook ID', 
			'type' 		=> 'hidden'
			),
		array( 
			'name' => AT_FB_TOKEN_META, 
			'title' 	=> 'Facebook Token', 
			'type' 		=> 'hidden'
			),

		// signup survey fields 
		
		array(
			'name' => AJC_TYPE_TAX,
			'title' => 'What types of antique jewellery are you interested in?',
			'type' => 'checkbox_array',
			'opts' => array( 
				'choices' => function() { return get_terms( AJC_TYPE_TAX ); }
			) ),

		array( 
			'name' => AJC_PERIOD_TAX,
			'title' => 'What are your favourite antique jewellery periods?',
			'type' => 'checkbox_array',
			'opts' => array(
				'choices' => function() { return get_terms( AJC_PERIOD_TAX ); } 
			) ),

		array( 
			'name' => AJC_PRICE_TAX,
			'title' => "What what price range suits you?",
			'type' => 'checkbox_array',
			'opts' => array(
				'choices' => function() { return get_terms( AJC_PRICE_TAX ); } 
			) ),

		array( 
			'name' => AJC_COLLECTION_TAX,
			'title' => 'Here are some collections that you might like',
			'type' => 'checkbox_array',
			'opts' => array(
				'choices' => function() { return get_terms( AJC_COLLECTION_TAX ); } 
			) ),


		array( 
			'name' => 'ajc_enquiry_name',
			'title' => 'Your Name',
			'type' => 'text'
		),

		array( 
			'name' => 'ajc_enquiry_product',
			'title' => 'Your Name',
			'type' => 'hidden'
		),

		array( 
			'name' => 'ajc_enquiry_email',
			'title' => 'Your Email Address',
			'type' => 'email',
			'validation' =>'is_email'
		),

		array( 
			'name' => 'ajc_enquiry_phone',
			'title' => 'Your Phone Number',
			'type' => 'text',
			'pattern' => '[0-9]*'
		),

		array(
			'name' => 'ajc_enquiry_date',
			'title' => 'When would you like to come?',
			'type' => 'date'
		),

		array(
			'name' => 'ajc_enquiry_time',
			'title' => 'What time of day?',
			'type' => 'select',
			'opts' => array(
				'choices' => function() {
					$choices = array( 'Morning', 'Lunchtime', 'Afternoon' );
					return array_combine( $choices, $choices );
				} )
			),

		array( 
			'name' => 'ajc_enquiry_query',
			'title' => 'Your Message',
			'type' => 'textarea' 
		)

	);	

	foreach( $fields as $f ) {
		at_register_field( $f );
	}

	at_register_form( 'signup', 
		array( 
			'avatar' => false,
			'password_1' => array( 'required' => true ),
			'password_2' => array( 'required' => true ), 
			'email' => array( 'required' => true ), 
			'first_name' => array( 'required' => true ),
			'last_name' => array( 'required' => true ),
			AT_FB_TOKEN_META => false, 
			AT_FB_ID_META => false ),
		array( 'show_discrete_errors' => false ) );

	at_register_form( 'survey', array(
		AJC_TYPE_TAX => false, 
		AJC_PERIOD_TAX => false, 
		AJC_PRICE_TAX => false, 
		AJC_COLLECTION_TAX => false ),
		array( 'hook_to_page' => 'your-interests' ) );

	at_register_form( 'enquire', array(
		'ajc_enquiry_email' => array( 'required' => true ),
		'ajc_enquiry_name' => array( 'required' => true ),
		'ajc_enquiry_query' => array( 'required' => true ),
		'ajc_enquiry_product' => array( 'required' => true )
	) );

	at_register_form( 'viewing', array(
		'ajc_enquiry_name' => array( 'required' => true ),
		'ajc_enquiry_email' => array( 'required' => true ),
		'ajc_enquiry_phone' => array( 'required' => true ),
		'ajc_enquiry_date' => array( 'required' => true ),
		'ajc_enquiry_time' => array( 'required' => true ),
		'ajc_enquiry_query' => array( 'required' => true ),
		'ajc_enquiry_product' => array( 'required' => true ),
	), array( 'nonce' => 'abc' ) );

	if( is_user_logged_in() ) : 
		at_register_form( 'account', array(
			'first_name' => array( 'value' => User::current_user()->get_meta( 'first_name', true ) ),
			'last_name' => array( 'value' => User::current_user()->get_meta( 'last_name', true ) ) ) );

		at_register_form( 'email', array( 
			'receive_newsletter' => array( 'value' => User::current_user()->get_meta( 'receive_newsletter', true ) ) ) );
	endif;
} );