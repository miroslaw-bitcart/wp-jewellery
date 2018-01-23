<?php 
/**
 * AJAX API for AJC
 */

define( 'AJC_MAX_PRICE', 900000 );

add_action( 'wp_ajax_nopriv_get_products', 'ajc_get_products_api' );
add_action( 'wp_ajax_get_products', 'ajc_get_products_api' );

function ajc_get_products_api() {

	$filters = $_POST['filter'];
	$offset = isset( $_POST['offset'] ) ? $_POST['offset'] : 0;
	$search = isset( $_POST['search'] ) ? $_POST['search'] : null;
	$sort = isset( $_POST['sort'] ) ? $_POST['sort'] : false;
	$price_low = isset( $_POST['price_low'] ) ? $_POST['price_low'] : 0;
	$price_high = isset( $_POST['price_high'] ) ? $_POST['price_high'] : AJC_MAX_PRICE;
	$archive = isset( $_POST['archive'] ) && $_POST['archive'] ? 1 : 0;
	$collection = isset( $_POST['collection'] ) ? $_POST['collection'] : false;
	$period = isset( $_POST['period'] ) ? $_POST['period'] : false;
	$ollys_picks = isset( $_POST['ollys_picks'] ) ? $_POST['ollys_picks'] : false;

	$tax_query = array();
	$meta_query = array();

	$product_status = false;

	if( $filters ) {

		$product_status = $filters[AJC_P_STATUS];
			unset( $filters[AJC_P_STATUS] ); // this one's a meta value not a taxonomy

		foreach( $filters as $filter => $value ) {
			
			if( !$value )
				continue;

			array_push( $tax_query, array( 
				'taxonomy' => $filter,
				'field' => 'slug',
				'terms' => explode( ',', $value ) 
			) );
		}
	}

	if( $collection && $collection != "undefined" ) {
		array_push( $tax_query, array(
			'taxonomy' => 'collection',
			'field' => 'slug',
			'terms' => (array) $collection ) );
	}

	if( $ollys_picks && $ollys_picks != "undefined" ) {
		array_push( $tax_query, array(
			'taxonomy' => 'ollys-picks',
			'field' => 'slug',
			'terms' => (array) $ollys_picks ) );
	}

	if( $period && $period != "undefined" ) {
		array_push( $tax_query, array(
			'taxonomy' => 'period',
			'field' => 'slug',
			'terms' => (array) $period ) );
	}

	$product_query_args = array( 
		'post_type' => 'product',
		'posts_per_page' => 48,
 		'tax_query' => $tax_query,
 		'post_status' => 'publish', // have to add as we're in ajax (ie admin) context 
		'offset' => $offset,
		's' => $search
	);


	if( !$product_status ) {	
		$meta_query[] = array(
			'key' => AJC_P_STATUS,
			'value' => 'available',
			'compare' => 'IN'
		);
	} else {
		$meta_query[] = array(
			'key' => AJC_P_STATUS,
			'value' => explode( ',' , $product_status ),
			'compare' => 'IN'
		);
	}
	
	// no prices for archive products
	if( !$product_status ) {
		if( $price_high == AJC_MAX_PRICE ) {
			$price_high = PHP_INT_MAX;
		}
		$meta_query[] = array(
			'key' => '_price',
			'value' => array( $price_low, $price_high ),
			'type' => 'numeric',
			'compare' => 'BETWEEN' 
		);
	}

	if( $sort ) {
		$bits = explode( '-', $sort );
		$order = end( $bits );
		$key = reset( $bits );

		if( $order != 'date' ) { // default order
			$product_query_args['order'] = $order == 'high' ? 'DESC' : 'ASC';
			$product_query_args['meta_key'] = $key;
			$product_query_args['orderby'] = 'meta_value_num';
		}
	}

	$product_query_args['meta_query'] = $meta_query;

	error_log( print_r( $product_query_args, true ) );

	$query = new WP_Query( $product_query_args );
	
	$products = array_map( function( $p ) {
		$_product = new AJC_Product( $p->ID );
		return $_product->api_format();
	}, $query->posts );

	at_ajax_response( array( 'products' => $products, 'foundProducts' => $query->found_posts ) );
}

