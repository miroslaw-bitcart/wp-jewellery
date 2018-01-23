<?php $ajc_product = new AJC_Product( $template_args['product'] ); ?>
<?php global $post, $product; $product = $ajc_product->wc_product; ?>
<?php setup_postdata( $ajc_product->_post );
$post =  $ajc_product->_post; ?>

<div class="add-favourite-modal product centered" data-id="<?php echo get_the_ID(); ?>">

	<h1 class="icon-heart"></h1>
	<h1 class="space-below">Added to your Favourites</h1>

	<div class="left border-top clearfix">
		<div class="image-wrap left" data-gallery-setup="false">
			<?php echo $ajc_product->get_thumbnail( 'product-x-small' ); ?>
		</div>
		<div class="info-wrap left">
			<h2 class="left"><?php echo $ajc_product->get_title(); ?></h2>
			<h3 class="left"><?php echo $ajc_product->get_price_html(); ?></h3>
			<h3 class="sku right">
				<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku() ) : ?>
					<span itemprop="productID"><em><?php _e('№', 'woocommerce'); ?></em> <?php echo $product->get_sku(); ?></span>
				<?php endif; ?>
			</h3>
		</div>
	</div>

</div>