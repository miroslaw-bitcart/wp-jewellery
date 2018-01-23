<?php
/**
 * @package ajc.newsletters
 */

/**
 * Change the menu item for sort fields to 'newsletters'
 */
add_filter( 'gettext', 'ajc_sort_fields', 10, 3 );

// ovverride advanced custom sort options
function ajc_sort_fields( $translated_text, $text, $domain ) {

	if( $domain !== 'acs' )
		return $translated_text;

	return preg_replace( '/Sort Group/', 'Sunday Club', $translated_text );
}

/**
 * Add the metabox to get html from the selected items
 */
add_action( 'admin_head', function() {

        if (in_array($GLOBALS['pagenow'], array('post.php', 'post-new.php')) 
	        && 'acs' == $GLOBALS['post_type'] ) {

    		add_meta_box('ajc_acs_html', 'Get HTML', 'ajc_newsletter_html_metabox', 'acs', 'normal', 'high');
        }
} );

/**
 * Render the HTML metabox
 * @return void 
 */
function ajc_newsletter_html_metabox() {
	global $post;
	$ids = get_post_meta($post->ID, 'post_order', true);
	$array = (array) unserialize($posts);
	include ( 'views/newsletter/metabox.php' );
}

/**
 * AJAX action to return the HTML of the select products, marked up nicely
 */
add_action( 'wp_ajax_get_group_html', function() {

	$group_id= $_POST['id'];

	// acs puts the connected posts in order like this
	$serialized_ids = get_post_meta($group_id, 'post_order', true);
	$ids = (array) unserialize($serialized_ids);

	$query = new WP_Query( array(
        'post_type' => AJC_PRODUCT_PT,
        'post__in' => $ids,
        'order' => 'ASC',
        'posts_per_page' => -1,
        'ignore_sticky_posts' => true,
    ) ); 

    ob_start();
    foreach( $query->posts as $post ) {
    	hm_get_template_part( 'products/newsletter', array( 
    		'product' => new AJC_Product( $post->ID )
    	) );
	}
	$markup = ob_get_clean();

	$result = array( 'markup' => $markup );

	$json = str_replace('\\/', '/', json_encode( $result ) );

	die( $json );

});
