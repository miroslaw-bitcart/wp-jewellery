<?php get_header(); ?>

<?php $user = new AJC_User( get_current_user_id() ); ?>
<?php $recommendation = $user->get_recommendation( 12 ); ?>
<?php ajc_server_products( $recommendation->get_query(), false, 'genericProducts' ); ?>

<div class="wrapper narrow recommended-products your-interests">

	<?php if( !$recommendation->is_tailored() ) : ?>
		<h2 class="border-bottom">Here are a few of our latest finds for you to enjoy</h2>
	<?php else : ?>
		<h2 class="border-bottom">To get you started, here are a few items we think you may like</h2>
	<?php endif; ?>

	<h3>Click on the <span class="icon-heart"></span> to add an item to your favourites</h3>
	<h3>You can manage your favourites by clicking the <em>My Favourites</em> link at the top of the page</h3>

	<p class="clearfix"><a href="#" data-bind="click: favouriteAll" class="small silver button space-above"><span class="icon-heart"></span>Favourite all</a></p>

	<ul class="generic-products" id="products" data-bind="foreach: products">
		<li class="single_option">
			<?php hm_get_template_part( 'products/grid-product', array( 'favourite' => true, 'flip_on_hover' => true, 'quick_view' => false, 'link' => false, 'price' => false ) ); ?>
		</li>
	</ul>

	<p class="clearfix centered"><a href='/?new_signup=1'><button class="medium black button space-above" type="submit">OK, take me to the Site!<span class="ion-ios7-arrow-forward space-left"></span></button></a></p>

</div>

<?php get_footer(); ?>