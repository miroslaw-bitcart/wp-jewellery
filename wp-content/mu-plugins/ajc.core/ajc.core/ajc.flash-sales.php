<?php
/**
 * @package ajc.flash-sales
 */

define( 'AJC_FLASH_SALE_START_META', 'ajc_flash_sale_start' );
define( 'AJC_FLASH_SALE_END_META', 'ajc_flash_sale_end' );
define( 'WC_SALE_START_META', '_sale_price_dates_from' );
define( 'WC_SALE_END_META', '_sale_price_dates_to' );

/**
 * Attributes and behaviours of a flash sale
 * Simple class to determine state of the sale
 */
class AJC_Flash_Sale extends AJC_Term {

	var $start_time;
	var $end_time;
	var $status;

	/**
	 * Return the currently active flash sales
	 * @return array of AJC_Flash_Sales, if any found
	 */
	public static function active() {

		$transient_key = AJC_FLASH_SALE . '-actives';

		$cached = get_transient( $transient_key );
		
		if( $cached )
			return $cached;

		$actives = self::get_sales_matching_callback( 'is_active' );

		set_transient( $transient_key, $actives, 3600 );

		return $actives;
	}

	/**
	 * Return the expired sales
	 * @return array of AJC_Flash_Sales, if any found
	 */
	public static function expired() {
		return self::get_sales_matching_callback( 'is_expired' );
	}

	/**
	 * Return the future sales
	 * @return array of AJC_Flash_Sales, if any found
	 */
	public static function future() {
		return self::get_sales_matching_callback( 'is_future' );
	}

	/**
	 * Get sales that pass a given callback
	 * @param  callable $callback a callback within this class
	 * @return array        
	 */
	private static function get_sales_matching_callback( $callback ) {

		$matches = array();

		$sales = self::all_sales();
		foreach( $sales as $sale ) {
			if( call_user_func( array( $sale, $callback ) ) ) {
				$matches[] = $sale;
			}
		}

		return $matches;
	}

	/**
	 * Get all the sales in an array
	 * @todo this won't scale after a while, either cache it or make it smarter
	 * @return array 
	 */
	public static function all_sales() {
		return array_map( function( $t ) {
			return new AJC_Flash_Sale( get_term( $t, AJC_FLASH_SALE ) );
		}, get_terms( AJC_FLASH_SALE, array( 'hide_empty' => false ) ) );
	}

	/**
	 * Get a given term
	 * @param  int $term_id 
	 * @return self          
	 */
	public static function get( $term_id ) {
		return new AJC_Flash_Sale( get_term( $term_id, AJC_FLASH_SALE ) );
	}

	/**
	 * Get an array of terms, sorted according to a meta field
	 * @param  array $terms will be cast to array if not
	 * @param  array  $options, 'order', 'by'
	 * @return array of self        
	 */
	public static function sorted( $terms, $options = array() ) {

		$defaults = array(
			'order' => 'ASC',
			'by'    => AJC_FLASH_SALE_START_META );

		$options = wp_parse_args( $options, $defaults );
		extract( $options );

		$sales = array();

		foreach( (array) $terms as $term )
			$sales[] = self::get( $term );

		usort( $sales, function( $a, $b ) use( $by ) {
			return strcmp( $b->get_meta( $by ), $a->get_meta( $by ) );
		} );

		if( $order === 'DESC' )
			$sales = array_reverse( $sales );

		return $sales;
	}

	/**
	 * Get all the products in this flash sale
	 * Will cache results 
	 * @return array 
	 */
	public function get_products( $limit = -1 ) {

		$transient_key = AJC_FLASH_SALE . '-' . $this->get_id() . '-' . $limit;
		delete_transient( $transient_key );
		//$cached = get_transient( $transient_key );
		$cached = false; // don't cache for now

		if( $cached )
			return $cached;

		$query = new WP_Query( array(
			'tax_query' => array( 
				array(
					'taxonomy' => AJC_FLASH_SALE,
					'field' => 'id',
					'terms' => $this->get_id()
					)
				),
				'posts_per_page' => $limit
			)
		);

		if( !$query->have_posts() )
			return array();

		$products = array_map( function( $p ) {
			return new AJC_Product( $p->ID );
		}, $query->posts );

		set_transient( $transient_key, $products, 3600 );

		return $products;
	}

	/**
	 * Is the sale on at the moment?
	 * @return boolean 
	 */
	public function is_active() {
		return time() > $this->get_start_time() && time() < $this->get_end_time();
	}

	/**
	 * Has the sale expired
	 * @return boolean 
	 */
	public function is_expired() {
		return $this->get_end_time() && time() > $this->get_end_time();
	}

	/**
	 * Is it going to happen?
	 * @return boolean 
	 */
	public function is_future() {
		return $this->get_start_time() && time() < $this->get_start_time();
	}

