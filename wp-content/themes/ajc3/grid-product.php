<?php 
/**
 * Template to display a single product in a grid
 * See possible options in array below
 */

$template_args = wp_parse_args( $template_args ? $template_args : array(), array(
		'product' => false, // display a product, or just markup
		'bindings' => true, // include knockout bindings
		'date_added' => true, //show date added
		'context' => 'shop', // add a context, currently only a class
		'favourite' => true, // allow favouriting
		'link' => true, // make the product clickable
		'quick_view' => true, // allow quick view
		'classes' => '', // add arbitrary classes
		'price' => true, // show the price
		'remove' => false, // show a remove-from-favourites button
		'sale' => false, // show sale info
		'reduction' => false, // show a reduction
		'custom_link' => false, // make the link point to a different target. only works if 'link' is set to true
		'flip_on_hover' => true, // show the flipside when hovering
	) );

extract( $template_args );
if ( ! $custom_link && $product )
	$link_target = $product->get_permalink();
else if ( $custom_link )
		$link_target = $custom_link;
	else
		$link_target = '';
global $product;
?>

<div class="product-block" <?php if ( $product ) : ?>data-id="<?php echo $product->id; ?>"<?php endif; ?>>
	
	<div class="image-wrap">
        <a href="<?php echo get_permalink($product->id); ?>" >
			<?php $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
            	$attachment_ids = $product->get_gallery_attachment_ids();
            	$default_img =  wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-larger');
            	$attchimage_finalNum = count($attachment_ids) - 1;
            	$mouserover_img = wp_get_attachment_image_src($attachment_ids[$attchimage_finalNum], 'grid-larger');
            //echo $image; ?>
            <img src="<?php echo $default_img[0];?>" height="640" width="480" alt="<?php the_title(); ?>" onmouseover="rollOverImage(this, '<?php echo $mouserover_img[0];?>', event)" onmouseout="rollOverImage(this, '<?php echo $default_img[0];?>', event)" title="<?php the_title(); ?>">
        </a>
	</div>

	<div class="info-wrap">
		<h3><?php echo $product->get_title(); ?></h3>
		<?php 
           $product_status = get_post_meta($product->id, AJC_P_STATUS, true);
           if($product_status=='sold'){
               echo '<span product_status class="sold">Sold</span>';
           }else if($product_status=='on_hold'){
               echo '<span product_status class="on_hold">On Hold</span>';
           }else{
               echo $product->get_price_html();
           }
    	?>		
	</div>
        <?php if ( $bindings && $quick_view ) : ?>
		<a class="quick-view" href="#" data-id="<?php echo $product->id;?>">Quick View</a>
	<?php endif; ?>
</div>