add_action( 'wp_ajax_toggle_favourite', 'ajc_toggle_favourite' );

function ajc_toggle_favourite() {

	$product = at_get_post_value( 'product_id' );
	$force = at_get_post_value( 'force' );

	$user_id = get_current_user_id();
	$product = new AJC_Product( $product );

	if( $force )
		$favourite = $product->add_to_favourites( $user_id );
	else
		$favourite = $product->toggle_favourite_of( $user_id );
	
	at_ajax_response( array( 'favourite' => (bool) $favourite, 'favcount' => (int) $product->get_favourites_count() ) );

}

add_action( 'wp_ajax_posts_as_json', 'ajc_get_json' );
add_action( 'wp_ajax_nopriv_posts_as_json', 'ajc_get_json' );

function ajc_get_json() {

	global $ajc;

	$products = array_map( function( $p ) {
		$_product = new AJC_Product( $p->ID );
		return $_product->api_format();
	}, $ajc->posts );

	at_ajax_response( $products );
}

add_action( 'wp_ajax_get_product_modal_html', 'ajc_get_product_modal_html' );
add_action( 'wp_ajax_nopriv_get_product_modal_html', 'ajc_get_product_modal_html' );

function ajc_get_product_modal_html() {
	global $ajc, $product, $post;

	$pid = $_POST['pid'];

	$ajc_product = new AJC_Product( $pid );
	$product = $ajc_product->wc_product;
	$post = get_post($pid);
	?>
	
    <div class="modal-body product">
		<div class="product-images left relative">
			<?php if ( $attachment_ids = $product->get_gallery_attachment_ids() ) : ?>
				<?php $first_image = reset( $attachment_ids ); ?>
				<div class="main-image relative">
					<div class="zoom-x-large">
						<?php echo wp_get_attachment_image( $first_image, 'grid-larger', false, array( 'class' => 'wp-post-image', 'alt' => ''.get_the_title().'') ); ?>
					</div>
				</div>
			<?php else : ?>
				<img src="<?php echo woocommerce_placeholder_img_src(); ?>" alt="Placeholder" />
			<?php endif; ?>

			<?php
			$attachment_ids = $product->get_gallery_attachment_ids();

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

						$image_medium_src = wp_get_attachment_image_src( $attachment_id, 'grid-larger' );
						$image_link = $image_medium_src[0];
						
						$classes[] = 'fancybox-thumb';

						if ( ! $image_link )
							continue;

						$image       = wp_get_attachment_image( $attachment_id, 'product-x-small' );
						$image_class = esc_attr( implode( ' ', $classes ) );
						$image_title = esc_attr( ajc_get_thumbnail_title( $product ) );
						
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" data-fancybox-group="fancybox-thumb" data-thumb-index="%d">%s</a>', $image_link, $image_class, $loop, $image ), $attachment_id, $post->ID, $image_class );

						$medium_images[] = $image_medium_src[0];
						$large_images[] = $image_large_src[0];

						$loop++;
					}

				?>
				</div>
				<div>
				<?php
				foreach ($medium_images as $key => $image) {
					echo '<img src="'. $image .'" style="width: 0; height: 0;">';
				}
				?>
				</div>
				<?php
			}
			?>

		</div>
		<div class="product-description right">

			<div class="modal-header">
				<a class="close ion-ios7-close-empty" data-dismiss="modal"></a>
				<h1 itemprop="name" class="entry-title"><?php echo $ajc_product->get_title(); ?></h1>
				<h3 class="left"><?php echo $ajc_product->get_period(); ?></h3>
				<h3 class="right sku">
					<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku() ) : ?>
						<span itemprop="productID"><em><?php _e('№', 'woocommerce'); ?></em> <?php echo $product->get_sku(); ?></span>
					<?php endif; ?>
				</h3>
			</div>
			
			<div itemprop="description" class="description">
				<?php echo $post->post_content; ?>
				<table class="specifications space-above">
					<?php if( $measurements = get_post_meta( $post->ID, AJC_P_MEASUREMENTS, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Measurements</td><td class="spec_data"><?php echo $measurements; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $ring_size = get_post_meta( $post->ID, AJC_P_RINGSIZE, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Ring size</td><td class="spec_data"><?php echo $ring_size; ?> 
								<?php if( get_post_meta( $post->ID, AJC_P_RESIZEABLE, true ) ) : ?> 
								(can be resized on request) 
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php if( $condition = get_post_meta( $post->ID, AJC_P_CONDITION, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Condition</td><td class="spec_data"><?php echo $condition; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $hallmarks = get_post_meta( $post->ID, AJC_P_HALLMARKS, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Hallmarks</td><td class="spec_data"><?php echo $hallmarks; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $date_origin = $ajc_product->get_date_origin() ) : ?>
						<tr class="spec">
							<td class="spec_title">Date &amp; Origin</td><td class="spec_data"><?php echo $date_origin; ?></td>
						</tr>
					<?php endif; ?>

					<?php if( $provenance = get_post_meta( $post->ID, AJC_P_PROVENANCE, true ) ) : ?>
						<tr class="spec">
							<td class="spec_title">Provenance</td><td class="spec_data"><?php echo $provenance; ?></td>
						</tr>
					<?php endif; ?>
				</table>
			</div>

			<?php if ( !$teaser ) : ?>
				<?php if( $ajc_product->is_available() ) : ?>
					<div itemprop="offers" class="price clearfix" itemscope itemtype="http://schema.org/Offer">
						<h2 itemprop="price" class="left" id="product-price"><?php echo $product->get_price_html(); ?></h2>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $teaser ) : ?>
				<h2 class="price space-below status on-sale left">
					<em>Currently at <?php echo $ajc_product->get_discount(); ?> off</em>
					<a href="/signup">Join our Weekly Sale to find out</a>
				</h2>
			<?php else : ?>
				<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" id="cart-buttons" class="clearfix" method="post" enctype='multipart/form-data'>

				 	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

					<?php if( $ajc_product->is_sold() ) : ?>
						<div class="left status sold">
							<h2>Sold</h2>
						</div>
					<?php elseif( $ajc_product->is_on_hold() ) : ?>
						<div class="left status on-hold">
							<?php $type = $ajc_product->get_type(); ?>
							<h2>On Hold</h2>
						</div>
					<?php else : ?>
						<ul>
							<li>
								<a href="<?php echo get_permalink($pid); ?>" class="silver button left">Full Details<span class="ion-plus"></span></a>
							</li> 
					 		<li>
					 			<button type="submit" class="black left"><?php echo apply_filters('single_add_to_cart_text', __('Add to Bag', 'woocommerce'), $product->product_type); ?><span class="ion-plus"></span></button>
					 		</li>
					 	</ul>
					<?php endif; ?>

				 	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

				</form>
			<?php endif; ?>
		</div>
	</div>
	<?php
	exit;
}

add_action( 'wp_ajax_nopriv_ajc_search', 'ajc_api_search' );
add_action( 'wp_ajax_ajc_search', 'ajc_api_search' );

function ajc_api_search() {
	$search = $_POST['query'];
	$term = $search['term'];

	$query = new WP_Query( "s=$term" );

	$posts = array_map( function( $p ) {
		return array( 'value' => $p->post_title, 'url' => get_permalink( $p->ID ) ); 
	}, $query->posts );

	at_ajax_response( $posts );
}

add_action( 'wp_ajax_get_tracking_info', 'ajc_get_tracking_info' );

function ajc_get_tracking_info() {
	$id = $_POST['order_id'];

	if( !$id )
		return array( 'status' => false, 'message' => 'No ID was passed' );

	$order = new AJC_Order( $id );
	$result = $order->get_tracking_info();

	if( !$result )
		at_ajax_response( array( 'status' => false ) );

	if( is_wp_error( $result ) ) {
		at_ajax_response( array( 'status' => false, 'message' => $result->get_error_message() ) );
	} 

	return at_ajax_response( array( 
		'status' => true, 'data' => $result ) );
}

add_action( 'wp_ajax_get_modal', 'ajc_get_modal' );
add_action( 'wp_ajax_nopriv_get_modal', 'ajc_get_modal' );

function ajc_get_modal() {

	$modalname = at_get_post_value( 'modal' );
	$args = at_get_post_value( 'args', array() );

	$type = $modalname;

	if( !$modalname )
		return false;

	$modal = new AJC_Modal( $modalname, $args );

	$response = array(
		'markup' => $modal->get_markup(),
		'_type' => $type // type is a reserved word in javascript
	);

	at_ajax_response( $response );
}


add_action( 'wp_ajax_get_tab', 'ajc_get_tab' );
add_action( 'wp_ajax_nopriv_get_tab', 'ajc_get_tab' );

function ajc_get_tab() {

	$term_id = $_POST['term_id'];
	$tab = $_POST['tab'];
	$args = isset( $_POST['args'] ) ? $_POST['args'] : array();

	$markup = hm_get_template_part( 'tabs/' . $tab, array(
		'return' => true,
		'term_id' => $term_id,
		'args' => $args
	) );

	$response = array(
		'markup' => $markup
	);

	at_ajax_response( $response );
}

class AJC_GetController {

	private $vars;

	function delete_user() {

		include_once( ABSPATH . 'wp-admin/includes/user.php' );

		wp_delete_user( get_current_user_id() );

		wp_logout();

		wp_redirect( '/' );
		die();
	}
}

add_action( 'init', function() {

	if( isset( $_GET['ajc_action'] ) ) {
		$action = $_GET['ajc_action'];
	} else {
		return false;
	}

	if( !wp_verify_nonce( $_GET['_wpnonce'], 'a' ) ) {
		return false;
	}

	$controller = new AJC_GetController;

	call_user_func( array( $controller, $action ) );

} );

/**
 * @todo  move things into this new OO api which is neater
 */

define( 'AJC_ALREADY_ADDED', 'Product already added' );
define( 'AJC_NOT_LOGGED_IN', 'You need to be logged in to do that' );


remove_action( 'init', 'woocommerce_add_to_cart_action' );


class AJC_Products_API {

	function add_to_cart( $product_id ) {

		if( !is_user_logged_in() )
			return array( 'failure' => AJC_NOT_LOGGED_IN );

		$user = new AJC_User( get_current_user_id() );
	
		global $woocommerce;

		$was_added_to_cart   = false;
		$added_to_cart       = array();
		$adding_to_cart      = get_product( $product_id );
		$quantity 			 = 1;

		$passed_validation 	= apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$already_added 		= $user->has_product_in_cart( $product_id );

		if( $already_added ) {
			return array( 'failure' => AJC_ALREADY_ADDED );
		}

		if ( $passed_validation ) {
    		if ( $woocommerce->cart->add_to_cart( $product_id, $quantity ) ) {
    			woocommerce_add_to_cart_message( $product_id );
    			$was_added_to_cart = true;
    			$added_to_cart[] = $product_id;
    		}
		}

		if( $was_added_to_cart ) {
			return array( 'success' => true );
		} else {
			return array( 'failure' => 'Failed Validation' );
		}
	}

}

/**
 * Just a wrapper round various API methods
 */
class AJC_Products_AJAX_Controller {

	private $api;

	function __construct() {
		$this->api = new AJC_Products_API;
		add_action( 'wp_ajax_nopriv_ajc_product_ajax', array( $this, '_ajax_handler' ) );
		add_action( 'wp_ajax_ajc_product_ajax', array( $this, '_ajax_handler' ) );
	}

	/**
	 * Take the post vars from ajax and hand them to the dispatcher
	 */
	function _ajax_handler() {
		$action = $_POST['ajc_products_action'];
		$args = $_POST['ajc_products_args'];

		$response = $this->dispatch( $action, $args );

		at_ajax_response( $response );
	}

	function dispatch( $action, $args ) {
		if( $args ) {
			$response = call_user_func_array( array( $this, $action ), $args );
		} else {
			$response = $this->$action();
		}

		return $response;
	}

	function __call( $name, $args ) {
		return call_user_func_array( array( $this->api, $name), $args );
	}
}

new AJC_Products_AJAX_Controller;