	/**
	 * When does it begin?
	 * @return mixed timestamp or null
	 */
	public function get_start_time() {
		if( $this->start_time )
			return $this->start_time;

		return $this->start_time = $this->get_meta( AJC_FLASH_SALE_START_META, true );
	}

	/**
	 * When does it end
	 * @return mixed timestamp or null
	 */
	public function get_end_time() {
		if( $this->end_time )
			return $this->end_time;

		return $this->end_time = $this->get_meta( AJC_FLASH_SALE_END_META, true );
	}

	/**
	 * Return the time to expiry as a string
	 * @return string epoch time
	 */
	public function get_time_to_expiry() {
		$period = ajc_seconds_to_time( $this->get_end_time() - time() );
		return $this->string_format_period( $period );
	}

	/**
	 * Return the time to start as a string
	 * @return string epoch time
	 */
	public function get_time_to_start() {
		$period = ajc_seconds_to_time( $this->get_start_time() - time() );
		return $this->string_format_period( $period );
	}

	/**
	 * Format an array of period details as a string
	 * @param array $period
	 * @return string
	 */
	private function string_format_period( $period ) {
		if( !$period['days'] && !$period['hours'] )
			return 'less than an hour';

		$string = '';

		if( $period['days'] ) {
			$days = $period['days'] == 1 ? ' day, ' : ' days, ';
			$string .= $period['days'] . $days;
		}

		if( $period['hours'] ) {
			$string .= $period['hours'] . ' hours';
		} 

		return $string;
	}

	/**
	 * Set the start time
	 * @param timestamp $time 
	 */
	public function set_start_time( $time ) {
		$this->update_meta( AJC_FLASH_SALE_START_META, $time );
		$this->publish_new_sale_time( WC_SALE_START_META, $time );
	}

	/**
	 * Set the finish time
	 * @param timstamp $time 
	 */
	public function set_end_time( $time ) {
		$this->update_meta( AJC_FLASH_SALE_END_META, $time );
		$this->publish_new_sale_time( WC_SALE_END_META, $time );
	}

	/**
	 * Get the flash sale's status as a string
	 * @return string the status
	 */
	public function get_status() {

		if( $this->is_active() ) {
			$this->status = 'active';
		} else if ( $this->is_expired() ) {
			$this->status = 'expired'; 
		} else if ( $this->is_future() ) {
			$this->status = 'future';
		}

		return $this->status;
	}

	/**
	 * Let the 'observing' posts know what's going on when times change
	 * @param  string $meta_key the value to update
	 * @param  string $new_time the value
	 * @return void
	 */
	private function publish_new_sale_time( $meta_key, $new_time ) {

		$observers = get_objects_in_term( $this->get_id(), AJC_FLASH_SALE );

		foreach( $observers as $observer ) {
			update_post_meta( $observer, $meta_key, $new_time );
		}

	}

}

/**
 * 
 * Prevent logged out users from receiving flash sale products in queries
 * @var &$query
 */
add_action( 'pre_get_posts', function( &$query ) {

	// get the ids of every active sale 
	// for excluding from the Query
	$active_sales = AJC_Flash_Sale::active(); // cached for speed

	if( is_user_logged_in() ) {
		if ( $query->get( 'is_flash_sales' ) || $query->get( AJC_FLASH_SALE ) )
			return false;
	}
	
	
	$relevant_sales = array_merge( AJC_Flash_Sale::active(), AJC_Flash_Sale::future() );

	if( !$relevant_sales )
		return;

	$ids = array_map( function( $sale ) {
		return $sale->get_id();
	}, $relevant_sales );

	// exclude the ids using the tax_query 
	if( isset( $query->query_vars['post_type'] ) &&
		$query->query_vars['post_type'] === 'product' ) {

		$tax = $query->get( 'tax_query' );

		if( !$tax )
			$tax = array();

		$tax = array_merge( $tax, array( array(
			'taxonomy' => AJC_FLASH_SALE,
			'field' => 'id',
			'terms' => $ids,
			'operator' => 'NOT IN' ) ) );

		$query->set( 'tax_query', $tax );
	}

	// request from a rewrite rule (eg single page)
	if( is_singular() && isset( $query->query['product'] ) && !is_user_logged_in() ) {
		add_action( 'template_redirect', function() use ( $ids ) {
			
			// check the $post_id isn't in this taxonomy - if it is, throw a 404
			$post_id = get_queried_object_id();
			if( has_term( $ids, AJC_FLASH_SALE, $post_id) ) {
				global $wp_query;
				status_header( '404' );
				$wp_query->set_404();
				$wp_query->flash_sale_product = true;
			}

		});
	}

}, 100 );

/**
 * Get the currently active flash sale
 * @return AJC_Flash_Sale|false 
 */
