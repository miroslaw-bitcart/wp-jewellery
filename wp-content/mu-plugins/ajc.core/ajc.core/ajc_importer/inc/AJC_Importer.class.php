<?php 
/**
 * @package  ajc.utilities
 */

class AJC_Importer {

	private $dbh;
	private static $total_count = 0;

	function __construct( $db, $username, $password, $host = 'localhost' ) {
		try {
		    $this->dbh = new PDO('mysql:host=' . $host . ';dbname=' . $db, $username, $password);
		    $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
		    echo 'ERROR: ' . $e->getMessage();
		}
	}

	function import_x_products( $number = 5, $offset = 0 ) {
		$count = 0;
		$products = $this->fetch_products( $number, $offset );
		foreach( $products as $product ) {
			$this->import_product( $product );
			$count++;
			error_log( "Product $count of $number imported: " . $product->get_title() );
		}
		self::$total_count += $count;
		error_log( 'Imported this session: ' . self::$total_count );
	}

	function get_single_product() {
		return reset( $this->fetch_products( 1 ) );
	}

	function get_product_by_id( $id ) {

		$dbh = $this->dbh;

		$stmt = $this->dbh->prepare('SELECT * FROM dp_tbShop_Products WHERE iProdid_PK = :id' );

		$stmt->execute( array( ':id' => $id ) );

		while($row = $stmt->fetch(PDO::FETCH_OBJ)) 
			return new AJC_ImportProduct( $row, $dbh );
	}

	function import_product( $product ) {

		$post = array(
		  'post_author'    => 1,
		  'post_content'   => $product->get_content(),
		  'post_date'      => $product->get_date(),
		  'post_status'    => $product->to_display() ? 'publish' : 'draft',
		  'post_title'     => $product->get_title(),
		  'post_type'      => 'product'
		);  

		$new = wp_insert_post( $post, true );	

		// set taxonomies
		foreach( $product->get_taxonomies() as $tax => $terms ) 
			wp_set_object_terms( $new, $terms, $tax );

		//set metadata
		update_post_meta( $new, AJC_P_MEASUREMENTS, $product->get_measurements() );
		update_post_meta( $new, AJC_P_RINGSIZE, prepare_ringsize( $product->get_ringsize() ) );
		update_post_meta( $new, AJC_P_CONDITION, prepare_condition( $product->get_condition() ) );
		update_post_meta( $new, AJC_P_HALLMARKS, prepare_hallmarks( $product->get_hallmarks() ) );
		update_post_meta( $new, AJC_P_PROVENANCE, prepare_provenance( $product->get_provenance() ) );
		update_post_meta( $new, AJC_P_DATEORIGIN, $product->get_dateorigin() );
		update_post_meta( $new, AJC_P_OTHER_NOTES, $product->get_notes() );
		update_post_meta( $new, 'ajc_legacy_id', $product->get_legacy_id() );

		update_post_meta( $new, 'ajc_imported', true );

		update_post_meta( $new, '_price', $product->get_price() );
		update_post_meta( $new, '_regular_price', $product->get_price() );
		update_post_meta( $new, '_sku', $product->get_sku() );
		update_post_meta( $new, 'legacy_views', $product->get_views() );

		if( $product->sold() ) {
			update_post_meta( $new, AJC_P_STATUS, 'sold' );			
		} else if ( $product->on_hold() ) {
			update_post_meta( $new, AJC_P_STATUS, 'on_hold' );			
		} else {
			update_post_meta( $new, AJC_P_STATUS, 'available' );			
		}

		do_action( 'save_post', $new ); // make sure meta is updated like a normal post

		$ajc_product = new AJC_Product( $new );

		if( $product->sold() ) {
			$ajc_product->set_stock( 0 );
		} else {
			$ajc_product->set_stock( 1 );
		}

		include_once ABSPATH . 'wp-admin/includes/media.php';
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/image.php';

		$baseurl = 'http://www.antiquejewellerycompany.com/images/product/large/';

		foreach( $product->get_image_urls() as $url ) {
			media_sideload_image( $baseurl . $url, $new, $product->get_title() );
		}	

		// get the ids of the attachments so we can update the post gallery in meta
		$attachments = array_keys( get_children( array(
		                'post_parent' => $new,
		                'post_status' => 'inherit',
		                'post_type' => 'attachment',
		                'post_mime_type' => 'image',
		                'order' => 'ASC',
		                'orderby' => 'menu_order'
		            ) ) );


		$first_image = reset( $attachments );

		if( $first_image )
			set_post_thumbnail( $new, $first_image );

		if( $attachments ) { // if there are any remaining, note them as the gallery
			update_post_meta( $new, '_product_image_gallery', implode( ',', $attachments ) );
		}

		return true;
	}

	/**
	 * Get an array of products up to a certain limit
	 * @param  int $limit
	 * @return array 
	 */
	private function fetch_products( $limit, $offset ) {
		$products = array();
		$dbh = $this->dbh;

		$stmt = $this->dbh->prepare('SELECT * FROM dp_tbShop_Products ORDER BY iProdid_PK DESC LIMIT ' . $limit . ' OFFSET ' . $offset );

		$stmt->execute( );
		while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			$products[] = new AJC_ImportProduct( $row, $dbh );
    	}

    	return $products;
	}

}

function prepare_condition( $string ) {
	
	if( !$string )
		return 'Excellent';

	$string = preg_replace( '/vg/i', 'very good', $string );
	$string = str_replace( ' condition', '', $string );
	$string = strip_full_stops( $string );
	return ucfirst( $string );
}

function prepare_provenance( $string ) {
	if( !$string )
		return 'English';
	return strip_full_stops( $string );
}

function prepare_hallmarks( $string ) {
	if( !$string )
		return '';

	$string = preg_replace( '/none/i', 'Unmarked', $string );

	return preg_replace( '/\.$/', '', $string );
}

function prepare_ringsize( $string ) {
	if( !$string )
		return $string; 

	$string = convert_fractions( $string );
	return $string;
}

function prepare_measurements( $string ) {
	if( !$string )
		return $string; 

	$string = str_replace( "cm's", 'cm', $string );
	$string = convert_fractions( $string );
	return $string;
}

function prepare_ring_size( $string ) {
	if( !$string )
		return $string;

	$string = convert_fractions( $string );
	return $string;
}


function strip_full_stops( $string ) {
	return str_replace( '.', '', $string );
}

function convert_fractions( $string ) {
		
	$fractions = array(
	    '1/4' => '¼', '1/3' => '⅓',
	    '3/8' => '⅜', '3/4' => '¾',
	    '1/2' => '½', '1/8' => '⅛',
	    '5/8' => '⅝', '7/8' => '⅞' );

	$result = str_replace(
		array_keys( $fractions ),
		array_values( $fractions ),
		$string );

	return $result;
}