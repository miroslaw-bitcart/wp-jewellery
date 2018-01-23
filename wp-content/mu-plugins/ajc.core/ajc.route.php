<?php
/**
 * @package AJC.Route
 */

add_action( 'init', function() {

		$routes = array(

			'import' => new Archetype_Route( 'import$', array(
					'query_callback' => function( $query ) {
						importer();
						die('imported');
					}
				) ),

			'members' => new Archetype_Route( 'members$', array(
					'rewrite' => 'members=1',
					'query_callback' => function( $query ) {
						do_action( 'at_before_logged_in_page', 'members' );
					},
					'title_callback' => function( $title ) {
						return "Members Area |" . $title;
					},
					'template' => 'members.php'
				), array( 'members' ) ),

			'login' => new Archetype_Route( 'login/?$', array(
					'template' => 'login.php',
					'wp' => function( $wp ) {
						global $wp_query;
						$wp_query->is_home = false;
						$wp_query->is_404 = false;
						$wp_query->is_at_login = true;
					},
					'title_callback' => function( $title ) {
						return "Login | " . $title;
					},
					'query_callback' => function( $query ) {
						do_action( 'at_before_logged_out_page' );
					}
				) ),

			'signup' => new Archetype_Route( 'signup/?$', array(
					'template' => 'signup.php',
					'wp' => function( $wp ) {
						global $wp_query;
						$wp_query->is_home = false;
						$wp_query->is_404 = false;
						$wp_query->is_at_signup = true;
					},
					'title_callback' => function( $title ) {
						return "Signup | " . $title;
					},
					'query_callback' => function( $query ) {
						do_action( 'at_before_logged_out_page' );
						at_form( 'signup', AT_USER_NONCE );
					}
				) ),
			'recommended' => new Archetype_Route( 'recommended/?$', array(
					'template' => 'signup/recommend.php',
					'query_callback' => function( $query ) {
						$query->is_at_signup = true;
					},
					'title_callback' => function( $title ) {
						do_action( 'at_before_logged_in_page' );
						return "Recommended for you | " . $title;
					}
				) ),
			'favourites' => new Archetype_Route( 'favourites/?$', array(
					'template' => 'favourites.php'
				)),
			'flash-sales' => new Archetype_Route( 'sales/?$', array(
					'template' => 'sales.php',
					'query_callback' => function( $query ) {
						$query->query_vars = array_map( function( $x ) {
							return null;
						}, $query->query_vars );
						$query->set( 'is_flash_sales', true );
					},
					'wp' => function( $wp ) {
						global $wp_query;
						$wp_query->is_home = false;
						$wp_query->is_404 = false;
						$wp_query->is_flash_sales = true;
					},
				)),
			'account' => new Archetype_Route( 'account/?(.+?)?/?$', array(
				'rewrite' => 'pagename=account&ajc_settings_section=$matches[1]', 
				'wp' => function( $wp ) {
					$selected = get_query_var( 'ajc_settings_section' );
					$sections = array( 'account', 'orders', 'addresses', 'email' );
					if( !in_array( $selected, $sections ) ) {
						$selected = 'account';
					}
					at_form( $selected, AT_USER_NONCE );
				}
			), array( 'ajc_settings_section' ) ),

			'archive' => new Archetype_Route( 'archive/?$', array(
				'rewrite' => 'ajc_archive=1',
				'template' => 'woocommerce/archive-sold.php'
			), array( 'ajc_archive' ) ),

			// until we override wc's menu item that takes you to my-account instead of account... grr...
			'legacy-account' => new Archetype_Route( 'my-account/$', array(
				'rewrite' => 'pagename=account',
				'wp' => function( $wp ) {
					at_form( 'account', AT_USER_NONCE );
				} ) )
		);


	} );

add_action( 'wp', function() {
	global $wp_query;
	
	if( is_page_template( 'your-interests.php' ) ) {
		$wp_query->is_at_signup = true;
	}
} );

function order_flash_sales( $clauses, $wp_query ) {
    global $wpdb;

    if ( isset( $wp_query->query['orderby'] ) && 'flash_sales' == $wp_query->query['orderby'] ) {

        $clauses['join'] .=<<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
        $clauses['where'] .= " AND (taxonomy = '". AJC_FLASH_SALE ."' OR taxonomy IS NULL)";
        $clauses['groupby'] = "object_id";
        $clauses['orderby']  = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
        $clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
    }

    return $clauses;
}