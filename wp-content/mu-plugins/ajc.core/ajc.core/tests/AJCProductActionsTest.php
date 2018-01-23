<?php
/**
 * Flash Sale tests
 */

class AJCProductIntegrationTests extends WP_UnitTestCase {
	
	function setUp() {
		parent::setUp();

		// activate woocommerce
		$plugin_path = 'woocommerce/woocommerce.php';
		$active_plugins = get_option('active_plugins');
		if (isset($active_plugins[$plugin_path]))
		    return;

		require_once(ABSPATH .'/wp-admin/includes/plugin.php');
		activate_plugin($plugin_path);

		do_action( 'init' );

		// manually activate taxonomy meta
		$taxonomy_metadata = new Taxonomy_Metadata;
		$taxonomy_metadata->activate();
	}

	/**
	 * Prove that adding a product to a flash sale synchronises it with woocommerce
	 */
	function testFlashSaleSync() {
		$sale = $this->makeFlashSale();
		$sale->set_start_time( strtotime( '11th February 2022' ) );
		$sale->set_end_time( strtotime( '16th July 2022' ) );

		$new_product = new AJC_Product( $this->getProduct() );

		wp_set_object_terms( $new_product->get_id(), $sale->get_id(), AJC_FLASH_SALE );

		do_action( 'woocommerce_process_product_meta' ); // simulate admin action

		$this->assertEquals( $sale->get_start_time(), $new_product->get_meta( WC_SALE_START_META, true ) ); 
		$this->assertEquals( $sale->get_end_time(), $new_product->get_meta( WC_SALE_END_META, true ) ); 
	}

	private function makeFlashSale() {
		$ids = wp_insert_term( mt_rand( 1,1000) , AJC_FLASH_SALE );
		return new AJC_Flash_Sale( get_term( $ids['term_id'], AJC_FLASH_SALE ) );
	}

	private function getProduct() {
		return wp_insert_post( array(
			'post_title'    => 'Example product',
			'post_content'  => 'Great product.',
			'post_status'   => 'publish',
			'post_type' 	=> 'product'
		) );
	}

}