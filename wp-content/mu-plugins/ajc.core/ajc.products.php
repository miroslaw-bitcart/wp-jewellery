<?php
/**
 * @package AJC.products
 */
define( 'AJC_P_MEASUREMENTS', 'ajc_p_measurements' );
define( 'AJC_P_RINGSIZE', 'ajc_p_ringsize' );
define( 'AJC_P_CONDITION', 'ajc_p_condition' );
define( 'AJC_P_HALLMARKS', 'ajc_p_hallmarks' );
define( 'AJC_P_PROVENANCE', 'ajc_p_provenance' );
define( 'AJC_P_STATUS', 'ajc_p_status' );
define( 'AJC_OLLYS_NOTE', 'ajc_ollys_note' );
define( 'AJC_P_NOTES', 'ajc_p_notes' );
define( 'AJC_P_OTHER_NOTES', 'ajc_p_other_notes' );
define( 'AJC_P_RESIZEABLE', 'ajc_p_resizeable' );
define( 'AJC_P_DATE_ORIGIN', 'ajc_p_date_origin' );

class AJC_Product extends Post {

	protected $thumbnail_ids;

	function __construct( $id ) {
		parent::__construct( $id );
		$this->wc_product = get_product( $id );
	}

	/**
	 * Get the product's price
	 * @return int 
	 */
	function get_price() {
		return (double) $this->wc_product->get_price();
	}

	public function invalidate_caches() {
		delete_transient( $this->get_id().'_api_format' );
	}

	public function get_date_origin() {
		$meta = $this->get_meta( AJC_P_DATE_ORIGIN, true );
		if( $meta )
			return $meta;

		$stringparts = array();

		if( $period = $this->get_period() )
			$stringparts[] = $period;

		if( $provenance = $this->get_meta( AJC_P_PROVENANCE, true ) )
			$stringparts[] = $provenance;

		if( !empty( $stringparts ) )
			return implode( ', ', $stringparts );
	}

	function get_converted_currencies() {
		$transient = $this->get_id() . '_currencies';
		//if( $c = get_transient( $transient ) )
		//	return $c;
		$price = $this->get_price();
		$c = array();
		foreach( array( 'USD', 'EUR', 'JPY' ) as $curr ) {
			$c[$curr] = ajc_convert_currency( $price, $curr );
		}
		set_transient( $transient, $c, 60*60*24 );
		return $c;
	}

	function get_converted_currencies_string() {
		$currencies = $this->get_converted_currencies();
		$string = '';
		$string .= sprintf( '<span class="%s">$%s</span>', 'USD', number_format( $currencies['USD'] ) );
		$string .= sprintf( '<span class="%s">€%s</span>', 'EUR', number_format( $currencies['EUR'] ) );
		$string .= sprintf( '<span class="%s">¥%s</span>', 'JPY', number_format( $currencies['JPY'] ) );
		return $string;
	}
	
	/**
	 * Return the product as an array to hand off the the JSON API
	 * @return array
	 */
	function api_format() {

		$alt_image = $this->get_alternative_image();

		if( $alt_image ) {
			$_src = wp_get_attachment_image_src( $alt_image, 'grid-larger' ); 
			$alt_image_src = $_src[0]; 
		} else {
			$alt_image_src = false;
		}

		if( $thumb = get_post_thumbnail_id( $this->get_id() ) ) {
			$_src = wp_get_attachment_image_src( $thumb, 'grid-larger' ); 
			$thumb_src = $_src[0];
		} else {
			$thumb_src = false;
		}

		$period = $this->get_period();

		$formatted = array( 
			'id' => $this->get_id(),
			'name' => $this->get_title(),
			'permalink' => $this->get_permalink(),
			'imageSrc' => $thumb_src ? $thumb_src : false, 
			'price' => '£'  . number_format( $this->get_price() ),
			'alternativeImageSrc' => $alt_image_src ? $alt_image_src : false,
			'ollysPick' => $this->is_ollys_pick(),
			'status' => $this->get_stock_status(),
			'period' => $period ? $period->get_name() : false,
			'dateAdded' => human_time_diff( $this->get_date() ) . ' ago'
		);

		return $formatted;
	}

