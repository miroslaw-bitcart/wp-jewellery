<?php

/**
 * Get the taxonomies that may be filtered by on the front end, as chosen by the user
 *
 * @return array
 */
function ajc_get_filterable_taxonomies() {
	return get_option( 'ajc_filter_products', array() );
}

/**
 * Get the filter criteria for building the knockout-powered shop view.
 *
 * @return array
 */
function ajc_get_shop_view_filters() {
	$filters = ajc_get_filterable_taxonomies();

	$filters = apply_filters( 'ajc_shop_view_filters', $filters ); // allow adding more filters programmatically
	$return = array();
	foreach ( $filters as $f ) {
		$filter_value = apply_filters( "ajc_shop_view_${f}_filter_value", array() );
		$return[$f] = $filter_value;
	}
	return $return;
}

function ajc_is_filtered_shop_view() {
	$filters = ajc_get_shop_view_filters();
	foreach( $filters as $f )
		if( !empty( $f ) ) return true;
	if( isset( $_GET['_priceLow'] ) || isset( $_GET['_priceHigh'] ) ) 
		return true;
	return false;
}

function ajc_get_shop_view_price_range() {
	$range = array();
    if( isset( $_GET['_price_range'] ) ) {
        $range = explode( '_', $_GET['_price_range'] );
    } else {
        $range[0] = isset( $_GET['_priceLow'] ) ? $_GET['_priceLow'] : 0;
        $range[1] = isset( $_GET['_priceHigh'] ) ? $_GET['_priceHigh'] : 5000;        
    }
	return $range;
}

function ajc_get_shop_view_product_status() {
    if( isset( $_GET['_ajc_p_status'] ) ) {
        return sanitize_title( $_GET['_ajc_p_status'] );
    }
}

function ajc_count_available_in_term( $term ) {
    global $wpdb;
    $st = $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts p
        INNER JOIN $wpdb->term_relationships tr
        ON (p.ID = tr.object_id)
        INNER JOIN $wpdb->term_taxonomy tt
        ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
        INNER JOIN $wpdb->postmeta m
        ON (p.ID = m.post_id )
        WHERE
        p.post_status = 'publish'
        AND tt.taxonomy = %s
        AND tt.term_id = %d
        AND m.meta_key = 'ajc_p_status'
        AND m.meta_value = 'available'",
        $term->taxonomy,
        $term->term_id );
    return $wpdb->get_var( $st );
}
//AND m.meta_key = '_ajc_p_status'
/**
 * Are we on an archive page?
 * @return bool 
 */
function ajc_is_archive_view() {
	return (bool) get_query_var( 'ajc_archive' );
}

/**
 * Get a taxonomy image from the term id
 *
 * @param StdClass     $term
 * @param string|array $size  wp size spec
 * @param boolean $icon  is it an icon?
 * @param string  $attr  attrs
 * @return mixed         false|string
 */
function ajc_get_taxonomy_image( $term, $size = 'full', $icon = false, $attr = ''  ) {
	
    $image = get_field( 'main-image', $term->taxonomy . '_' . $term->term_id );
    return wp_get_attachment_image( $image, $size, $icon, $attr );
}

/**
 * Convert a dd/mm/yyyy date to a timestamp
 *
 * @param string  $ddmmyy_date
 * @return string              epoch timestamp
 */
function ajc_date_to_timestamp( $ddmmyyyy_date ) {
	$date = str_replace( '/', '-', $ddmmyyyy_date );
	return strtotime( $date );
}

/**
 * Turn a period of seconds into an array of days/hours/minutes/seconds
 * @param  int|string $secs 
 * @return array       
 */
function ajc_seconds_to_time( $secs ) {
	$dt = new DateTime( '@' . $secs, new DateTimeZone( 'UTC' ) );
	return array( 'days' => $dt->format( 'z' ),
		'hours'   => $dt->format( 'G' ),
		'minutes' => $dt->format( 'i' ),
		'seconds' => $dt->format( 's' ) );
}

/**
 * Posts2Posts integration
 */
define( 'AJC_FAVOURITES', 'ajc_favourites' );

add_action( 'p2p_init', function() {
	p2p_register_connection_type( array(
	    'name' => AJC_FAVOURITES,
	    'from' => 'product',
	    'to' => 'user',
	    'title' => 'Favourite of'
	) );
});


/**
 * Get a plain ordinary recommendation without user prefs
 * @return AJC_Recommendation 
 */
function ajc_get_generic_recommendation( $max = 12 ) {
	$builder = new AJC_Recommendation_Builder;
	$builder->add_max( $max );
	return $builder->get_recommendation();
}

/**
 * Get the thumbnail title
 * @param  WC_Product $wc_product native product
 * @return  string [description]
 */
function ajc_get_thumbnail_title( $wc_product) {

	$title = $wc_product->get_title();

	if( $period = ajc_get_native_product_period( $wc_product ) )
		$title .= " - {$period->name}";

	return $title;
}

