<?php do_action( 'ajc_before_product_page' ); ?>
<?php get_header(); ?>
<?php do_action('woocommerce_before_main_content'); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php $ajc_product = new AJC_Product( get_the_ID() ); ?>
	<?php /* show woocommerce messages */ do_action( 'woocommerce_before_single_product' );?>
	<?php hm_get_template_part( 'products/single-product', array( 'ajc_product' => $ajc_product ) ); ?>
	<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php endwhile; // end of the loop. ?>		
	
<?php do_action( 'woocommerce_after_main_content' ); ?>
<?php get_footer(); ?>