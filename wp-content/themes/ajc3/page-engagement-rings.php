<?php 
global $wp_query;

if(isset($_GET['view']) && $_GET['view']=='all'){
    $per_page = -1;
}else{
    $per_page = 60;
}
$wp_query = new WP_Query(
    array(
        'tax_query' => array(
            array(
            'taxonomy' => 'type',
            'field' => 'slug',
            'terms' => 'antique-engagement-rings')
        ),
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

<div class="taxonomy-header clearfix">
   <h2>Engagement Rings</h2>
</div>

<div class="content shop full-width new-arrivals">
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
    <?php woocommerce_pagination();?>
</div>

<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    </div>
    </div>
</div>

<?php get_footer(); ?>  