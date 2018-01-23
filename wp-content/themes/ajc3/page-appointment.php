<?php get_header(); ?>

	<?php at_form( 'viewing', 'abc' ); ?>

	<aside class="static" data-spy="affix" data-offset-top="190" data-offset-bottom="600"><?php get_sidebar( "contact" ); ?></aside>

	<div class="content static contact">

		<div class="left-col">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/static-pages/visit-us2.jpg" alt="Appointment">
		</div>

		<div class="right-col">
			<h2>Book an Appointment</h2>
			<p>To book an appointment at our London shop please fill in the form below:</p>

			<?php $form = Archetype_Form::get( 'viewing' ); ?>

				<form method="POST" action="" class="modal-form clearfix">
					<?php $form->messages(); ?>
					<?php wp_nonce_field( 'abc' ); ?>
					<?php 
					$name_field = $form->get_field( 'ajc_enquiry_name' ); 
					$email_field = $form->get_field( 'ajc_enquiry_email' );

					if( is_user_logged_in() ) {
						$user = User::current_user();
						$email_field->set_value( $user->get_email() );
						$email_field->opts['readonly'] = true;
						if( $displayname = $user->get_proper_name() ) {
							$name_field->set_value( $displayname );
							$name_field->opts['readonly'] = true;
						}
					} ?>

					<?php $name_field->show_field(); ?>
					<?php $email_field->show_field(); ?>
					<?php $form->get_field( 'ajc_enquiry_phone' )->show_field(); ?>
					<?php $pid = $form->get_field( 'ajc_enquiry_product' );
							$pid->opts['value'] = $product;	
						$pid->show_field();  ?>
					<?php $form->get_field( 'ajc_enquiry_date' )->show_field(); ?>
					<?php $form->get_field( 'ajc_enquiry_time' )->show_field(); ?>
					<?php $form->get_field( 'ajc_enquiry_query' )->show_field(); ?>
					<button type="submit" class="silver button">Send Viewing Request</button>
				</form>
		</div>
		
	</div>

	<?php get_footer(); ?>