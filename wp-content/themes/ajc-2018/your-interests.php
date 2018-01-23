<?php
/**
 * Template Name: Your Interests
 */  
?>
<?php do_action( 'at_before_logged_in_page' ); ?>
<?php get_header(); ?>
<?php $form = Archetype_Form::get( 'survey' ); ?>

<div class="wrapper narrow your-interests">

	<form action="" method="POST" class="ajc_survey_form" id="ajc_survey_form" enctype="multipart/form-data">
		
		<ul>
			<?php $form->nonce_field(); ?>

			<?php foreach( array( AJC_TYPE_TAX, AJC_PERIOD_TAX, AJC_COLLECTION_TAX ) as $part => $_field ) : ?>
			
			<?php $field = $form->get_field( $_field ); ?>

			<div class="sequence clearfix <?php echo $_field; ?>_preferences">

				<li class="image-select checkbox_array">
					
					<h2 class="border-bottom"><?php echo $field->title; ?></h2>
						<?php if( isset( $field->subtitle ) ) : ?>
							<h2 class="border-bottom"><?php echo $field->subtitle; ?></h2>
						<?php endif; ?>

					<?php if( $part !== 0 ) : ?>
						<a href="#" class="small silver button left" onclick="signup.prev()"><span class="icon-angle-left"></span>Back</a>
					<?php endif; ?>

					<a href="#" class="small silver button right" onclick="signup.next()">Next Step<span class="ion-ios7-arrow-forward"></span></a>
					<a href="#" onclick="signup.next()" class="skip">Skip</a>
					
					<p class="clearfix left">
						<a href="#" data-bind="click: function() { selectAll( '<?php echo $field->name ; ?>' ); }" class="small silver button space-above">Select all</a>
					</p>
				
					<ul class="clearfix">
						<?php foreach( $field->get_choices() as $term ) : ?>
							<?php $term = new Term( $term ); ?>
							<?php if( $_field == 'type' ) : ?>

								<?php $product = new AJC_Product( $term->get_example_post()->ID ); ?>
								<li class="single_option" data-group="<?php echo $field->name; ?>" data-id="<?php echo $term->get_id(); ?>">
									<span class="ion-checkmark"></span>
									<?php hm_get_template_part( 'products/grid-product', array( 
										'product' => $product, 
										'bindings' => false, 
										'context' => 'form', 
										'quick_view' => false ) ) ; ?>
		
									<label>
										<input type="checkbox" data-bind="checked: <?php echo $field->name; ?>" name="<?php echo $field->name; ?>[]" value="<?php echo $term->get_id(); ?>">
										<?php echo $term->get_name(); ?>
									</label>
								</li>

							<?php else : ?>

								<li class="single_option" data-group="<?php echo $field->name; ?>" data-id="<?php echo $term->get_id(); ?>">
									<span class="ion-checkmark"></span>
									<?php echo ajc_get_taxonomy_image( $term->get_term(), 'panel-small' ); ?>
									<label>
										<input type="checkbox" data-bind="checked: <?php echo $field->name; ?>" name="<?php echo $field->name; ?>[]" value="<?php echo $term->get_id(); ?>">
										<?php echo $term->get_name(); ?>
									</label>

								</li>

							<?php endif ; ?>
						<?php endforeach; ?>
					</ul>

				</li>

			</div>

			<?php endforeach; ?>

			<?php $field = $form->get_field( AJC_PRICE_TAX ); ?>

			<div class="sequence clearfix <?php echo $field->name; ?>_preferences">

				<li class="checkbox_array image-select">

					<h2 class="border-bottom"><?php echo $field->title; ?></h2>
						
					<a href="#" class="small silver button left" onclick="signup.prev()"><span class="icon-angle-left"></span>Back</a>

					<button class="small silver button right" type="submit">Next Step<span class="ion-ios7-arrow-forward"></span></button>
					
					<p class="clearfix left">
						<a href="#" data-bind="click: function() { selectAll( '<?php echo $field->name ; ?>' ); }" class="small silver button space-above">Select all</a>
					</p>
					
					<ul class="clearfix">
						<?php foreach( $field->get_choices() as $term ) : ?>
					
							<?php $range = new AJC_Price_Range( $term ); ?>
							<?php $product = new AJC_Product( $range->get_example_post()->ID ); ?>
				
							<li class="single_option" data-group="<?php echo $field->name; ?>" data-id="<?php echo $range->get_id(); ?>">
								<span class="icon-ok"></span>
								<?php hm_get_template_part( 'products/grid-product', array( 'product' => $product, 'bindings' => false, 'context' => 'form', 'quick_view' => false ) ) ; ?>
				
								<label><input type="checkbox" data-bind="checked: <?php echo $field->name; ?>" name="<?php echo $field->name; ?>[]" value="<?php echo $range->get_id(); ?>">
									<span class="low"><?php echo number_format( $range->get_low() ); ?></span>
										<?php if( !$range->starts_at_zero() ) : ?>
											<span class="sep"> - </span>
										<?php endif; ?>
									<span class="high"><?php echo number_format( $range->get_high() ); ?></span>
								</label>
				
							</li>
						<?php endforeach; ?>
					</ul>
				</li>

				<a href="#" class="small silver button left" onclick="signup.prev()"><span class="icon-angle-left"></span>Back</a>
				
			</div>
		</ul>
	</form>
</div>

<?php get_footer(); ?>