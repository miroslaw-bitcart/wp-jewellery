<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( ! $woocommerce->cart->coupons_enabled() )
	return;

$info_message = apply_filters('woocommerce_checkout_coupon_message', __( 'Have a voucher?', 'woocommerce' ));
?>

<div class="coupon clearfix">

	<a href="#" class="showcoupon light-grey-background small-padded">
		<strong class="small-space-right"><span class="icon-gift space-right"></span><?php echo $info_message; ?></strong><small><?php _e( 'Click here to enter your code', 'woocommerce' ); ?></small><span class="icon-angle-down space-left right"></span>
	</a>

	<form class="checkout_coupon" method="post" style="display:none">
		<fieldset>
			<input name="coupon_code" class="input-text" id="coupon_code" placeholder="<?php _e( 'Voucher code', 'woocommerce' ); ?>" value="" />
			<input type="submit" class="black button" name="apply_coupon" value="<?php _e('Apply', 'woocommerce'); ?>" />
		</fieldset>
	</form>

</div>