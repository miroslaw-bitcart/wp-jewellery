<?php get_header(); ?>
<?php hm_get_template_part( 'sidebar-shop' ); ?>
<div class="taxonomy-header centered type">
    <h2>
    <?php  global $wp_query;
        $term = $wp_query->get_queried_object();
        echo $term->name;?>
    </h2>
</div>
<div class="content shop">
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
    <p class="centered" data-bind="visible: ready() && !products().length, html: 'Sorry, there are no items matching your criteria'"></p>
   <?php woocommerce_pagination();?>
</div>
<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    </div>
    </div>
</div>
<?php get_footer(); ?>