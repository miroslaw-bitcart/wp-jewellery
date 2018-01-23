<?php get_header(); ?>

<?php $term = get_queried_object(); ?>
<?php $sale = new AJC_Flash_Sale( $term ); ?>

<header class="intro-container clearfix">
	<div class="half">
		<?php if( $sale->is_active() ) : ?>
		<h4><span class="icon-time small-space-right"></span>Ends in <strong><?php echo $sale->get_time_to_expiry(); ?></strong></h4>
		<?php endif; ?>
		<h1 class="small-space-above"><?php echo $sale->get_name(); ?></h1>
		<!-- <h3 class="small-space-below"><?php echo date( 'j M', $sale->get_start_time() ); ?> - <?php echo date( 'j M Y', $sale->get_end_time() ); ?></h3>-->
		<h2><?php echo $sale->get_description(); ?></h2>
	</div>
	<div class="half">
	</div>
</header>

<ul class="content shop large space-above">
	<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
		<li class="product">
			<?php hm_get_template_part( 'products/grid-product', array( 
				'product' => new AJC_Product( $post->ID ), 'quick_view' => false, 'favourite' => true, 'bindings'=> false, 'reduction' => true, 'flip_on_hover' => true ) );  ?>
		</li>
	<?php endwhile; endif; ?>
</ul>

<?php get_footer(); ?>