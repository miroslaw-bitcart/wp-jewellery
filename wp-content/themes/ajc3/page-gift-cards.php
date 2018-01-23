<?php get_header(); ?>	

<div class="wrapper inspiration">

	<?php do_action( 'woocommerce_before_single_product' ); ?>

	<div class="taxonomy-header centered">
	    <h2>Gift Cards</h2>
	</div>

	<div class="content gift-cards centered">

		<h3>The perfect present for antique jewellery lovers</h3>

		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/100.jpg" alt="100 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=22321' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/200.jpg" alt="200 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=54135' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/300.jpg" alt="300 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=54139' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/500.jpg" alt="500 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=54142' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/750.jpg" alt="750 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=54145' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/1000.jpg" alt="1000 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=54147' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/2000.jpg" alt="2000 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/add-to-cart=54149' ) ); ?>">Add to Bag</a>
		</div>
		<div class="quarter">
			<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/gift-cards/5000.jpg" alt="5000 pounds gift card">
			<a class="black small button" href="<?php echo esc_url( home_url( '/?add-to-cart=54151' ) ); ?>/">Add to Bag</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>