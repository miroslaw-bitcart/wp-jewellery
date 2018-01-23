<?php $ajc_product = new AJC_Product( $template_args['product'] ); ?>
<?php global $post, $product; $product = $ajc_product->wc_product; ?>
<?php setup_postdata( $ajc_product->_post );
$post =  $ajc_product->_post; ?>

<div class="add-to-cart-modal product" data-id="<?php echo get_the_ID(); ?>">

	<div class="product-images left relative" data-gallery-setup="false">
	
		<?php echo $ajc_product->get_thumbnail( 'product-x-small' ); ?>
		
	</div>

	<div class="product-description right">

		<h1><?php echo $ajc_product->get_title(); ?></h1>
	
		<h3 class="left"><?php echo $ajc_product->get_period(); ?></h3>

		<h3 class="right sku">
			<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku() ) : ?>
				<span itemprop="productID"><?php _e('№', 'woocommerce'); ?> <?php echo $product->get_sku(); ?></span>
			<?php endif; ?>
		</h3>

		<div itemprop="description" class="description clearfix"><?php echo apply_filters( 'the_content', $ajc_product->_post->post_content ); ?></div>


</div>