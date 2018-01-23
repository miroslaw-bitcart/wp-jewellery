<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in()  || ! $checkout->enable_signup ) return;

$info_message = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
?>

<div class="coupon clearfix space-below">

	<a href="#" class="showlogin light-grey-background small-padded">
		<strong class="small-space-right"><span class="icon-user space-right"></span><?php echo esc_html( $info_message ); ?></strong><small><?php _e( 'Click here to log in', 'woocommerce' ); ?></small><span class="icon-angle-down space-left right"></span>
	</a>

	<?php
		woocommerce_login_form(
			array(
				'message'  => __( '<small class="space-below">If you have shopped with us before, please enter your details below. If you are a new customer please proceed to the Billing and Shipping forms below.</small>', 'woocommerce' ),
				'redirect' => get_permalink( woocommerce_get_page_id( 'checkout') ),
				'hidden'   => true,
			)
		);
	?>

</div>