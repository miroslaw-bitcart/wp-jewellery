<?php extract( $template_args );
$product = $ajc_product->wc_product; 
global $post, $product;
?>

<div itemscope itemtype="http://schema.org/Product" data-id="<?php echo $ajc_product->get_id(); ?>" <?php post_class(); ?>>
	<div class="wrapper">
		<div class="product-description left">	
			<div class="headings mobile clearfix">
				
				<h1 itemprop="name" id="product-title"><?php the_title(); ?></h1>

					<div itemprop="offers" class="left price clearfix" itemscope itemtype="http://schema.org/Offer">

						<?php if ( $ajc_product->is_available() ) : ?>

							<h2 itemprop="price" class="left" id="product-price"><?php echo $product->get_price_html(); ?></h2>
																			
							<div class="left space-left">
								<?php 
								$posted = get_the_date( 'U' );
								if( $posted > ( time() - 1209600 /* 14 days */ ) ) : 
									$today = new DateTime();
									$started = new DateTime();
									$started->setTimestamp( $posted );
									$difference = $started->diff( $today ); ?>
									<?php if( $difference->d === 0 ) : ?>
										<span class="added">&#43; Added Today</span>
									<?php else : ?>
										<span class="added">&#43; Added <?php echo $difference->d . ' ' . _n( 'day', 'days', $difference->d ); ?> ago</span>
									<?php endif; ?>
								<?php endif; ?>

								<div class="red right counter">
									<span class="ion-heart small-space-right left"></span>
									<?php echo do_shortcode( '[post_view]' ); ?> Admirers
								</div>
							</div>

						<?php elseif ( $ajc_product->is_sold() ) : ?>
							<h2 class="sold">Sold</h2>

						<?php elseif ( $ajc_product->is_on_hold() ) : ?>
							<h2 class="on-hold">On Hold</h2>

						<?php endif; ?>

				</div>

				<h3 class="right sku" id="product-sku" data-sku="<?php echo $product->get_sku(); ?>">
					<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku() ) : ?>
						<span itemprop="productID"><em><?php _e('№', 'woocommerce'); ?></em> <?php echo $product->get_sku(); ?></span>
					<?php endif; ?>
				</h3>
				
			</div>
		</div>

		<div class="product-images left relative">

			<?php if ( $attachment_ids = $product->get_gallery_attachment_ids() ) : ?>
				<?php $first_image = reset( $attachment_ids ); ?>
				<?php $image_src = wp_get_attachment_image_src( $first_image, 'product-x-large' ); ?>
				<div class="main-image relative">
					<a itemprop="image" data-fancybox-group="fancybox-thumb" href="<?php echo $image_src[0]; ?>" data-price="£<?php echo $product->get_price(); ?>" data-title="<?php echo $ajc_product->get_title(); ?>" data-thumb-index="0" class="zoom zoom-x-large" title="<?php the_title(); ?>">
						<?php echo wp_get_attachment_image( $first_image, 'product-large', false, array( 'class' => 'wp-post-image' ) ); ?>
						<span class="ion-arrow-expand"></span>
					</a>
				</div>
			<?php else : ?>
				<img src="<?php echo woocommerce_placeholder_img_src(); ?>" alt="Placeholder" />
			<?php endif; ?>

			<?php do_action('woocommerce_product_thumbnails'); ?>

			<div class="social left">
				<em class="blue left small-space-below small-space-above">Share this item with family and friends</em>
					<div class="addthis_toolbox addthis_default_style small-space-above clearfix">
						<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
						<a class="addthis_button_tweet"></a>
						<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
						<a class="addthis_counter addthis_pill_style"></a>
					</div>
				<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4dfb84b53e768952"></script>
			</div>
		</div>

		<div class="product-description right">
			<div class="headings desktop clearfix">
				<small class="breadcrumbs">
					<?php if(function_exists('bcn_display'))
					{
					    bcn_display();
					}?>
				</small>
				<h1 itemprop="name" id="product-title"><?php the_title(); ?></h1>

					<div itemprop="offers" class="left price clearfix" itemscope itemtype="http://schema.org/Offer">

						<?php if ( $ajc_product->is_available() ) : ?>

							<h2 itemprop="price" class="left" id="product-price"><?php echo $product->get_price_html(); ?></h2>
																			
							<div class="left space-left">
								<?php 
								$posted = get_the_date( 'U' );
								if( $posted > ( time() - 1209600 /* 14 days */ ) ) : 
									$today = new DateTime();
									$started = new DateTime();
									$started->setTimestamp( $posted );
									$difference = $started->diff( $today ); ?>
									<?php if( $difference->d === 0 ) : ?>
										<span class="added">&#43; Added Today</span>
									<?php else : ?>
										<span class="added">&#43; Added <?php echo $difference->d . ' ' . _n( 'day', 'days', $difference->d ); ?> ago</span>
									<?php endif; ?>
								<?php endif; ?>

								<div class="red right counter">
									<span class="ion-heart small-space-right left"></span>
									<?php echo do_shortcode( '[post_view]' ); ?> Admirers
								</div>
							</div>

						<?php elseif ( $ajc_product->is_sold() ) : ?>
							<h2 class="sold">Sold</h2>

						<?php elseif ( $ajc_product->is_on_hold() ) : ?>
							<h2 class="on-hold">On Hold</h2>

						<?php endif; ?>

				</div>

				<h3 class="right sku" id="product-sku" data-sku="<?php echo $product->get_sku(); ?>">
					<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku() ) : ?>
						<span itemprop="productID"><em><?php _e('№', 'woocommerce'); ?></em> <?php echo $product->get_sku(); ?></span>
					<?php endif; ?>
				</h3>
			</div>

			<div itemprop="description" class="description">		
				<?php the_content(); ?>
				<span class="vcard author"><span class="fn">
					Written by <a href="https://plus.google.com/110092914152631533251">Olly Gerrish</a>
				</span></span>
				
				<?php if( $olly = get_post_meta( $post->ID, AJC_OLLYS_NOTE, true ) ) : ?>
					<blockquote class="clearfix">
						<?php hm_get_template_part( 'products/ollys-pick', array( 'product' => $ajc_product, 'text' => $olly ) ); ?>
					</blockquote>
				<?php endif; ?>

				<?php woocommerce_template_single_add_to_cart() ;?>

				<table class="specifications">
					<?php if( $measurements = get_post_meta( $post->ID, AJC_P_MEASUREMENTS, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Measurements</td><td class="spec_data"><?php echo $measurements; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $ring_size = get_post_meta( $post->ID, AJC_P_RINGSIZE, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Ring size</td><td class="spec_data"><?php echo $ring_size; ?>
								<?php if( get_post_meta( $post->ID, AJC_P_RESIZEABLE, true ) ) : ?><br><span class="free-sizing">Eligible for <strong>Free Sizing</strong></span><br>
								<?php endif; ?>
								<a data-toggle="modal" data-target="#rings">Ring Sizing Chart</a>
							</td>
						</tr>
					<?php endif; ?>

					<?php if( $condition = get_post_meta( $post->ID, AJC_P_CONDITION, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Condition</td><td class="spec_data"><?php echo $condition; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $hallmarks = get_post_meta( $post->ID, AJC_P_HALLMARKS, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Hallmarks</td><td class="spec_data"><?php echo $hallmarks; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $date_origin = $ajc_product->get_date_origin() ) : ?>
						<tr class="spec">
							<td class="spec_title">Date &amp; Origin</td><td class="spec_data"><?php echo $date_origin; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $provenance = get_post_meta( $post->ID, AJC_P_PROVENANCE, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Provenance</td><td class="spec_data"><?php echo $provenance; ?></td>
						</tr>
					<?php endif; ?>
				</table>

				<?php if( $ajc_product->is_available() ) : ?>
					<ul class="ajc-buttons">
						<li><em>Need more info?</em><a href="#" data-toggle="modal" data-target="#enquiry-available">Ask Olly about this item</a></li>
						<li><em>Want to try it on?</em><a href="#" data-toggle="modal" data-target="#viewing">Arrange a viewing</a></li>
						<!-- <li><small class="small-space-right">New!</small><em>Want more angles?</em><a href="skype:oliviagerrish?call">See it on Skype</a></li>-->
						<li><em>Want to save for later?</em><a href="javascript:window.print()">Print out this item</a></li>
					</ul>
				<?php endif; ?>

			</div>
			
		</div>	

	</div>

	<?php if( $ajc_product->is_available() ) : ?>

		<div class="info-banner">
			<div class="wrapper">
				<section>
					<h4>Free Worldwide Delivery</h4>
					<p>
						&ndash; <strong>UK</strong> next working day before 1pm<br>
						&ndash; <strong>Europe</strong> 3-5 working days<br>
						&ndash; <strong>US &amp; ROW</strong> 5-7 working days
					</p>
					<a href="#" data-toggle="modal" data-target="#delivery">More info <span class="ion-chevron-right"></span></a>		
				</section>

				<section>
					<h4>Free Returns</h4>
					<p>If you don't like your item, return it to us for free (UK only) by 10 January 2018 for a full-money refund</p>
					<a href="#" data-toggle="modal" data-target="#returns">More info <span class="ion-chevron-right"></span></a>
				</section>
				<section>
					<h4>Free Ring Sizing</h4>
					<p>Let us know your desired size at Checkout and we will re-size your ring at no additional cost</p>
					<a href="#" data-toggle="modal" data-target="#guarantee">More info <span class="ion-chevron-right"></span></a>
				</section>
				<section>
					<h4>Fast, Secure Payment</h4>
					<p>We use PayPal's industry-leading technology to keep your information safe</p>
					<a href="#" data-toggle="modal" data-target="#payments">More info <span class="ion-chevron-right"></span></a>
				</section>
			</div>

		</div>

		<div class="info-banner centered">
			<div class="wrapper">
				<section class="half">
					<h4>The Responsible Choice</h4>
					<p>Buying antique jewellery is both ethical and eco-friendly as harmful and destructive mining processes are not needed to make an item yours. So give yourself a pat on the back!</p>
					<a href="#" data-toggle="modal" data-target="#guarantee">More info <span class="ion-chevron-right"></span></a>
				</section>
				<section class="half">
					<h4>Our 5 Point Promise</h4>
					<p>We always stay true to our core values, embodied in The AJC Guarantee:<br>Quality, Rarity, Knowledge, Peace of Mind and The Personal Touch</p>
					<a href="#" data-toggle="modal" data-target="#guarantee">More info <span class="ion-chevron-right"></span></a>
				</section>
			</div>
		</div>

	<?php endif; ?>

	<?php related_entries();?>

</div>

<?php if( $ajc_product->is_sold() ) {
	get_template_part( 'modals/partials/product-sold', array( 'status' => 'Sold' ) );
} else if ( $ajc_product->is_on_hold() ) {
	get_template_part( 'modals/partials/product-on-hold', array( 'status' => 'On Hold' ) );
} else {
	get_template_part( 'modals/partials/product-available', array() );
} ?>

<?php get_template_part( 'modals/viewing' ); ?>
<?php get_template_part( 'modals/payments' ); ?>
<?php get_template_part( 'modals/delivery' ); ?>
<?php get_template_part( 'modals/returns' ); ?>
<?php get_template_part( 'modals/guarantee' ); ?>
<?php get_template_part( 'modals/rings' ); ?>
<?php get_template_part( 'modals/packaging' ); ?>
<?php get_template_part( 'modals/hint' ); ?>
