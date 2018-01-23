<?php 

/** 1. REGISTER STUFF **/

/** Menus **/

register_nav_menus( array( 
	'header_menu' 	=> 'Header Menu',
	'about_menu'	=> 'About Menu',
	'discover_menu'	=> 'Discover Menu',
	'terms_menu' 	=> 'Terms Menu',
	'contact_menu' 	=> 'Contact Menu',
	'user_menu' 	=> 'User Menu'
) );

//remove containers
add_filter( 'wp_nav_menu_args', function( $args ) {
	$args['container'] = false;
	return $args;
});

add_theme_support('woocommerce');

/** Primary Sidebar (for Trending Page) **/

function my_register_sidebars() {
	register_sidebar(
		array(
			'id' 			=> 'primary',
			'name' 			=> __( 'Primary' ),
			'description' 	=> __( 'Primary sidebar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>'
		)
	);
}
add_action( 'widgets_init', 'my_register_sidebars' );

/** Post Types **/

function create_post_types() {
	register_post_type( 'press',
		array(
			'labels' 				=> array(
				'name' 				=> 'Press',
				'singular_name' 	=> 'Press'
			),
			'public' 				=> true,
			'has_archive' 			=> true,
			'menu_position' 		=> 10,
			'rewrite'    			=> array( "slug" => "press" ),
			'show_in_nav_menus' 	=> true,
			'menu_icon' 			=> 'dashicons-awards'
		)
	);

	register_post_type( 'lookbook',
		array(
			'labels' 				=> array(
				'name' 				=> 'Lookbooks',
				'singular_name' 	=> 'Lookbook',
				'add_new' => 'Add New Lookbook',
				'add_new_item' => 'Add New Lookbook',
				'edit' => 'Edit Lookbook',
				'edit_item' => 'Edit Lookbook',
				'new_item' => 'New Lookbook',
				'view' => 'View Lookbook',
				'view_item' => 'View Lookbook',
				'search_items' => 'Search Lookbooks',
				'not_found' => 'No Lookbook found'
			),
			'public' 				=> true,
			'has_archive' 			=> true,
			'hierarchical' 			=> true,
			'menu_position' 		=> 10,
			'rewrite'    			=> array( "slug" => "lookbooks" ),
			'supports' 				=> array('title','editor','thumbnail'),
			'show_in_nav_menus' 	=> true,
			'menu_icon' 			=> 'dashicons-awards'
		)
	);
}
add_action( 'init', 'create_post_types' );

/** Taxonomies **/

function custom_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Categories', 'text_domain' ),
		'all_items'                  => __( 'All Categories', 'text_domain' ),
		'parent_item'                => __( 'Parent Category', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Category', 'text_domain' ),
		'add_new_item'               => __( 'Add New Category', 'text_domain' ),
		'edit_item'                  => __( 'Edit Category', 'text_domain' ),
		'update_item'                => __( 'Update Category', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Categories', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true
	);
	register_taxonomy( 'taxonomy', array( 'article' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'custom_taxonomy', 0 );

/** Image Sizes **/

add_theme_support( 'post-thumbnails' );
add_image_size( 'grid-regular', 206, 9999, false );
add_image_size( 'grid-larger', 640, 9999, false );
add_image_size( 'product-x-small', 100, 9999, false );
add_image_size( 'product-large', 750, 9999, false );
add_image_size( 'product-x-large', 1120, 9999, false );

/****/

/** 2. ENQUEUE STUFF **/

/** css/js **/

define( 'AJC_THEME_URL', trailingslashit( get_stylesheet_directory_uri() ) );
define( 'AJC_THEME_PATH', trailingslashit( get_stylesheet_directory() ) );

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'ajc-stylesheet', AJC_THEME_URL . 'assets/css/main.css' );
	wp_enqueue_style( 'webfonts', AJC_THEME_URL . 'assets/fonts/MyFontsWebfontsKit.css' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'underscore' );
	wp_enqueue_script( 'query-ui-js', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', false, true, '1.10.3');
	wp_enqueue_script( 'knockout', AJC_THEME_URL . 'assets/javascripts/lib/knockout.js', array(), false, true );
	wp_enqueue_script( 'bootstrap', AJC_THEME_URL . 'assets/javascripts/bootstrap.min.js', array(), false, true );
    wp_enqueue_script( 'plugins-sitewide', AJC_THEME_URL . 'assets/javascripts/plugins-ed.min.js', array(), false, true );
    wp_enqueue_script( 'AJC', AJC_THEME_URL . 'assets/javascripts/ajc.js', array(), false, true );
    wp_enqueue_script( 'modernizr', AJC_THEME_URL . 'assets/javascripts/lib/modernizr.js', array(), false, true );

    /** Conditional **/
    	
    if( is_archive('product') || is_singular( 'product' ) ) {
    	wp_enqueue_script( 'fancybox-init', AJC_THEME_URL . 'assets/javascripts/fancybox-init.js', array(), false, true );
    	wp_enqueue_script( 'fancybox', AJC_THEME_URL . 'assets/javascripts/lib/fancybox/jquery.fancybox.min.js', array(), false, true );
    	wp_enqueue_script( 'fancybox-thumb', AJC_THEME_URL . 'assets/javascripts/lib/fancybox/helpers/jquery.fancybox-thumbs.min.js', array(), false, true );
    }

    if( is_singular( 'product' ) || is_front_page() ) {
    	wp_enqueue_script( 'flexslider', AJC_THEME_URL . 'assets/javascripts/lib/jquery.flexslider.min.js', array(), false, true );
    	$flexslider = true;
    }

    if( is_singular( 'product' ) ) {
		wp_enqueue_style( 'fancybox-css', AJC_THEME_URL . 'assets/javascripts/lib/fancybox/jquery.fancybox.min.css' );
	} 

    if( is_page( 'our-world' ) || is_page( 'the-ages' ) || is_post_type_archive( 'article' ) || is_post_type_archive( 'press' ) ) {
		wp_enqueue_script( 'salvattore', AJC_THEME_URL . 'assets/javascripts/lib/salvattore.min.js', array(), false, true );
	}

	/** Localize **/

	wp_localize_script( 'AJC', 'AJC', array( 
		'currentTab' 	=> apply_filters( 'ajc_current_tab', false ),
		'filter' 		=> ajc_get_shop_view_filters(),
		'priceRange' 	=> ajc_get_shop_view_price_range(),
		'productStatus' => ajc_get_shop_view_product_status(),
		'search' 		=> get_query_var( 's' ),
		'flexslider' 	=> $flexslider,
		'filterNames' 	=> array( 
			AJC_TYPE_TAX 		=> 'Type',
			AJC_PERIOD_TAX 		=> 'Age',
			AJC_MATERIAL_TAX 	=> 'Material',
			AJC_COLLECTION_TAX 	=> 'Collection',
			AJC_P_STATUS 		=> 'Include' ),
		'archive' 		=> get_query_var( 'ajc_archive' ),
		'signup' 		=> Archetype_Form::get( 'signup' )->get_field_names(),
		'survey' 		=> Archetype_Form::get( 'survey' )->get_field_names() 
	) ); 

    /** Deregister **/
    wp_deregister_script( 'jquery-ui-core' );
    wp_deregister_style('thickbox');
    wp_deregister_style('smart-coupon');
    wp_deregister_style( 'wpcm' );
    wp_deregister_style( 'yarpp' );
} );

function my_theme_deregister_plugin_assets_header() {
  wp_dequeue_style('yarppWidgetCss');
}
add_action( 'wp_print_styles', 'my_theme_deregister_plugin_assets_header' );

function my_theme_deregister_plugin_assets_footer() {
  wp_dequeue_style('yarppRelatedCss');
}
add_action( 'get_footer', 'my_theme_deregister_plugin_assets_footer' );

/** Typekit **/

function theme_typekit() {
    wp_enqueue_script( 'theme_typekit', '//use.typekit.net/nku0xls.js');
}
add_action( 'wp_enqueue_scripts', 'theme_typekit' );

function theme_typekit_inline() {
  if ( wp_script_is( 'theme_typekit', 'done' ) ) { ?>
  	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php }
}
add_action( 'wp_head', 'theme_typekit_inline' );

/** 3. CUSTOMIZE WOOCOMMERCE **/

/** Remove Styles and Scripts **/

add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
function child_manage_woocommerce_styles() {
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
	//wp_dequeue_script( 'prettyPhoto-init' );
	if ( is_front_page() || is_home() || is_page() ) {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_script( 'wc_price_slider' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-add-to-cart' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-checkout' );
		wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-cart' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'jquery-blockui' );
		wp_dequeue_script( 'jquery-placeholder' );
		wp_dequeue_script( 'fancybox' );
		wp_dequeue_script( 'jqueryui' );
	}
}

remove_filter( 'lostpassword_url',  'woocommerce_lostpassword_url' );


/** Other Stuff **/

/** Remove CSS **/
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/** Remove Description/Comments titles **/
add_filter( 'woocommerce_product_description_tab_title', function( $text ) {
	return 'Specifications';
});
add_filter( 'woocommerce_product_reviews_tab_title', function( $text ) {
	return 'Comments';
});

/** Remove Breadcrumbs **/
function woocommerce_breadcrumb() {} 

add_filter( 'woocommerce_in_cart_product_title', function( $title, $values ) {
	$product = new AJC_Product( $values['product_id'] );
	return sprintf( '%s (%s)', $title, $product->get_sku() );
}, 10, 2 );


/****/

/** AJC STUFF **/

/** Make form submissions work on product page **/
add_action( 'ajc_before_product_page' , function() {
	at_form( 'enquire', AT_USER_NONCE );
	at_form( 'viewing', 'abc' );
} );

/** Strip the dates from a string, eg Georgian (1791-1825) becomes Georgian **/
function ajc_strip_date_range( $string ) {
	return preg_replace( '/\(.*/', '', $string );
}

/** Set genders for jewellery types **/

add_action( 'init', function() {
	return;
	if( !isset( $_GET['reset_genders'] ) )
		return;
	$female = array( 'antique-rings', 'antique-earrings', 'antique-necklaces', 'antique-bracelets-bangles', 'antique-lockets-pendants', 'antique-charms', 'antique-brooches', 'antique-chains' );
	$male = array( 'antique-cufflinks', 'antique-tie-pins', 'antique-dress-sets' );
	$both = array( 'antique-seals-signet-rings', 'antique-curiosities' );

	foreach( $female as $f ) {
		$t = get_term_by( 'slug', $f, AJC_TYPE_TAX );	
		if( $t );
			assign_female( $t );
	}

	foreach( $male as $f ) {
		$t = get_term_by( 'slug', $f, AJC_TYPE_TAX );	
		if( $t );
			assign_male( $t );
	}

	foreach( $both as $f ) {
		$t = get_term_by( 'slug', $f, AJC_TYPE_TAX );	
		if( $t ) {
			assign_female( $t, false );
			assign_male( $t, false );
		}
	}
} );

/** Assign parent terms when a child term is selected **/
add_action('save_post', 'assign_parent_terms');

function assign_parent_terms($post_id){
    global $post;

    if($post->post_type != 'product')
        return $post_id;

    // get all assigned terms   
    $terms = wp_get_post_terms($post_id, AJC_TYPE_TAX );
    foreach($terms as $term){
        while($term->parent != 0 && !has_term( $term->parent, AJC_TYPE_TAX, $post )){
            // move upward until we get to 0 level terms
            wp_set_post_terms($post_id, array($term->parent), AJC_TYPE_TAX, true);
            $term = get_term($term->parent, AJC_TYPE_TAX );
        }
    }
}

/** REMOVE STUFF **/

/** Header stuff **/

function remove_header_info() {
	remove_action('wp_head', 'feed_links', 2); //removes feeds
	remove_action('wp_head', 'feed_links_extra', 3); //removes comment feed links
	remove_action('wp_head', 'wp_generator'); //removes comment feed links
	remove_action('wp_head', 'rsd_link'); //removes rel=EditURI
	remove_action('wp_head', 'wlwmanifest_link'); //removes rel=wlwmanifest
	remove_action('wp_head', 'rel_canonical'); //removes canonical links
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); /* removes header links */
	remove_action('wp_head',array($GLOBALS['woocommerce'], 'generator'));
}
add_action('init', 'remove_header_info');

