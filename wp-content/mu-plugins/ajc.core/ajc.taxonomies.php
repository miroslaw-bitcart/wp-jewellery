<?php 

define( 'AJC_TYPE_TAX', 'type' );
define( 'AJC_PERIOD_TAX', 'period' );
define( 'AJC_GENDER_TAX', 'gender' );
define( 'AJC_MATERIAL_TAX', 'material' );
define( 'AJC_OLLY_TAX', 'ollys-picks' );
define( 'AJC_COLLECTION_TAX', 'collection' );
define( 'AJC_PRICE_TAX', 'price-range' );

/**
 * Add some meta abilities to Terms
 */
class AJC_Term extends Term {
	
	function get_meta( $key, $single = true ) {
		return get_term_meta( $this->get_id(), $key, $single );
	}

	function update_meta( $key, $value, $prev_value = false ) {
		return update_term_meta( $this->get_id(), $key, $value, $prev_value );
	}

	function delete_meta( $key, $value ) {
		return delete_term_meta( $this->get_id(), $key, $value );
	}

}

/**
 * A query wrapper for recommended products
 * Will return the latest products if no params specified
 */
class AJC_Recommendation {

	var $taxonomies;
	var $max;

	private $query;
	private $query_params;

	public function get_products() {
		$this->query = new WP_Query( $this->query_params );
		return $this->query->posts;
	}

	public function get_query() {
		if( $this->query )
			return $this->query;
		else 
			return new WP_Query( $this->query_params );
	}

	public function compose_query() {

		$tax_query = $this->build_tax_query();		

		$this->query_params = array(
			'posts_per_page' => $this->max ? $this->max : -1,
			'post_type' => 'product',
			'meta_query' => array(
				array(	
					'key' => AJC_P_STATUS,
					'value' => 'available',
					'compare' => 'IN'
				) ),
			'tax_query' => $tax_query ? $tax_query : null
		);

	}

	private function build_tax_query() {
		
		if( !$this->taxonomies )
			return false;

		$tax_query = array( 'relation' => 'OR' );

		foreach( $this->taxonomies as $tax => $terms ) {
			$tax_query[] = array( 
				'taxonomy' => $tax,
				'field' => 'id',
				'terms' => $terms 
			);
		}

	}

	/**
	 * Is this a specialised recommendation or just the generic one?
	 * @return boolean 
	 */
	public function is_tailored() {
		return (bool) $this->taxonomies;
	}
	
}

class AJC_Recommendation_Builder {

	private $_r;

	public function __construct() {
		$this->_r = new AJC_Recommendation();
	}

	public function add_dimension( $dimension, $terms ) {
		if( $terms )
			$this->_r->taxonomies[$dimension] = $terms;
	}

	public function add_max( $max ) {
		$this->_r->max = $max;
	}

	public function get_recommendation() {
		$this->_r->compose_query();
		return $this->_r;
	}

}

class AJC_Price_Range extends Term {

	var $low;
	var $high;

	function __construct( $term ) {
		parent::__construct( $term );
		$range = explode( '_', $this->get_slug() );
		$this->low = (int) $range[0];
		$this->high = (int) $range[1];
	}

	/**
	 * Get the low price
	 * @return string 
	 */
	public function get_low() {
		if( $this->starts_at_zero() )
			return 'Up to';
		else return '£' . $this->low;
	}

	/**
	 * Get the high price
	 * @return string 
	 */
	public function get_high() {
		return '£' . $this->high;
	}

	/**
	 * Does this range start at 0?
	 * @return bool 
	 */
	public function starts_at_zero() {
		return $this->low === 0;
	}

	/**
	 * Is an int in this price range?
	 * @param  int $int the int
	 * @return bool
	 */
	public function includes( $int ) {
		$int = intval( $int );
		return at_in_range( $int, $this->low, $this->high );
	}

}

