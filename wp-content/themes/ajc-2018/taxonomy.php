<?php get_header(); ?>

<?php $term = get_queried_object(); ?>

	<?php hm_get_template_part( 'sidebar-taxonomy', array( 'terms' => get_terms( $term->taxonomy ) ) ); ?>

	<div class="content">

		<div class="taxonomy-header centered border-bottom">
			<h1><?php echo $term->name; ?></h1>
			<?php if( file_exists( AJC_THEME_PATH . 'taxonomy-headers/' . $term->slug . '.php' ) ) : 
				hm_get_template_part( 'taxonomy-headers/' . $term->slug ); ?>
			<?php endif; ?>
		</div>

		<?php hm_get_template_part( 'products/heading-bar' ); ?>

		<ul class="shop_view">
			<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
				<li><?php hm_get_template_part( 'products/grid-product', array( 
					'product' => new AJC_Product( $post->ID ), 
					'bindings' => false, 
					'classes' => is_user_logged_in() ? '' : 'unavailable',
					'reduction' => true,
					'favourite' => true,
					'custom_link' => is_user_logged_in() ? false : '/signup' ) ); ?>
				</li>
			<?php endwhile; endif; ?>
		</ul>

		<p class="centered space-above space-below" data-bind="visible: ready() && !products().length, html: 'Sorry, there are no items matching your criteria'"></p>

		<div style="clear:both" class="scroll"></div>

	</div>
<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    </div>
    </div>
</div>
<?php get_footer(); ?>