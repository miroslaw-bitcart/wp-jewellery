<?php 
/**
 * @package  ajc.utilities
 */

include 'inc/AJC_Importer.class.php';
include 'inc/AJC_ImportProduct.class.php';
include 'inc/AJC_Importer.mappings.php';

if( !defined( 'AJC_LEGACY_DBNAME' ) )
	define( 'AJC_LEGACY_DBNAME', 'db_antiqjewellery' );

if( !defined( 'AJC_LEGACY_USERNAME' ) )
	define( 'AJC_LEGACY_USERNAME', 'root' );

if( !defined( 'AJC_LEGACY_PASS' ) )
	define( 'AJC_LEGACY_PASS', 'root' );

if( !defined( 'AJC_LEGACY_HOST' ) )
	define( 'AJC_LEGACY_HOST', 'localhost' );

function importer() {

	date_default_timezone_set( 'Europe/London' );
	
	$importer = new AJC_Importer( AJC_LEGACY_DBNAME, AJC_LEGACY_USERNAME, AJC_LEGACY_PASS, AJC_LEGACY_HOST );

	$number = $_GET['number'];
	$offset = $_GET['offset'];

	$importer->import_x_products( $number, $offset );

}

$importer;


function reset_legacy_product_price( $post_id, $importer ) {

	$legacy = get_post_meta( $post_id, 'ajc_legacy_id', true );

	if( !$legacy )
		return false;

	$product = $importer->get_product_by_id( $legacy );

	if( !$product )
		return false;

	update_post_meta( $post_id, '_price', $product->get_price() );
	update_post_meta( $post_id, '_regular_price', $product->get_price() );
	update_post_meta( $post_id, '_sku', $product->get_sku() );
}

function reset_legacy_product_status( $post_id, $importer ) {

	$legacy = get_post_meta( $post_id, 'ajc_legacy_id', true );

	if( !$legacy )
		return false;

	$product = $importer->get_product_by_id( $legacy );

	if( !$product )
		return false;

	if( $product->sold() ) {
		update_post_meta( $post_id, AJC_P_STATUS, 'sold' );			
	} else if ( $product->on_hold() ) {
		update_post_meta( $post_id, AJC_P_STATUS, 'on_hold' );			
	} else {
		update_post_meta( $post_id, AJC_P_STATUS, 'available' );			
	}
}

add_action( 'wp', function() {
	/*if( !isset($_GET['frisk']) || !$_GET['frisk'] )*/
	return;

	$importer = new AJC_Importer( AJC_LEGACY_DBNAME, AJC_LEGACY_USERNAME, AJC_LEGACY_PASS, AJC_LEGACY_HOST );

	$posts = get_posts( array( 
		'post_type' => 'product',
		'posts_per_page' => -1 ) );

	$post_ids = array_map( function( $p ) { return $p->ID; }, $posts );

	foreach( $post_ids as $id ) {
		if( !get_post_meta( $id, '_regular_price', true ) )
			reset_legacy_product_price( $id, $importer );
	}

} );

add_action( 'wp', function() {
	if( !isset($_GET['frisk']) || !$_GET['frisk'] )
		return;

	$importer = new AJC_Importer( AJC_LEGACY_DBNAME, AJC_LEGACY_USERNAME, AJC_LEGACY_PASS, AJC_LEGACY_HOST );

	global $wpdb;
	$ids = $wpdb->get_col( 'SELECT ID from wp_posts where post_type="product" AND post_status="publish"' );

	foreach( $ids as $id ) {
		reset_legacy_product_status( $id, $importer );
	}

} );
