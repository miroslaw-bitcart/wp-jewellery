<div class="content">

	<div class="taxonomy-header centered border-bottom"><h2>My Orders</h2></div>

	<?php global $woocommerce;

	$customer_id = get_current_user_id();

	$args = array(
	    'meta_key'        => '_customer_user',
	    'meta_value'	  => $customer_id,
	    'post_type'       => 'shop_order',
	    'post_status'     => 'publish'
	);
	$customer_orders = get_posts($args);

	if ($customer_orders) :
	?>

	<table class="shop_table my_account_orders space-above">

		<thead>
			<tr>
				<th class="order-date"><span class="nobr">Date</span></th>
				<th class="order-number"><span class="nobr"><?php _e('Order No.', 'woocommerce'); ?></span></th>
				<th class="order-total"><span class="nobr"><?php _e('Total', 'woocommerce'); ?></span></th>
				<th class="order-shipto"><span class="nobr"><?php _e('Deliver to', 'woocommerce'); ?></span></th>
				<th class="order-status"><span class="nobr"><?php _e('Status', 'woocommerce'); ?></span></th>
				<th class="order-tracking"><span class="nobr"><?php _e('Delivery Information', 'woocommerce'); ?></span></th>
			</tr>
		</thead>

		<tbody id="trackable"><?php
			foreach ($customer_orders as $customer_order) :
				$order = new WC_Order();
				$ajc_order = new AJC_Order( $customer_order->ID ); 

				$order->populate( $customer_order );

				$status = get_term_by('slug', $order->status, 'shop_order_status');

				?><tr class="order">
					<td class="order-date">
						<time title="<?php echo esc_attr( strtotime($order->order_date) ); ?>"><?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?></time>
					</td>

					<td class="order-number">
						<a href="<?php echo esc_url( add_query_arg('order', $order->id, get_permalink(woocommerce_get_page_id('view_order'))) ); ?>"><?php echo $order->get_order_number(); ?></a>
					</td>

					<td class="order-total">
						<?php echo $order->get_formatted_order_total(); ?>
						<?php
							$actions = array();

							if ( in_array( $order->status, array( 'pending', 'failed' ) ) )
								$actions['pay'] = array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								);

							if ( in_array( $order->status, array( 'pending', 'failed' ) ) )
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url(),
									'name' => __( 'Cancel', 'woocommerce' )
								);

							$actions['view'] = array(
								'url'  => add_query_arg( 'order', $order->id, get_permalink( woocommerce_get_page_id( 'view_order' ) ) ),
								'name' => __( 'View', 'woocommerce' )
							);

							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

							foreach( $actions as $key => $action ) {
								echo '<a href="' . esc_url( $action['url'] ) . '" class="small silver button space-above ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
							}
						?>
					</td>

					<td class="order-shipto">
						<address><?php if ($order->get_formatted_shipping_address()) echo $order->get_formatted_shipping_address(); else echo '&ndash;'; ?></address>
					</td>

					<td class="order-status">
						<?php echo ucfirst( __( $status->name, 'woocommerce' ) ); ?>
						<?php if (in_array($order->status, array('pending', 'failed'))) : ?>
							<a href="<?php echo esc_url( $order->get_cancel_order_url() ); ?>" class="cancel" title="<?php _e('Click to cancel this order', 'woocommerce'); ?>">(<?php _e('Cancel', 'woocommerce'); ?>)</a>
						<?php endif; ?>
					</td>

					<td data-id="<?php echo $customer_order->ID; ?>" class="order-tracking">
						<div class="loader"></div>
						<a id="update_tracking" data-bind="click: getStatus" href="#" class="small silver button space-below">Track Now</a>
						<p data-bind="visible: error, text: error"></p>
						<div class="tracking-report" data-bind="visible: hasTrackingData">
							<p data-bind="visible: lastCheckpointTime">Last seen: <span data-bind="text: lastCheckpointTime"></span>
							<p data-bind="visible: lastPosition">At: <span data-bind="text: lastPosition"></span>
							<p data-bind="visible: status">Status: <span data-bind="text: status"></span>
							<p data-bind="visible: message">Message: <span data-bind="text: message"></span>
							<p data-bind="visible: noDataYet">Sorry - no data is available yet</p>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
		</tbody>

	</table>

	<?php else : ?>
		<p class="centered space-above"><?php _e('You have no recent orders', 'woocommerce'); ?></p>
	<?php endif; ?>

</div>