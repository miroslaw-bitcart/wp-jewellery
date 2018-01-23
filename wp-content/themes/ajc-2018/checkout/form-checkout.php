<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $woocommerce;
?>

<div class="content checkout space-below">

	<div class="header centered clearfix">
		<div class="heading"><h1>Secure Checkout</h1></div>
	</div>

	<?php $woocommerce->show_messages();

	// If checkout registration is disabled and not logged in, the user cannot checkout
	if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
		echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
		return;
	}

	// filter hook for include new pages inside the payment method
	$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>

	<form name="checkout" class="wrapper" method="post" action="<?php echo esc_url( $get_checkout_url ); ?>">

		<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set" id="customer_details">

				<div class="left-col space-above space-below">

					<?php do_action( 'woocommerce_checkout_billing' ); ?>

				</div>

				<div class="right-col space-above space-below">

					<?php do_action( 'woocommerce_checkout_shipping' ); ?>

				</div>

			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<h3 id="order_review_heading" class="space-above border-bottom deco clearfix"><?php _e( 'Order Summary', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<?php do_action( 'woocommerce_checkout_order_review' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

</div>