<?php 
global $wp_query;

if(isset($_GET['view']) && $_GET['view']=='all'){
    $per_page = -1;
}else{
    $per_page = 60;
}
$wp_query = new WP_Query(
            array(
                'post_type' => 'product',
                'posts_per_page' => $per_page,
                'paged' => get_query_var( 'paged' ),
                'meta_query' => array(
                    array('key' => AJC_P_STATUS,
                        'value' => 'available',
                        'compare' => 'IN')
                    )
                )
            );
get_header();
?>
<div class="taxonomy-header full-width latest-finds centered clearfix">
    <h2 class="small-space-below">Latest Finds</h2>
    <ul class="after">
        <li>Last Fortnight<span><?php wp_posts_in_days('days=14'); ?></span></li>
        <li>Last Month<span><?php wp_posts_in_days('days=30'); ?></span></li>
        <li>Last Year<span><?php wp_posts_in_days('days=365'); ?></span></li>
    </ul>   
</div>
<div class="content shop full-width new-arrivals">
	<ul class="dynamic-products main-shop" id="products">
    	<?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <li>
                <?php hm_get_template_part( 'products/grid-product', array( 'quick_view' => true, 'flip_on_hover' => true ) ); ?>
                </li>
            <?php endwhile; // end of the loop. ?>
        <?php endif; ?>	
	</ul>
    <?php woocommerce_pagination();?>
</div>

<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    </div>
    </div>
</div>

<?php get_footer(); ?>				