<?php
/**
 * Flash Sale tests
 */

class AJCFlashSaleTests extends WP_UnitTestCase {
	
	function setUp() {
		parent::setUp();
		// manually activate taxonomy meta
		$taxonomy_metadata = new Taxonomy_Metadata;
		$taxonomy_metadata->activate();
	}

	public function testIsActive() {
		$sale = $this->getActiveFlashSale();
		$this->assertTrue( $sale->is_active() );
	}

	public function testIsInactive() {
		$sale = $this->getFlashSale();
		$this->assertTrue( ! $sale->is_active() );
	}

	public function testActive() {
		$active = $this->getActiveFlashSale();

		// make these guys to prove they won't go in
		$this->getExpiredFlashSale();
		$this->getFutureFlashSale(); 

		$actives = AJC_Flash_Sale::active();

		$this->assertTrue( $this->sale_in_array( $active, $actives ) );
		$this->assertEquals( 1, count( $actives ) );
	}

	public function testExpired() {
		$expired = $this->getExpiredFlashSale();

		// make these guys to prove they won't go in
		$this->getActiveFlashSale();
		$this->getFutureFlashSale(); 

		$expireds = AJC_Flash_Sale::expired();
		$this->assertTrue( $this->sale_in_array( $expired, $expireds ) );
		$this->assertEquals( 1, count( $expireds ) );
	}

	public function testFuture() {
		$future = $this->getFutureFlashSale();

		// make these guys to prove they won't go in
		$this->getActiveFlashSale();
		$this->getExpiredFlashSale(); 

		$futures = AJC_Flash_Sale::future();
		$this->assertTrue( $this->sale_in_array( $future, $futures ) );
		$this->assertEquals( 1, count( $futures ) );
	}

	public function testGetSorted() {
		$sales = array();
		$sale_ids = array();

		$conditions = array( 'active', 'future_1', 'future_2', 'past_1', 'past_2' );

		shuffle( $conditions ); // so term order cannot be repsonsible

		foreach( $conditions as $name ) {
			$ids = wp_insert_term( mt_rand( 1,1000) , AJC_FLASH_SALE );
			$sales[$name] = new AJC_Flash_Sale( get_term( $ids['term_id'], AJC_FLASH_SALE ) );
			$sale_ids[] = $ids['term_id'];
		}

		$sales['active']->set_start_time( strtotime( 'yesterday' ) );
		$sales['active']->set_end_time( strtotime( 'tomorrow' ) );

		$sales['future_1']->set_start_time( strtotime( 'now + 1 week' ) );
		$sales['future_1']->set_end_time( strtotime( 'now + 2 weeks' ) );

		$sales['future_2']->set_start_time( strtotime( 'now + 3 weeks' ) );
		$sales['future_2']->set_end_time( strtotime( 'now + 4 weeks' ) );

		$sales['past_1']->set_start_time( strtotime( 'now - 2 weeks' ) );
		$sales['past_1']->set_end_time( strtotime( 'now - 1 week' ) );

		$sales['past_2']->set_start_time( strtotime( 'now - 4 weeks' ) );
		$sales['past_2']->set_end_time( strtotime( 'now - 3 weeks' ) );

		$sorted = AJC_Flash_Sale::sorted( $sale_ids );

		$this->assertEquals( count( $sorted ), count( $conditions ) );

		// future and past are at opposite ends
		$this->assertEquals( end( $sorted ), $sales['past_2'] );
		$this->assertEquals( reset( $sorted ), $sales['future_2'] );
	}

	/**
	 * Make flash sales
	 */
	private function getFlashSale() {
		$ids = wp_insert_term( mt_rand( 1,1000) , AJC_FLASH_SALE );
		return new AJC_Flash_Sale( get_term( $ids['term_id'], AJC_FLASH_SALE ) );
	}

	private function getActiveFlashSale() {
		$sale = $this->getFlashSale();
		$sale->set_start_time( time() - 100 );
		$sale->set_end_time( time() + 100 ); 
		return $sale;
	}

	private function getExpiredFlashSale() {
		$sale = $this->getFlashSale();
		$sale->set_end_time( time() - 100 ); 
		return $sale;
	}

	private function getFutureFlashSale() {
		$sale = $this->getFlashSale();
		$sale->set_start_time( time() + 100 ); 
		return $sale;
	}

	private function sale_in_array( $sale, $array ) {
		$id = $sale->get_id();
		foreach( $array as $_sale ) {
			if( $_sale->get_id() == $id )
				return true;
		}
		return false;
	}


}