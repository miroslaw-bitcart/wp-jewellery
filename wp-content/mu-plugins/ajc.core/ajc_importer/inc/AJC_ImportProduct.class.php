<?php
/**
 * @package  ajc.utilities
 */

class AJC_ImportProduct {

	private $row;
	private $dbh;

	/**
	 * Assign the PDO row and dbh to the local product
	 * @param StdClass $row 
	 * @param PDO $dbh 
	 */
	function __construct( $row, $dbh ) {
		$this->row = $row;
		$this->dbh = $dbh;
	}

	function get_title() {
		$raw = htmlspecialchars_decode( $this->row->vTitle );
		$trimmed = preg_replace( '/^(A |An )/', '', $raw );
		$titleized = ajc_title_case( $trimmed );
		$ampersands = preg_replace( '/\band\b/i', '&', $titleized );
		$carats = preg_replace( '/(\d+) carat/', '\1ct', $ampersands);
		$despaced = str_replace( '  ', ' ', $carats );
		return $despaced;
	}

	function get_author() {
		return 1;
	}

	function get_content() {
		$content = $this->row->tContent;
		$content = preg_replace( '/(\d+) carat/', '\1ct', $content );
		$content = preg_replace( '/\b&\b/', 'and', $content );
		$content = preg_replace( '/\?\./', '?', $content );
		return $content;
	}

	function get_date() {
		return date( 'Y-m-d H:i:s', $this->row->tmBuildDate );
	}

	function get_dateorigin() {
		return $this->row->sDate_Origin;
	}

	function get_id() {
		return $this->row->iProdid_PK;
	}

	function get_price() {
		return (int) $this->row->fPrice;
	}

	function get_type() {
		return $this->get_department( 'type' );
	}

	function get_collection() {
		return $this->get_department( 'collection' );
	}

	function get_gender() {
		return $this->get_department( 'gender' );
	}

	function get_legacy_id() {
		return $this->row->iProdid_PK;
	}

	function get_views() {
		return $this->row->views;
	}

	function get_notes() {
		return $this->row->sNotes;
	}

