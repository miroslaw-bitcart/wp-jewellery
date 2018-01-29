<?php get_header(); ?>
<?php hm_get_template_part( 'sidebar-shop' ); ?>
<div class="content shop">
    <div class="taxonomy-header <?php if(is_shop()) { echo "search";} ?> centered type">
    <?php
        if(is_shop()) {
            if( $_GET['s'] != '')
                echo '<h3>Search results: <em>'.get_search_query().'</em></h3>';
            else
                echo '<h2>Shop</h2>';
        } else {
            echo '<h2 data-bind=text: allTitle()></h2>';
        }
    ?>
    </div>
    <?php woocommerce_catalog_ordering();?>
    <!--<?php woocommerce_pagination();?>-->
    <?php woocommerce_result_count();?>
    <ul class="dynamic-products main-shop" id="products">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <li>
                <?php hm_get_template_part( 'products/grid-product', array( 'quick_view' => true, 'flip_on_hover' => true ) ); ?>
                </li>
            <?php endwhile; // end of the loop. ?>
    <?php endif; ?>

    <?php
        if( $_GET['s'] != '')
            echo '
                <li class="enquiry">
                <h3>Looking for<br>something specific?</h3>
                <p>Tell us and we\'ll find it for you</p>
                <a href="/contact" class="silver button block">Contact</a>
                </li>
            ';
    ?>

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