	/**
	 * Get the period tax for this product
	 * @return Term 
	 */
	function get_period() {
		$terms = wp_get_post_terms( $this->get_id(), AJC_PERIOD_TAX );
		
		if( empty( $terms ) )
			return false;

		return new Term( array_shift( $terms ) );
	}

	/**
	 * Get the type tax for this product
	 * @return Term 
	 */
	function get_type() {
		$terms = wp_get_post_terms( $this->get_id(), AJC_TYPE_TAX );
				
		if( empty( $terms ) )
			return false;

		return new Term( array_shift( $terms ) );
	}

	/**
	 * Override the hated woocommerce method that offers no filters
	 * @return array ids of related posts
	 */
	function get_related( $relation = 'AND', $limit = 2, $orderby = 'rand' ) {
		global $woocommerce;

		// Related products are found from period and type
		$period_array = array();
		$type_array = array();
		$material_array = array();

		// Get tags
		$terms = wp_get_post_terms($this->get_id(), AJC_PERIOD_TAX);
		foreach ( $terms as $term ) $period_array[] = $term->term_id;

		// Get types
		$terms = wp_get_post_terms($this->get_id(), AJC_TYPE_TAX );
		foreach ( $terms as $term ) $type_array[] = $term->term_id;

		// get materials
		$terms = wp_get_post_terms($this->get_id(), AJC_MATERIAL_TAX );
		foreach ( $terms as $term ) $material_array[] = $term->term_id;

		// Meta query
		$meta_query = array();
		$meta_query[] = $woocommerce->query->visibility_meta_query();
	    $meta_query[] = $woocommerce->query->stock_status_meta_query();
	    $meta_query[] = array(
			'key' => AJC_P_STATUS,
			'value' => 'available',
			'compare' => 'IN'
		);

	    $query = apply_filters('woocommerce_product_related_posts', array(
			'orderby'        => 'rand',
			'posts_per_page' => $limit,
			'post_type'      => 'product',
			'meta_query'     => $meta_query,
			'tax_query'      => array(
				'relation'      => $relation,
				array(
					'taxonomy'     => AJC_TYPE_TAX,
					'field'        => 'id',
					'terms'        => $type_array
				),
				array(
					'taxonomy'     => AJC_PERIOD_TAX,
					'field'        => 'id',
					'terms'        => $period_array
				),
				array(
					'taxonomy'     => AJC_MATERIAL_TAX,
					'field'        => 'id',
					'terms'        => $material_array
				)
			)
		) );

		return new WP_Query( $query );
	}

	/**
	 * Get the discount, if the product's on sale
	 * @return string 
	 */
	function get_discount() {
		
		$orig = $this->wc_product->regular_price;
		$sale = $this->wc_product->sale_price;
	
		if( !$orig )
			return false; // prevent divide by zero

		$difference = $orig - $sale;

		if( !$difference || $difference == $orig )
			return false; // ain't no discount

		return floor( $difference / ( $orig / 100 ) ) . '%';
	}