add_action( 'init', function() {

	register_taxonomy( AJC_TYPE_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Types', 'taxonomy general name' ),
			'singular_name' => _x( 'Type', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Types' ),
			'all_items' => __( 'All Types' ),
			'parent_item' => __( 'Parent Type' ),
			'parent_item_colon' => __( 'Parent Type:' ),
			'edit_item' => __( 'Edit Type' ),
			'update_item' => __( 'Update Type' ),
			'add_new_item' => __( 'Add New Type' ),
			'new_item_name' => __( 'New Type Name' ),
			'menu_name' => __( 'Types' ),
		),
		'public' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'jewellery-type', 
			'with_front' => true, 
			'hierarchical' => true,
			'menu_position' => 10,
			'show_admin_column' => true,
		),
	));

	register_taxonomy( AJC_PERIOD_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Periods', 'taxonomy general name' ),
			'singular_name' => _x( 'Period', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Periods' ),
			'all_items' => __( 'All Periods' ),
			'parent_item' => __( 'Parent Period' ),
			'parent_item_colon' => __( 'Parent Period:' ),
			'edit_item' => __( 'Edit Period' ),
			'update_item' => __( 'Update Period' ),
			'add_new_item' => __( 'Add New Period' ),
			'new_item_name' => __( 'New Period Name' ),
			'menu_name' => __( 'Periods' ),
		),
		'rewrite' => array(
			'slug' => 'period', 
			'with_front' => false, 
			'hierarchical' => true,
			'menu_position' => 20,
			'show_admin_column' => true
		),
	));

	register_taxonomy( AJC_GENDER_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Genders', 'taxonomy general name' ),
			'singular_name' => _x( 'Gender', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Genders' ),
			'all_items' => __( 'All Genders' ),
			'parent_item' => __( 'Parent Gender' ),
			'parent_item_colon' => __( 'Parent Gender:' ),
			'edit_item' => __( 'Edit Gender' ),
			'update_item' => __( 'Update Gender' ),
			'add_new_item' => __( 'Add New Gender' ),
			'new_item_name' => __( 'New Gender Name' ),
			'menu_name' => __( 'Genders' ),
		),
		'rewrite' => array(
			'slug' => 'gender', 
			'with_front' => false, 
			'hierarchical' => true,
			'menu_position' => 30
		),
	));

	register_taxonomy( AJC_OLLY_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Olly\'s Picks', 'taxonomy general name' ),
			'singular_name' => _x( 'Olly\'s Pick', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Olly\'s Picks' ),
			'all_items' => __( 'Pick categories' ),
			'parent_item' => __( 'Parent Olly\'s Pick' ),
			'parent_item_colon' => __( 'Parent Olly\'s Pick:' ),
			'edit_item' => __( 'Edit Olly\'s Pick' ),
			'update_item' => __( 'Update Olly\'s Pick' ),
			'add_new_item' => __( 'Add New Olly\'s Pick' ),
			'new_item_name' => __( 'New Olly\'s Pick Name' ),
			'menu_name' => __( 'Olly\'s Picks' ),
		),
		'rewrite' => array(
			'slug' => 'ollys-picks', 
			'with_front' => false, 
			'hierarchical' => true,
			'menu_position' => 40
		),
	));

	register_taxonomy( AJC_MATERIAL_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Materials', 'taxonomy general name' ),
			'singular_name' => _x( 'Material', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Materials' ),
			'all_items' => __( 'All Materials' ),
			'parent_item' => __( 'Parent Material' ),
			'parent_item_colon' => __( 'Parent Material:' ),
			'edit_item' => __( 'Edit Material' ),
			'update_item' => __( 'Update Material' ),
			'add_new_item' => __( 'Add New Material' ),
			'new_item_name' => __( 'New Material Name' ),
			'menu_name' => __( 'Materials' ),
		),
		'rewrite' => array(
			'slug' => 'material', 
			'with_front' => false, 
			'hierarchical' => true,
			'menu_position' => 50
		),
	));

	register_taxonomy( AJC_COLLECTION_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Collections', 'taxonomy general name' ),
			'singular_name' => _x( 'Collection', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Collections' ),
			'all_items' => __( 'All Collections' ),
			'parent_item' => __( 'Parent Collection' ),
			'parent_item_colon' => __( 'Parent Collection:' ),
			'edit_item' => __( 'Edit Collection' ),
			'update_item' => __( 'Update Collection' ),
			'add_new_item' => __( 'Add New Collection' ),
			'new_item_name' => __( 'New Collection Name' ),
			'menu_name' => __( 'Collections' ),
		),
		'public' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'collection', 
			'with_front' => true, 
			'hierarchical' => true,
			'menu_position' => 60,
			'show_admin_column' => true 
		),
	));

	register_taxonomy( AJC_PRICE_TAX, 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Price Ranges', 'taxonomy general name' ),
			'singular_name' => _x( 'Price Range', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Price Ranges' ),
			'all_items' => __( 'All Price Ranges' ),
			'edit_item' => __( 'Edit Price Range' ),
			'update_item' => __( 'Update Price Range' ),
			'add_new_item' => __( 'Add New Price Range' ),
			'new_item_name' => __( 'New Price Range Name' ),
			'menu_name' => __( 'Price Ranges' ),
		),
		'public' => true,
		'rewrite' => array(
			'slug' => 'price-range', 
			'with_front' => true, 
			'hierarchical' => true,
			'menu_position' => 70,
		),
	));

	/**
	 * Add the basic terms in advance
	 */
	foreach( array( 'male', 'female' ) as $gender ) {
		if( ! term_exists( $gender, AJC_GENDER_TAX ) ) {
			wp_insert_term(
				ucfirst( $gender ), // the term 
				AJC_GENDER_TAX, // the taxonomy
				array(
					'description'=> ucfirst( $gender ),
					'slug' => $gender
				)
			);
		}
	}



	foreach( array( 'under-250', '250-500', '500-1000', '1000-2000', '2000-3000', '3000-5000', 'over-5000' ) as $range ) {
		if( ! term_exists( $range, AJC_PRICE_TAX ) ) {
			wp_insert_term(
				$range, // the term 
				AJC_PRICE_TAX, // the taxonomy
				array(
					'description'=> $range,
					'slug' => $range
				)
			);
		}
	}

});

