<?php get_header(); ?>
	<div class="centered space-above space-below empty">
		<h2>Sorry, this page doesn't exist!</h2>
		<?php do_action('woocommerce_cart_is_empty'); ?>
		<hr>
		<img class="space-above" src="<?php bloginfo('template_directory'); ?>/assets/images/misc/bertie.jpg" alt="Bertie">
	</div>
<?php get_footer(); ?>