<?php get_header();
$main_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
$terms = get_terms("collection");
?>

<div class="taxonomy-header full-width collection clearfix<?php echo ' ' . $main_term->slug; ?>">
	<h2 class="small-space-below">
	<?php  global $wp_query;
	    $term = $wp_query->get_queried_object();
	    echo $term->name;?>
	</h2>
	<ul class="after">
	    <li><?php echo $term->description; ?></li>
	</ul> 
</div>

<div class="content shop full-width">
    <?php woocommerce_catalog_ordering();?>
    <?php woocommerce_result_count();?>
	<ul class="dynamic-products main-shop" id="products">
		<?php if ( have_posts() ) : ?>
	        <?php while ( have_posts() ) : the_post(); ?>
	            <li>
	            <?php hm_get_template_part( 'products/grid-product', array( 'quick_view' => true, 'flip_on_hover' => true ) ); ?>
	            </li>
	        <?php endwhile; // end of the loop. ?>
	    <?php endif; ?>	
	</ul>
	<p class="centered space-above space-below" data-bind="visible: ready() && !products().length, html: 'Sorry, there are no items matching your criteria'"></p>
	<?php woocommerce_pagination();?>
</div>

<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    </div>
    </div>
</div>

<?php get_footer(); ?>