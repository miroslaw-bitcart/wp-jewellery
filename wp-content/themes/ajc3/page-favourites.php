<?php get_header(); ?>

<?php $favourites = new WP_Query( array(
	'connected_type' => AJC_FAVOURITES,
	'connected_items' => wp_get_current_user() 
) );  ?>
	
	<div class="content static favourites">

		<div class="taxonomy-header centered border-bottom type"><h2>My Favourites</h2></div>

		<?php if ( $favourites->have_posts() ) : ?>

		<!-- ko with: primaryProductsView -->
			<ul id="products" class="content space-above" data-bind="foreach: products">
				<li class="hidden" data-bind="css: { hidden: false }">
					<?php hm_get_template_part( 'products/grid-product', array( 
						'bindings' => true, 
						'product' => false, 
						'favourite' => true, 
						'quick_view' => true, 
						'flip_on_hover' => true ) ); ?>
				</li>
			</ul>
		<!-- /ko -->
		<?php ajc_server_products( $favourites, false, 'favouriteProducts' ); ?>

		<?php else: ?>
			<hgroup class="centered">
				<h3>Your Favourites List is currently empty</h3>
				<h3>To get you started below are a few items you might like to keep an eye on!</h3>
			</hgroup>
		<?php endif; ?>

	</div>

	
<?php get_footer(); ?>