function ajc_get_native_product_period( $wc_product ) {

	$periods = get_the_terms( $wc_product->id, AJC_PERIOD_TAX );

	if( $period = array_shift( $periods ) )
		return $period;

	return false;
} 


/**
 * Render products as a JSON array in the AJC.{serverProducts} object
 *
 * @param array   $query WP_Query
 * @param array $options any options to pass in the JSON
 * @param string $prop_name the property name in the JSON, if not serverProducts
 * @return void
 */
function ajc_server_products( WP_Query $query, $options = false, $prop_name = 'serverProducts' ) { 
	add_action( 'wp_footer', function () use ( $query, $options, $prop_name ) { ?>
        <script>
        AJC.<?php echo $prop_name; ?> = { products: [
            <?php foreach ( $query->posts as $r ) : ?>
                <?php $r = new AJC_Product( $r->ID );
                echo htmlspecialchars_decode( json_encode( $r->api_format() ) ); ?>,
            <?php endforeach; ?>
        ] }; 
        <?php if( $options ) : ?>
            AJC.<?php echo $prop_name; ?>Options = <?php echo json_encode( $options ); ?>;
        <?php endif; ?>
        AJC.<?php echo $prop_name; ?>.foundProducts = <?php echo $query->found_posts; ?>
        </script>
    <?php }, 99, 0 );
} 

/**
 * Wrapper function around wp_nav_menu() that will cache the wp_nav_menu for all tag/category
 * pages used in the nav menus
 * @see http://lookup.hitchhackerguide.com/wp_nav_menu for $args
 * @author tott
 */
function hh_cached_nav_menu( $args = array(), $prime_cache = false ) {
    global $wp_query;
     
    $queried_object_id = empty( $wp_query->queried_object_id ) ? 0 : (int) $wp_query->queried_object_id;
     
    // If design of navigation menus differs per queried object use the key below
    // $nav_menu_key = md5( serialize( $args ) . '-' . serialize( get_queried_object() ) );
     
    // Otherwise
    $nav_menu_key = md5( serialize( $args ) );
     
    $my_args = wp_parse_args( $args );
    $my_args = apply_filters( 'wp_nav_menu_args', $my_args );
    $my_args = (object) $my_args;
     
    if ( ( isset( $my_args->echo ) && true === $my_args->echo ) || !isset( $my_args->echo ) ) {
        $echo = true;
    } else {
        $echo = false;
    }
     
    $skip_cache = false;
    $use_cache = ( true === $prime_cache ) ? false : true;
     
    // If design of navigation menus differs per queried object comment out this section
    //*
    if ( is_singular() ) {
        $skip_cache = true;
    } else if ( !in_array( $queried_object_id, hh_get_nav_menu_cache_objects( $use_cache ) ) ) {
        $skip_cache = true;
    }
    //*/
     
    if ( true === $skip_cache || true === $prime_cache || false === ( $nav_menu = get_transient( $nav_menu_key ) ) ) {
        if ( false === $echo ) {
            $nav_menu = wp_nav_menu( $args );
        } else {
            ob_start();
            wp_nav_menu( $args );
            $nav_menu = ob_get_clean();
        }
        if ( false === $skip_cache )
            set_transient( $nav_menu_key, $nav_menu );
    } 
    if ( true === $echo )
        echo $nav_menu;
    else
        return $nav_menu;
}
 
/**
 * Invalidate navigation menu when an update occurs
 */
function hh_update_nav_menu_objects( $menu_id = null, $menu_data = null ) {
    hh_cached_nav_menu( array( 'echo' => false ), $prime_cache = true );
}
add_action( 'wp_update_nav_menu', 'hh_update_nav_menu_objects' );
 
/** 
 * Helper function that returns the object_ids we'd like to cache
 */
function hh_get_nav_menu_cache_objects( $use_cache = true ) {
    $object_ids = get_transient( 'hh_nav_menu_cache_object_ids' );
    if ( true === $use_cache && !empty( $object_ids ) ) {
        return $object_ids;
    }
 
    $object_ids = $objects = array();
     
    $menus = wp_get_nav_menus();
    foreach ( $menus as $menu_maybe ) {
        if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id ) ) {
            foreach( $menu_items as $menu_item ) {
                if ( preg_match( "#.*/category/([^/]+)/?$#", $menu_item->url, $match ) )
                    $objects['category'][] = $match[1];
                if ( preg_match( "#.*/tag/([^/]+)/?$#", $menu_item->url, $match ) )
                    $objects['post_tag'][] = $match[1];
            }
        }
    }
    if ( !empty( $objects ) ) {
        foreach( $objects as $taxonomy => $term_names ) {
            foreach( $term_names as $term_name ) {
                $term = get_term_by( 'slug', $term_name, $taxonomy );
                if ( $term )
                    $object_ids[] = $term->term_id;
            }
        }
    }
     
    $object_ids[] = 0; // that's for the homepage
     
    set_transient( 'hh_nav_menu_cache_object_ids', $object_ids );
    return $object_ids;
}