	private function get_period() {
		
		$results = array(); 

		$stmt = $this->dbh->prepare( "SELECT dp_tbPeriod.sPeriodName FROM
			dp_tbPeriod
			INNER JOIN 	dp_tbShop_Products
			ON dp_tbShop_Products.iPeriod_FK = dp_tbPeriod.id
			WHERE dp_tbShop_Products.iProdid_PK = :id" );

		$stmt->execute( array( ':id' => $this->get_id() ) );

		while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
			$results[] = sanitize_title( $row->sPeriodName );

		return $results;
	}

	private function get_department( $dept ) {

		$results = array();

		switch( $dept ) {
			case 'type' : 
				$exclude = implode( ',', array( AJC_ImporterMappings::IGNORE, AJC_ImporterMappings::COLLECTIONS, AJC_ImporterMappings::GENDERS ) );
				break;
			case 'collection' :
				$exclude = implode( ',', array( AJC_ImporterMappings::IGNORE, AJC_ImporterMappings::TYPES, AJC_ImporterMappings::GENDERS ) );
				break;
			case 'gender' :
				$exclude = implode( ',', array( AJC_ImporterMappings::IGNORE, AJC_ImporterMappings::TYPES, AJC_ImporterMappings::COLLECTIONS ) );
				break;
		}

		$stmt = $this->dbh->prepare( "SELECT dp_tbShop_Departments.vTitle FROM
			dp_tbShop_Departments
			INNER JOIN dp_tbfusion
			ON dp_tbfusion.iOwner_FK = dp_tbShop_Departments.iDeptid_PK
			WHERE dp_tbfusion.iSubID_FK = :id
			AND dp_tbShop_Departments.iDeptid_PK NOT IN (${exclude})" );

		$stmt->execute( array( ':id' => $this->get_id() ) );

		while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
			$results[] = sanitize_title( $row->vTitle );

		// assign different categories for certain mapped products
		if( $dept == 'type' ) {
			$mappings = AJC_ImporterMappings::get_category_mappings();

			// loop through the results. if one is found in the map,
			// replace that one with the mapped value
			foreach( $results as $key => $result ) {
				$r = sanitize_title( $result );
				if( isset( $mappings[$r] ) ) {
					error_log( 'mapping found: ' . $r . ' => ' . $mappings[$r] );
					$results[$key] = $mappings[$r];
				}
			}

		}

		return $results;
	}

	function to_display() {

		$stmt = $this->dbh->prepare( "SELECT iState FROM
			dp_tbfusion
			WHERE iSubID_FK = :id" );

		$stmt->execute( array( ':id' => $this->get_id() ) );
		$row = $stmt->fetch( PDO::FETCH_OBJ );

		return (bool) $row->iState;
	}

	function get_materials() {

		$materials = array();

		$stmt = $this->dbh->prepare( "SELECT dp_tbMaterial.sMaterialName FROM
			dp_tbMaterial
			INNER JOIN dp_tbShop_ProductMaterial
			ON dp_tbShop_ProductMaterial.iMaterialid_FK = dp_tbMaterial.id
			WHERE dp_tbShop_ProductMaterial.iProductid_FK = :id" );

		$stmt->execute( array( ':id' => $this->get_id() ) );

		while( $row = $stmt->fetch( PDO::FETCH_OBJ ) ) {
			$materials[] = strtolower( $row->sMaterialName );
		}

		return $materials;
	}

	function get_materials_from_title() {
		$title = $this->get_title();
		return scrape_materials( $title );
	}

	function get_measurements() {
		return $this->row->sMeasurements;
	}

	function get_sku() {
		return strtoupper( $this->row->vSku );
	}

	function get_ringsize() {
		return $this->row->sRingsize;
	}

	function sold() {
		return (bool) $this->row->iSold;
	}

	function on_hold() {
		return (bool) $this->row->iHold;
	}

	function get_condition() {
		return $this->row->sCondition;
	}

	function get_hallmarks() {
		return $this->row->sHallmarks;
	}

	function get_provenance() {
		return $this->row->sProvenance;
	}

	function get_taxonomies() {

		$materials = array_unique( array_merge(
			$this->get_materials_from_title(),
			$this->get_materials() ) );

		$tax_values = array(
			'period' => array_map( 'sanitize_title', (array) $this->get_period() ),
			'type' => array_map( 'sanitize_title', (array) $this->get_type() ),
			'collection' => array_map( 'sanitize_title', (array) $this->get_collection() ),
			'gender' => array_map( 'sanitize_title', (array) $this->get_gender() ),
			'material' => array_map( 'sanitize_title', (array) $materials ),
		);

		return array_filter( $tax_values );
	}


	function get_image_urls() {

		$images = array();

		// try to find as many images as we can
		// if an image is unset assume the rest are missing too
		// fit the found images together into an array
		for( $i = 0; $i < 6; $i++ ) {
			$column_name = 'vImage' . ( $i + 1 );
			
			if( ! $this->row->$column_name ) {
				break;
			} else {
				$images[$i] = $this->row->$column_name;
			}
		}

		return $images;
	}
}

/**
 * Get materials from a string
 * @param  string $title the string to check
 * @return array
 */
function scrape_materials( $string ) {

	$native_materials = AJC_ImporterMappings::get_materials();
	$string = sanitize_title( $string );
	$found = array();
	foreach( $native_materials as $material ) {	
		if( strpos( $string, $material) ) {
			$found[] = strtolower( $material );
		}
	}
	return $found;
}

function ajc_title_case( $str ) {
    return preg_replace(
        "/(?<=(?<!:|’s)\W)
        (A|An|And|At|For|In|Of|On|Or|The|To|With)
        (?=\W)/e", 
        'strtolower("$1")',
        ucwords( $str )
    );
}