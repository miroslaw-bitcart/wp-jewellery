<?php
/**
 * Order tracking form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;
?>

<aside class="static"><?php get_sidebar( "contact" ); ?></aside>

<div class="content static">

	<div class="left-col">
		<img src="<?php bloginfo('template_directory'); ?>/assets/images/static-pages/visit-us.jpg" alt="Visit Us">
	</div>

	<div class="right-col">
		<h2>Track Your Order</h2>
		<p>To track your order please enter your Order ID in the box below and press the button. This was given to you on your receipt and in the confirmation email you should have received.</p>
		<form action="<?php echo esc_url( get_permalink($post->ID) ); ?>" method="post" class="track_order modal-form clearfix">
			<label for="orderid"><?php _e( 'Order ID', 'woocommerce' ); ?></label> <input class="input-text" type="text" name="orderid" id="orderid" placeholder="<?php _e( 'Found in your order confirmation email', 'woocommerce' ); ?>" />
			<label for="order_email"><?php _e( 'Billing Email', 'woocommerce' ); ?></label> <input class="input-text" type="text" name="order_email" id="order_email" placeholder="<?php _e( 'Email address you used during checkout', 'woocommerce' ); ?>" />
			<input type="submit" class="medium silver button" name="track" value="<?php _e( 'Track', 'woocommerce' ); ?>" />
			<?php $woocommerce->nonce_field('order_tracking') ?>
		</form>
	</div>

</div>