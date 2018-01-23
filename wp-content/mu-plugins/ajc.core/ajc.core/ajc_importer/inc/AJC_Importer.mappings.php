<?php
/**
 * @package  ajc.utilities
 */

class AJC_ImporterMappings {

	private static $materials = null;

	const COLLECTIONS = '24, 35, 34, 83, 85, 86, 90, 91, 93';

	const IGNORE = '92';

	const TYPES = '7, 8, 9, 12, 13, 14, 15, 16, 17, 18, 19, 21, 22, 77, 78, 79, 80, 93';

	const GENDERS = '5,6';

	public static function get_category_mappings() {
		return array(
			'necklaces' => 'pendants',
			'bracelets' => 'bangles',
			'signet-rings' => 'seals',
			'curiosities' => 'stick-pins'
		);
	}

	/**
	 * Get the slugs for the available materials
	 * @return array 
	 */
	public static function get_materials() {
		if( self::$materials )
			return self::$materials;

		$terms = get_terms( AJC_MATERIAL_TAX, array( 'hide_empty' => false) );

		$materials = array();

		foreach( $terms as $term ) {
			$materials[] = $term->slug;
		}

		return self::$materials = $materials;
	}

}