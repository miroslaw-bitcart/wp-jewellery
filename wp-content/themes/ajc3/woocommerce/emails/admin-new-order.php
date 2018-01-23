<?php
/**
 * Admin new order email
 *
 * @author WooThemes
 * @package WooCommerce/Templates/Emails/HTML
 * @version 2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( 'You have received an order from %s. Their order is as follows:', 'woocommerce' ), $order->billing_first_name . ' ' . $order->billing_last_name ); ?></p>

<?php do_action( 'woocommerce_email_before_order_table', $order, true ); ?>

 <h2 style="color:#999;font-family:helvetica,sans;text-transform:uppercase;font-weight:normal;font-size:11px;letter-spacing:0.1em;text-align:left;margin:0 0 18px 0"><?php printf( __( 'Order %s', 'woocommerce'), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( woocommerce_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border:1px solid #eee;font-weight:normal;font-size:14px;color:#999;"><?php _e( 'Item', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;font-size:14px;color:#999;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( false, true ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" style="text-align:left; border: 1px solid #eee; font-weight: normal; font-family:georgia,serif; font-size:14px;color:#999; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; font-weight: normal; font-family:georgia,serif; font-size:16px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action('woocommerce_email_after_order_table', $order, true); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, true ); ?>

<h2 style="color:#999;font-family:georgia,serif;font-weight:normal;font-size:14px;text-align:left;margin: 36px 0 0 0;"><?php _e( 'Customer details', 'woocommerce' ); ?></h2>

<?php if ( $order->billing_email ) : ?>
	<p><?php _e( 'Email:', 'woocommerce' ); ?> <?php echo $order->billing_email; ?></p>
<?php endif; ?>
<?php if ( $order->billing_phone ) : ?>
	<p><?php _e( 'Tel:', 'woocommerce' ); ?> <?php echo $order->billing_phone; ?></p>
<?php endif; ?>

<?php woocommerce_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>