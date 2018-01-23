<?php hm_get_template_part( 'products/heading-bar' ); ?>

<ul class="shop_view" id="products" data-bind="foreach: products">
	<li>
		<?php hm_get_template_part( 'products/grid-product', array( 'quick_view' => true, 'flip_on_hover' => true ) ); ?>
	</li>
</ul>
<?php global $wp_query; ?>
<p class="centered" data-bind="visible: ready() && !products().length, html: 'Sorry, there are no items matching your criteria'"></p>