	/**
	 * Get all the thumbnails attached to this product
	 * @return array of post_ids
	 */
	function get_thumbnail_ids() {
		if( $this->thumbnail_ids ) {
			return $this->thumbnail_ids;
		} else {
			return $this->thumbnail_ids = get_posts( 'post_parent=' . $this->get_id() . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids' );
		}
	}

	/**
	 * Get one of the thumbnail IDs for this image
	 * @return string|false
	 */
	function get_alternative_image() {
		$ids = $this->get_thumbnail_ids();
		return end( $ids );
	}

	/**
	 * Is this product one of olly's picks?
	 * @return boolean 
	 */
	function is_ollys_pick() {
		return has_term( 'all-picks', AJC_OLLY_TAX, $this->get_id() );
	}

	/**
	 * Is the product sold
	 * @return boolean 
	 */
	function is_sold() {
		return $this->get_stock_status() == 'sold';
	}

	/**
	 * Is the product on hold?
	 * @return boolean 
	 */
	function is_on_hold() {
		return $this->get_stock_status() == 'on_hold';
	}

	function is_available() {
		return !$this->is_sold() && !$this->is_on_hold();
	}

	/**
	 * Get the number of views for this product
	 * @return int 
	 */
	function get_views_count() {

		$legacy_views = $this->get_meta( 'legacy_views', true );
		$current_views = bawpvc_views_sc( array( 'id' => $this->get_id() ) ); 
		
		$total = $current_views + (int) $legacy_views;
		$this->update_meta( '_views', $total ); //for sorting. annoying
		
		return $total;
	}

	/**
	 * Get the sold/on_hold/available status for this product
	 * @return [type] [description]
	 */
	function get_stock_status() {
		return $this->get_meta( AJC_P_STATUS, true );
	}

	/**
	 * Pass all other function calls up to the WC product
	 */
	function __call( $name, $args ) {
		return call_user_func_array( array( $this->wc_product, $name ), $args );
	}

}

add_action( 'save_post', 'ajc_save_product' );

//actions on saving product
function ajc_save_product( $post_id ) {

	//verify post is not a revision
	if ( !wp_is_post_revision( $post_id ) ) {

		if( 'product' !== get_post_type( $post_id ) || !current_user_can( 'edit_post', $post_id ) )
			return;

		/**
		 * Put it in the proper price range
		 */
		$product = new AJC_Product( $post_id );
		$price = $product->get_price();

		$found_term = false; 

		$terms = get_terms( AJC_PRICE_TAX, array( 'hide_empty' => false ) );
		foreach( $terms as $term ) {
			$range = new AJC_Price_Range( $term );
			if( $range->includes( $price ) ) {
				$found_term = $term->slug;
				break;
			}
		}
		if( $found_term ) 
			wp_set_object_terms( $post_id, $found_term, AJC_PRICE_TAX );

		// @todo check for cufflinks/male taxonomies
		//wp_set_object_terms( $post_id, 'female', AJC_GENDER_TAX );

		/**
		 * Don't let woocommerce manage stock, ever
		 */
		$product->update_meta( '_manage_stock', 'no' );
	}

}

// filter by status too
add_filter( 'ajc_shop_view_filters', function( $filters ) {
	$filters[] = AJC_P_STATUS;
	return $filters;
});

// this was broken by WC 2.0
add_filter( 'woocommerce_get_price', function( $price, $wc_product ) {

	if( $price ) {
	
		return $price;
	
	} else  {
		
		$id = $wc_product->post->id;
		
		if( !$id && !empty( $wc_product->post->ID ) ) {
			$id = $wc_product->post->ID; // wtf: wc_product puts other wc_products in its '$post' property
		}
		
		if( !$id )
			return false;

		return get_post_meta( $id, '_price', true );
	}

}, 10, 2 );

function ajc_product_metaboxes( array $meta_boxes ) {

	$meta_boxes[] = array(
		'title' => 'Item Specifications',
		'pages' => 'product',
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields' => array(

			array( 'id' => AJC_P_MEASUREMENTS, 'name' => 'Measurements', 'desc' => '<p style="font-size:10px;">eg. 1 ½ in  / 2.5 cm length<br>⅛ ¼ ½ ⅜ ¾ ⅝ ⅞<br>&lt;br&gt;</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_P_RINGSIZE, 'name' => 'Ring Size', 'desc' => '<p style="font-size:10px;">
				UK F, US 3, French 44, Japanese 4<br>
				UK G, US 3 ½, French 45, Japanese 5 ½<br>
				UK G ½, US 3 ¾, French 45, Japanese 6<br>
				UK H, US 4, French 46, Japanese 7<br>
				UK I, US 4 ¼, French 47 ¾, Japanese 7 ½<br>
				UK J, US 4 ¾, French 48, Japanese 8 ½<br>
				UK J ½, US 5, French, 49, Japanese 9<br>
				UK K, US 5 ¼, French 50, Japanese 9 ½<br>
				UK K ½, US 5 ½, French 50, Japanese 9 ½<br>
				UK L, US 5 ¾, French 51, Japanese 10 ½<br>
				UK L ½, US 6, French 51 Japanese 11<br>
				UK M, US 6, French 52 ¾, Japanese 12<br>
				UK M ½, US 6 ¼, French 53 ¼, Japanese 12 ½<br>
				UK N, US 6 ½, French 54, Japanese 13<br>
				UK N ½, US 6 ¾, French 54 ¾, Japanese 13 ½<br>
				UK O, US 7, French 55 ¼, Japanese 14<br>
				UK O ½, US 7 ¼, French 56, Japanese 14 ½<br>
				UK P, US 7 ½, French 56 ½, Japanese 15<br>
				UK P ½, US 7 ¾, French 57, Japanese 15 ½<br>
				UK Q, US 8, French 57 ¾, Japanese 16<br>
				UK Q ½, US 8 ¼, French 58 ¼, Japanese 16 ½<br>
				UK R, US 8 ⅝, French 59, Japanese 17<br>
				UK T ½, US 10, French 62, Japanese 20
			</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),

			array( 'id' => AJC_P_CONDITION, 'name' => 'Condition', 'desc' => '<p style="font-size:10px;">Options: Excellent, Very Good, Good<br>Then add any further details </p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_P_HALLMARKS, 'name' => 'Hallmarks', 'desc' => '<p style="font-size:10px;">eg. Marked ‘TF 935’ for Theodore Fahrner Sterling Silver<br>&lt;br&gt;</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_P_DATE_ORIGIN, 'name' => 'Date & Origin', 'desc' => '<p style="font-size:10px;">When and where the item was produced</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_P_PROVENANCE, 'name' => 'Provenance', 'desc' => '<p style="font-size:10px;">The personal history of the item, if applicable</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_OLLYS_NOTE, 'name' => 'Olly\'s note', 'desc' => '<p style="font-size:10px;">Will appear ‘Olly’s picks’<br>Should be personal and fun</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_P_OTHER_NOTES, 'name' => 'Notes', 'desc' => '<p style="font-size:10px;">Any other notes about the item</p>', 'type' => 'textarea', 'rows' => 2, 'cols' => 3 ),
			array( 'id' => AJC_P_RESIZEABLE, 'name' => 'If a ring, can it be resized?', 'type' => 'checkbox', 'rows' => 2, 'cols' => 12 )
		)
	);

	$meta_boxes[] = array(
		'title' => 'Item Status',
		'pages' => 'product',
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields' => array(

			array( 'id' => AJC_P_STATUS, 'type' => 'radio', 'cols' => 12, 'options' => array(
				'sold' => 'Sold',
				'on_hold' => 'On Hold',
				'available' => 'Available' )),

			array( 'id' => AJC_P_NOTES, 'name' => 'Notes', 'desc' => '<p style="font-size:10px;">Who is the item On Hold for / Is item is on SOR / being repaired / in The Rutland Centre?</p>', 'type' => 'textarea', 'rows' => 1, 'cols' => 12 )
			)
	);