function ajc_get_registered_taxonomies() {
	// faster than get_taxonomies
	return array( AJC_TYPE_TAX, AJC_MATERIAL_TAX, AJC_COLLECTION_TAX, AJC_PERIOD_TAX, AJC_GENDER_TAX ); 
}

add_filter( sprintf( 'manage_edit-%s_columns', AJC_PERIOD_TAX ), function( $cols ) {
	unset( $cols['description'] );
	return $cols;
} );

add_action( 'edited_' . AJC_COLLECTION_TAX, function( $term_id ) {
	update_term_meta( $term_id, 'collection_subtitle', $_POST['collection_subtitle'] );
} );

/**
 * Select the tab using a query var on the period page
 * @var [type]
 */
add_action( 'wp', function( $wp ) {
	if( is_tax( AJC_PERIOD_TAX ) && isset( $_GET['tab'] ) && $_GET['tab'] ) {
		$section = $_GET['tab'];
		add_filter( 'ajc_current_tab', function( $chosen ) use ( $section ) {
			return $section;
		} );
	};
}, 1, 1 );


/**
 * Attach a query flag if we're showing a product grid
 * @var boolean
 */
add_action( 'wp', function( $wp ) {
	global $wp_query;
	if( $wp_query->get( AJC_TYPE_TAX ) ||  preg_match( '/shop/', $wp->request )  )
		$wp_query->set( 'is_ajc_product_list', true );
}, 1, 1 );

// let the JS sidebar know which term is selected 
// when landing on a type-tax page
add_action( 'wp', function( $wp ) {
	global $wp_query;
	$type = $wp_query->get( AJC_TYPE_TAX );
	if( $type ) {
		add_filter( 'ajc_shop_view_type_filter_value', function( $filters ) use ( $type ) {
			$filters[] = $type;
			return $filters;
		});
		add_filter( 'ajc_shop_view_filters', function( $filters ) {
			$filters[] = AJC_TYPE_TAX;
			return $filters;
		});
	}
} );

// if we're on a product list take filter values from the query string
// and merge them into the ajc_shop_$tax_filter_values filter. this means
// that we can pass a url like '?period=thing' and it will be picked up 
// by the fancy JS ShopView
// 
// NB tax names are preceded by underscore so we don't trigger
// wp rewrite rules that will send us to tax archives pages
add_action( 'wp', function( $wp ) {

	if( get_query_var( 'is_ajc_product_list' ) ) {

		foreach( array( AJC_MATERIAL_TAX, AJC_PERIOD_TAX, AJC_PRICE_TAX ) as $tax ) {

			if( isset( $_GET['_' . $tax] ) && $_GET['_' . $tax] ) {

				$chosen = (array) explode( ',', $_GET['_' . $tax] );
				add_filter( "ajc_shop_view_${tax}_filter_value", function( $old_filters ) use ( $chosen ) {
					$new_filters = array_merge( $old_filters, $chosen );
					return $new_filters;
				} );

			}
		}
	}
}, 1, 1);


foreach( array( AJC_TYPE_TAX, AJC_PERIOD_TAX, AJC_COLLECTION_TAX, AJC_FLASH_SALE ) as $taxonomy ) {
	add_filter( 'manage_' . $taxonomy . '_custom_column', 'ajc_tax_rows', 15, 3 );
	add_filter( 'manage_edit-' . $taxonomy . '_columns',  'ajc_tax_columns' );
}

function ajc_tax_columns( $original_columns ) {
	$new_columns = $original_columns;
	array_splice( $new_columns, 1 );
	$new_columns['ajc_tax_image'] = esc_html__( 'Image', 'taxonomy-images' );
	return array_merge( $new_columns, $original_columns );
}

function ajc_tax_rows( $row, $column_name, $term_id ) {
	if ( 'ajc_tax_image' === $column_name ) {
		global $taxonomy;
		return ajc_get_taxonomy_image( get_term( $term_id, $taxonomy ), array( 100, 100 ) );
	}
	return $row;
}

// only show available products in collections
add_action( 'pre_get_posts', function( &$query ) {
	
	if( !$query->is_main_query() )
		return;

	if( $query->get(AJC_COLLECTION_TAX) ) {
		if( !$query->get( 'meta_query' ) ) {
			$query->set( 'meta_query', array() );
		}
	} else {
		return;
	}

	$meta_query = $query->get( 'meta_query' );
	$meta_query[] = array(
		'key' => AJC_P_STATUS,
		'value' => 'available',
		'compare' => 'IN'
	);
        
        if(isset($_GET['view']) && $_GET['view']=='all'){
            $per_page = -1;
        }else{
            $per_page = 60;
        }
	$query->set( 'meta_query', $meta_query );
	$query->set('posts_per_page', $per_page);


});