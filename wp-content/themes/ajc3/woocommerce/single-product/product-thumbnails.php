<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();
$ajc_product = new AJC_Product( $post->ID );
//array_shift( $attachment_ids ); // we're using the first one as the post thumbnail

if ( $attachment_ids ) {
	?>
	<div class="thumbnails clearfix"><?php

		$loop = 0;
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 6 );

		$medium_images = array();
		$large_images = array();

		foreach ( $attachment_ids as $key => $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop == 0 || $loop % $columns == 0 )
				$classes[] = 'first';

			if ( ( $loop + 1 ) % $columns == 0 )
				$classes[] = 'last';

			$image_original_link = wp_get_attachment_url( $attachment_id );
			$image_medium_src = wp_get_attachment_image_src( $attachment_id, 'product-large' );
			// $image_large_src = wp_get_attachment_image_src( $attachment_id, 'product-x-large' );
			$image_large_src = wp_get_attachment_image_src( $attachment_id, 'full' );
			$image_link = $image_medium_src[0];
			
			//if( $key > 0 ) // we don't want to include the first one in the fancybox as it's a dupe of the main image
				$classes[] = 'fancybox-thumb';

			if ( ! $image_link )
				continue;

			$image       = wp_get_attachment_image( $attachment_id, 'product-x-small' );
			$image_class = esc_attr( implode( ' ', $classes ) );
			$image_title = esc_attr( ajc_get_thumbnail_title( $product ) );
			
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" original-href="%s" data-fancybox-group="fancybox-thumb" data-thumb-index="%d">%s</a>', $image_link, $image_class, $image_original_link, $loop, $image ), $attachment_id, $post->ID, $image_class );

			$medium_images[] = $image_medium_src[0];
			$large_images[] = $image_large_src[0];

			$loop++;
		}

	?>
	</div>
	<div>
	<?php
	foreach ($medium_images as $key => $image) {
		echo '<img src="'. $image .'" style="width: 0; height: 0;" alt="Thumbnail">';
	}
	foreach ($large_images as $key => $image) {
		echo '<img src="'. $image .'" style="width: 0; height: 0;" alt="Thumbnail">';
	}
	?>
	</div>
	<?php
}