	return $meta_boxes;

}

//https://www.google.com/finance/converter?a=16.6700&from=GBP&to=USD

function ajc_convert_currency( $gbp_amount, $target_currency ) {
	$x = sprintf( 'https://www.google.com/finance/converter?hl=en&q=%dGBP=?%s', $gbp_amount, $target_currency );
	$data = file_get_contents( sprintf( 'https://www.google.com/finance/converter?hl=en&q=%dGBP=?%s', $gbp_amount, $target_currency ) );
	$data = preg_replace('/[^a-zA-Z0-9_ ",%\[\]\:\.\(\)%&-]/s', '', $data);
	preg_match('/rhs:\s*"([0-9\.]+)[^"]+"/', $data, $m);
	return intval( $m[1] );
}

add_filter( 'cmb_meta_boxes', 'ajc_product_metaboxes' );

// invalidate product caches on save
add_action( 'save_post', function( $post_id ) {
	
	if( 'product' !== get_post_type( $post_id ) )
		return;

	$product = new AJC_Product( $post_id );
	$product->invalidate_caches();
} );

// complete order

add_filter( 'woocommerce_order_status_completed', function( $order_id ) {
	
	$order = new WC_Order( $order_id );
	$products = $order->get_items();

	foreach( $products as $p ) {
		update_post_meta( $p['product_id'], AJC_P_STATUS, 'sold' );
	}

	return $order_id;
} ); 