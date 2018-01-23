<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $woocommerce;
?>

<div class="content cart">
	<div class="taxonomy-header cart"><h2>Shopping Bag</h2></div>
	<?php $woocommerce->show_messages(); ?>
	<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
	<table class="shop_table cart" cellspacing="0">
		<?php
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">

						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image( 'grid-regular' ), $values, $cart_item_key );
								printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
							?>
						</td>

						<!-- Product Name -->
						<td class="product-name">
							<?php
								if ( ! $_product->is_visible() || ( $_product instanceof WC_Product_Variation && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );

								// Meta data
								echo $woocommerce->cart->get_item_data( $values );

	               				// Backorder notification
	               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
	               					echo '<p class="backorder_notification">' . __('Available on backorder', 'woocommerce') . '</p>';
							?>
							<p class="remove-hidden">
								<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" title="%s" class="small">Remove</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove', 'woocommerce') ), $cart_item_key );
							?>
							</p>
						</td>

						<!-- Remove Item  -->
						<td class="product-remove">
							<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" title="%s" class="small">Remove</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove', 'woocommerce') ), $cart_item_key );
							?>
						</td>

						<!-- Product price -->
						<td class="product-price">
							<?php
								$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();

								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
							?>
						</td>
					</tr>
					<?php
				}
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>

	</table>
	
	<?php if ( get_option( 'woocommerce_enable_coupons' ) == 'yes' ) { ?>
		<div class="coupon space-above space-below">
			<label for="coupon_code"><?php _e('To redeem a Gift Card or a Voucher enter the code here', 'woocommerce'); ?>:</label>
			<fieldset>
				<input name="coupon_code" class="input-text" id="coupon_code" placeholder="<?php _e( 'Voucher code', 'woocommerce' ); ?>" value="" />
				<input type="submit" class="button voucher" name="apply_coupon" value="<?php _e('Apply', 'woocommerce'); ?>" />
				<?php do_action('woocommerce_cart_coupon'); ?>
				<?php do_action('woocommerce_cart_coupon'); ?>
			</fieldset>
		</div>
		<?php $woocommerce->nonce_field('cart') ?>

	<?php } ?>

	<?php do_action( 'woocommerce_after_cart_contents' ); ?>

</div>

<aside class="cart">

	<ul class="clearfix">
		<li>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
			<?php do_action('woocommerce_cart_collaterals'); ?>
			<?php woocommerce_cart_totals(); ?>
		</li>
	</ul>

	<a href="/checkout" class="green button left space-above">Secure Checkout<span class="ion-chevron-right space-left"></span></a>

	<span class="pf pf-paypal"></span>
	<span class="pf pf-visa"></span>
	<span class="pf pf-mastercard"></span>
	<span class="pf pf-maestro"></span>
	
	<small class="space-above">
		<strong>Need any assistance with your order?</strong><br>
		&mdash; Call Us +44 (0)20 7206 2477<br>
		&mdash; <a href="mailto:enquiries@antiquejewellerycompany.com">Email us now</a>
	</small>

</aside>

</form>