/** Remove the WordPress version from RSS feeds **/

add_filter('the_generator', '__return_false');

/** Version numbers **/

function remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );

add_filter('the_generator', '__return_false');

/** Clean up language_attributes() used in <html> tag
 ** Remove dir="ltr" */

function ajc_language_attributes() {
  $attributes = array();
  $output = '';

  if (is_rtl()) {
    $attributes[] = 'dir="rtl"';
  }

  $lang = get_bloginfo('language');

  if ($lang) {
    $attributes[] = "lang=\"$lang\"";
  }

  $output = implode(' ', $attributes);
  $output = apply_filters('ajc_language_attributes', $output);

  return $output;
}
add_filter('language_attributes', 'ajc_language_attributes');

/** Add and remove body_class() classes */

function ajc_body_class($classes) {

  // Add post/page slug
  if (is_single() || is_page() && !is_front_page()) {
    $classes[] = basename(get_permalink());
  }

  // Remove unnecessary classes
  $home_id_class = 'page-id-' . get_option('page_on_front');
  $remove_classes = array(
    'page-template-default',
    $home_id_class
  );
  $classes = array_diff($classes, $remove_classes);

  return $classes;
}
add_filter('body_class', 'ajc_body_class');

 // Remove emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/** Remove unnecessary dashboard widgets */
