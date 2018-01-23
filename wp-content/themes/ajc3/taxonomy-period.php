<?php get_header();
$main_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
$terms = get_terms("period");
?>

<div class="taxonomy-header full-width period clearfix">
	<h2>
		<?php  global $wp_query;
	    $term = $wp_query->get_queried_object();
	    echo ajc_strip_date_range( $term->name ); ?>
	</h2>
	<div class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<?php echo $main_term->name; ?>
			<span class="ion-chevron-down"></span>
		</a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<?php foreach ($terms as $term) : ?>
				<li>
					<a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<!-- Tab Menu -->

<div class="term-tabset clearfix" data-spy="affix" data-offset-top="265">
	<ul role="tablist">
		<li><a href="#introduction" role="tab" data-toggle="tab">Introduction</a></li>
		<li><a href="#characteristics" role="tab" data-toggle="tab">Characteristics</a></li>
		<li class="active"><a href="#collection" role="tab" data-toggle="tab">Our Collection</a></li>
		<li class="look-book"><a href="#look-book" role="tab" data-toggle="tab">Look Book</a></li>
	</ul>
</div>

<div class="tab-content period content shop full-width clearfix">

	<!-- Introduction -->
	<div class="tab-pane fade wrapper" id="introduction">
		<?php echo get_field( 'introduction', $main_term->taxonomy . '_' . $main_term->term_id ); ?>
	</div>

	<!-- Characteristics -->
	<div class="tab-pane fade wrapper" id="characteristics">
		<?php echo get_field( 'characteristics', $main_term->taxonomy . '_' . $main_term->term_id ); ?>
	</div>

	<!-- Collection -->
	<div class="tab-pane fade in active" id="collection">
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

 	<!-- Look Book -->
  	<div class="tab-pane fade wrapper" id="look-book">
		<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
  		<div class="pinterest padding-top centered">
  			<a data-pin-do="embedBoard" href="<?php echo get_field( 'pinterest', $term->taxonomy . '_' . $main_term->term_id ); ?>" data-pin-scale-width="80" data-pin-scale-height="320" data-pin-board-width="400">Pinterest</a>
  			
  		</div>
  	</div>

</div>
<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    </div>
    </div>
</div>
<?php get_footer(); ?>