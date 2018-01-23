<?php global $product; ?>

<div class="product-block" <?php if ( $product ) : ?>data-id="<?php echo $product->id; ?>"<?php endif; ?>>
	
	<div class="image-wrap">

    <a href="<?php echo get_permalink($product->id); ?>" >

  		<?php $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
      	$attachment_ids = $product->get_gallery_attachment_ids();
      	$default_img =  wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'grid-larger');
      	$attchimage_finalNum = count($attachment_ids) - 1;
      	$mouserover_img = wp_get_attachment_image_src($attachment_ids[$attchimage_finalNum], 'grid-larger');
        //echo $image; ?>

      <img src="<?php echo $default_img[0];?>" height="640" width="480" alt="<?php the_title(); ?>" onmouseover="this.src='<?php echo $mouserover_img[0];?>'" onmouseout="this.src='<?php echo $default_img[0];?>'" />
      
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

      <?php 
      $posted = get_the_date( 'U' );
      if( $posted > ( time() - 172800 /* 2 days */ ) ) : 
        $today = new DateTime();
        $started = new DateTime();
        $started->setTimestamp( $posted );
        $difference = $started->diff( $today ); ?>
        <span class="just-added">Just Added</span>
      <?php endif; ?>

	</div>
	
	<a class="quick-view" href="#" data-id="<?php echo $product->id;?>">Quick View</a>
  <?php edit_post_link(); ?>
</div>