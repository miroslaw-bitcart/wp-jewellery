<?php
/**
 * @package  ajc.frontend
 */

add_action( 'pre_get_posts', function($query) {

	if ( $query->is_search ) {
	    $query->set( 'post_type',array( 'product' ) ); // also we don't care about non-product content
	}
	
	return $query;
});
