<?php get_header(); ?>
<?php hm_get_template_part( 'sidebar-shop' ); ?>
<div class="content shop">
    <div class="taxonomy-header search centered clearfix"><h2>
        <h2>Search results: <em><?php echo get_search_query( ); ?></em></h2>
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
            <li class="enquiry">
                <p>Looking for something specific?</p>
                <p><em>Ask us and we'll find it for you.</em></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="silver button space-above block">Enquire<span class="ion-android-send"></span></a>
            </li>

        <?php endif; ?> 
    </ul>
    <p class="centered" data-bind="visible: ready() && !products().length, html: 'Sorry, there are no items matching your criteria'"></p>
    <?php woocommerce_pagination();?>
    <?php woocommerce_result_count();?>
</div>
<?php get_footer(); ?>