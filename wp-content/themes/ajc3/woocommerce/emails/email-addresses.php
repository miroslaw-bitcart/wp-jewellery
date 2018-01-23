<?php
/**
 * Email Addresses
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><table cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">

	<tr>

		<td valign="top" width="50%">

			<h3 style="text-align:left;font-size:14px;font-weight:normal;color:#999;font-family:georgia,serif;margin-bottom:6px;"><?php _e( 'Billing address', 'woocommerce' ); ?></h3>

			<p style="font-family:georgia,serif;"><?php echo $order->get_formatted_billing_address(); ?></p>

		</td>

		<?php if ( get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>

		<td valign="top" width="50%">

				<h3 style="text-align:left;font-size:14px;font-weight:normal;color:#999;font-family:georgia,serif;margin-bottom:6px;"><?php _e( 'Delivery address', 'woocommerce' ); ?></h3>

			<p style="font-family:georgia,serif;"><?php echo $shipping; ?></p>

		</td>

		<?php endif; ?>

	</tr>

</table>