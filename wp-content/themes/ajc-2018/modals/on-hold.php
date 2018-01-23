		<?php $product = new AJC_Product( $template_args['product'] ); ?>

		<?php //hm_get_template_part( 'modals/partials/product-unavailable', array( 'product' => $product, 'status' => 'Sold' ) ); ?>

		<div class="product modal">

			<div class="border-bottom clearfix">
				<div class="left"><?php echo get_the_post_thumbnail( $product->get_id(), ( 'product-x-small' ) ); ?></div>
				<div class="headings left small-space-left">
					<h1 itemprop="name"><?php echo $product->get_title(); ?></h1>
					<h3 class="left"><?php echo $product->get_period(); ?></h3>
					<h3 class="right sku"><em>№</em> <?php echo $product->get_sku(); ?></h3>
				</div>
				<h2 itemprop="price" class="right"><?php echo $product->get_price_html(); ?></h2>
			</div>

			</div>

			<div class="left-col">
				<div class="description clearfix">
					<p>Please use the form on the right to enquire about this item.</p>
					<p>Alternatively you can call us directly on +44 (0)20 7206 2477 or email us at <a href="mailto:enquiries@antiquejewellerycompany.com">enquiries@antiquejewellerycompany.com</a>.</p>
					<h3>Want to reserve this item?</h3> 
					<p>Any item may be reserved for up to 72 hours. For longer, let us know and we will try to accommodate your needs. Reserving an item does not occur automatically so there may be a short delay while we recieve your request.<p>
					<h3>Want to view this item in person?</h3>
					<p>You can arrange an appointment at our central London shop by filling in <a href="#">this form</a>.</p>
					<h3>Ready to buy?</h3>
					<p>To purchase this item click <em>Add to Basket</em>. When you are happy, click <em>Shopping Bag</em> in the top right of the screen and follow our simple and secure checkout. Alternatively you can call us directly on +44 (0)20 7206 2477 to process the payment manually.</p>
				</div>
			</div>

			<div class="right-col">

				<?php $form = Archetype_Form::get( 'enquire' ); ?>

				<form action="" method="POST" id="ajc_enquiry_form" class="modal-form clearfix">
					<?php $form->messages(); ?>
					<?php wp_nonce_field( AT_USER_NONCE ); ?>
					<?php $name_field = $form->get_field( 'ajc_enquiry_name' ); 
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
					<?php $pid = $form->get_field( 'ajc_enquiry_product' );
					$pid->opts['value'] = $template_args['product'];
					$pid->show_field(); ?>
					<?php $form->get_field( 'ajc_enquiry_query' )->show_field(); ?>
					<button type="submit" class="medium black button">Send Enquiry</button>
				</form>

			</div>

		</div>