function ajc_current_flash_sale() {
	$sales = AJC_Flash_Sale::active();
	
	if( $sales )
		return array_shift( $sales );

	return false;
}

function ajc_get_next_flash_sale() {
	$sales = AJC_Flash_Sale::future();

	if( $sales )
		return array_shift( $sales );

	return false;
}


/**
 * When a product is added to a taxonomy, update its sale terms correspondingly
 */

add_action( 'set_object_terms', 'ajc_sync_product_sale_dates', 10, 4 );

function ajc_sync_product_sale_dates( $object_id, $terms, $tt_ids, $taxonomy ) {

	if( get_post_type( $object_id ) !== AJC_PRODUCT_PT || $taxonomy !== AJC_FLASH_SALE )
		return false;

	$product = new AJC_Product( $object_id );

	$sales = AJC_Flash_Sale::sorted( $terms, array( 'order' => 'ASC', 'by' => 'start_date' ) );

	// WooCommerce will overwrite this when it processes the post edit form,
	// so we need to hook our change to happen just after it does so
	// @otod make this more efficent, ie don't pass all the sales
	add_action( 'woocommerce_process_product_meta', function() use( $sales, $product ) {

		// clear the sale dates if the product is removed from a taxonomy
		if( empty( $sales ) ) {
			$product->update_meta( WC_SALE_END_META, '' );
			$product->update_meta( WC_SALE_START_META, '' );
			return;
		} else {
			foreach( $sales as $sale ) {
				if( $sale->is_future() && $sale->get_start_time() && $sale->get_end_time() ) {
					$product->update_meta( WC_SALE_START_META, $sale->get_start_time() );
					$product->update_meta( WC_SALE_END_META, $sale->get_end_time() );
					break;
				}
			}
		}
	}, 2, 2 );

}

add_action( 'init', function() {

	register_taxonomy( AJC_FLASH_SALE, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Sales', AJC_FLASH_SALE . ' general name' ),
			'singular_name' => _x( 'Sale', AJC_FLASH_SALE . ' singular name' ),
			'search_items' =>  __( 'Search Sales' ),
			'all_items' => __( 'All Sales' ),
			'edit_item' => __( 'Edit Sale' ),
			'update_item' => __( 'Update Sale' ),
			'add_new_item' => __( 'Add New Sale' ),
			'new_item_name' => __( 'New Sale Name' ),
			'menu_name' => __( 'Sales' ),
		),
		'public' => true,
		'rewrite' => array(
			'slug' => 'sales', 
			'hierarchical' => false 
		),
	));

});

add_action( AJC_FLASH_SALE . '_edit_form_fields', 'flash_sale_edit_form_fields' );

function flash_sale_edit_form_fields( $term ) { 

	$sale = new AJC_Flash_Sale( $term ); ?>

	<script>
		jQuery(document).ready(function() {
		    jQuery( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
		 });
	</script>
	<tr class="form-field">
		<?php $date = $sale->get_meta( AJC_FLASH_SALE_START_META, true ); ?>
		<th scope="row" valign="top"><label for="<?php echo AJC_FLASH_SALE_START_META; ?>">Start Date</label></th>
		<td><input class="datepicker" name="<?php echo AJC_FLASH_SALE_START_META; ?>" id="<?php echo AJC_FLASH_SALE_START_META; ?>" type="text" value="<?php echo $date ? date( 'd/m/Y', $date ) : null; ?>" size="40">
		<p class="description">The start date of the sale.</p></td>
	</tr>

	<tr class="form-field">
		<?php $date = $sale->get_meta( AJC_FLASH_SALE_END_META, true ); ?>
		<th scope="row" valign="top"><label for="<?php echo AJC_FLASH_SALE_END_META; ?>">End Date</label></th>
		<td><input class="datepicker" name="<?php echo AJC_FLASH_SALE_END_META; ?>" id="<?php echo AJC_FLASH_SALE_END_META; ?>" type="text" value="<?php echo $date ? date( 'd/m/Y', $date ) : null; ?>" size="40">
		<p class="description">The end date of the sale.</p></td>
	</tr>

<?php };

add_action( 'edited_' . AJC_FLASH_SALE, function( $term_id ) {

	$sale = AJC_Flash_Sale::get( $term_id );

	if ( isset( $_POST[AJC_FLASH_SALE_END_META] ) ) {
			$ts = ajc_date_to_timestamp( $_POST[AJC_FLASH_SALE_END_META] );
			$sale->set_end_time( $ts );
		}

	if ( isset( $_POST[AJC_FLASH_SALE_START_META] ) ) {
			$ts = ajc_date_to_timestamp( $_POST[AJC_FLASH_SALE_START_META] );
			$sale->set_start_time( $ts );		
		}

	delete_transient( AJC_FLASH_SALE . '-actives' );

} );