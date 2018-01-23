<?php
global $woocommerce, $product;
$ajc_product = new AJC_Product( $product->id );
?>

<?php do_action('woocommerce_before_add_to_cart_form'); ?>

<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" id="cart-buttons" class="clearfix" method="post" enctype='multipart/form-data'>

 	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	<?php if( $ajc_product->is_sold() ) : ?>
		<div class="left status sold">
			<p><a href="#" data-toggle="modal" data-target="#enquiry-sold">Looking for something similar?</a></p>
			<p><a href="javascript:window.print()">Print out this item</a>
			<?php $type = $ajc_product->get_type(); ?></p>
			<p><a href="<?php echo $type->get_link(); ?>">Browse other <?php echo $type->get_name(); ?></a></p>
			<p><a href="/shop">Browse all jewellery for sale</a></p>
		</div>
	<?php elseif( $ajc_product->is_on_hold() ) : ?>
		<div class="left status on-hold">
			<?php $type = $ajc_product->get_type(); ?>
			<a href="javascript:window.print()">Print out this item</a>
			<p>There's still a chance! <a href="#" data-toggle="modal" data-target="#enquiry-on-hold">Join the waiting list</a></p>
			<p><a href="<?php echo $type->get_link(); ?>">Browse other <?php echo $type->get_name(); ?></a></p>
		</div>
	<?php else : ?>
		
		<button type="submit" class="black buy"><?php echo apply_filters('single_add_to_cart_text', __('Add to Bag', 'woocommerce'), $product->product_type); ?><span class="ion-bag"></span></button>

		<?php get_template_part( 'products/delivery-date');	?>	

	 	<ul>
	 		<li>
	 			<a href="#" data-toggle="modal" data-target="#enquiry-available" class="silver button left">Enquire<span class="ion-android-send"></span></a>
	 		</li> 
	 		<li>
	 			<a href="#" class="button border" data-toggle="modal" data-target="#hint">Drop a hint<span class="icon-hint"></span></a>
	 		</li>
	 	</ul>
	<?php endif; ?>

 	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>