function ajc_remove_dashboard_widgets() {
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}
add_action('admin_init', 'ajc_remove_dashboard_widgets');

/** 5b. Scripts **/

/** js migrate **/

add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );
function dequeue_jquery_migrate( &$scripts){
	if(!is_admin()){
		$scripts->remove( 'jquery');
		$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
	}
}

/** Redirect ordinary users away from wp-admin **/

add_action( 'admin_init', 'themeblvd_redirect_admin' );
function themeblvd_redirect_admin(){
	if ( ! current_user_can( 'edit_posts' ) && !DOING_AJAX ){
		wp_redirect( site_url() );
		exit;		
	}
}

/** Login redirect **/

add_action( 'wp_login_failed', 'ajc_front_end_login_fail' );  // hook failed login
function ajc_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' ); 
      exit;
   }
}

/** Logout redirect to current page **/

add_filter('logout_url', function( $logouturl, $redir ) {
    return $logouturl . '&amp;redirect_to=' . get_permalink();
}, 10, 2);

/** Show page names as body class **/

function add_body_class( $classes )
{
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_body_class' );

add_filter( 'archetype_invalid_field_class', function( $classes ) {
        $classes[] = 'tipsy-error';
        return $classes;
} );

/** Disqus (for blog posts) **/

function disqus_embed($disqus_shortname) {
    global $post;
    wp_enqueue_script('disqus_embed','http://'.$disqus_shortname.'.disqus.com/embed.js');
    echo '<div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = "'.$disqus_shortname.'";
        var disqus_title = "'.$post->post_title.'";
        var disqus_url = "'.get_permalink($post->ID).'";
        var disqus_identifier = "'.$disqus_shortname.'-'.$post->ID.'";
    </script>';
}

/** Only load Disqus on Single Articles **/

add_action( 'wp_head', 'tgm_tame_disqus_comments' );
function tgm_tame_disqus_comments() {
        if ( is_singular( array( 'post', 'page' ) ) && comments_open() )
                return;
        remove_action( 'loop_end', 'dsq_loop_end' );
        remove_action( 'wp_footer', 'dsq_output_footer_comment_js' );
}

function wp_posts_in_days( $args = '' ) {
	global $wpdb;
	$defaults = array(
		'echo' => 1,
		'days' => 30,
		'lookahead' => 0
	);
	$the_args = wp_parse_args( $args, $defaults );
	extract( $the_args , EXTR_SKIP );
	unset( $args , $the_args , $defaults );
	$days = intval( $days );
	$operator = ( $lookahead != false ) ? '+' : '-';
	$postsindays = $wpdb->get_col( "
		SELECT COUNT(ID)
		FROM $wpdb->posts
		WHERE (1=1
		AND post_type = 'product'
		AND post_status = 'publish'
		AND post_date >= '" . date('Y-m-d', strtotime("$operator$days days")) . "')"
	);
		if($echo != false) :
			echo $postsindays[0];
		else :
			return $postsindays[0];
		endif;
	return;
}

/** Remove prefixes to private posts (ie 'Private:') **/

function title_format($content) {
return '%s';
}
add_filter('private_title_format', 'title_format');
add_filter('protected_title_format', 'title_format');

//add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 60;' ), 20 );
add_filter( 'loop_shop_per_page', 'loop_shop_per_page_change', 20 );
function loop_shop_per_page_change($per_page){
    if(isset($_GET['view']) && $_GET['view']=='all'){
        $per_page = -1;
    }else{
        $per_page = 60;
    }
    return $per_page;
}

/** Change Add to Cart message **/

add_filter( 'woocommerce_add_to_cart_message', 'custom_add_to_cart_message' );
 
function custom_add_to_cart_message() {
	global $woocommerce;
 		$message = sprintf( '<a href="%s">%s <span class="ion-chevron-right"></span></a>%s', get_permalink( woocommerce_get_page_id( 'cart' ) ), __( 'View Shopping Bag', 'woocommerce' ), __( 'Item successfully added to your bag', 'woocommerce' ) );
		return $message;
}

//add_filter('pre_get_posts', 'posts_for_current_author');

/** Product Queries - important **/

add_action('woocommerce_product_query', 'my_woocommerce_product_query', 10, 2);
function my_woocommerce_product_query($wp_query, $wc_query){
    $meta_query = array();
    $tax_query = $wp_query->tax_query;
    if(!is_array($tax_query)){
        $tax_query = array();
    }
    if(isset($_GET['_ajc_p_status'])){
        $meta_query[] = array('key' => AJC_P_STATUS,
			'value' => $_GET['_ajc_p_status'],
			'compare' => 'IN');
    }else{
        $meta_query[] = array('key' => AJC_P_STATUS,
			'value' => 'available',
			'compare' => 'IN');
    }
    $price_low = isset( $_GET['_priceLow'] ) ? $_GET['_priceLow'] : 0;
    $price_high = isset( $_GET['_priceHigh'] )&& $_GET['_priceHigh']<5000  ? $_GET['_priceHigh'] : AJC_MAX_PRICE;
    $meta_query[] = array(
            'key' => '_price',
            'value' => array( $price_low, $price_high ),
            'type' => 'numeric',
            'compare' => 'BETWEEN' 
    );
    $filter_keys = array('gender', 'period', 'ollys-picks', 'material', 'collection');
    foreach($filter_keys as $filter_key){
        if(isset($_GET['_'.$filter_key]) && $_GET['_'.$filter_key]!=''){
            array_push( $tax_query, array( 
                    'taxonomy' => $filter_key,
                    'field' => 'slug',
                    'terms' => explode( ',', $_GET['_'.$filter_key] ) 
            ) );
        }
    }
    $wp_query->set('meta_query', $meta_query);
    $wp_query->set('tax_query', $tax_query);
}