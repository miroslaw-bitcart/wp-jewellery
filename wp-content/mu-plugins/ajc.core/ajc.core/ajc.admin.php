<?php
/**
 * @package ajc.admin
 */

add_action( 'at_main_options_page', function( $main_options ) {

	$filter = new Archetype_Options_Page_Section( 'Product Filter' );

	$filter->add_field( new Archtype_Options_Page_Checkbox_Array( array(
		'name' => 'Taxonomies to include',
		'key' => 'ajc_filter_products',
		'choices' => get_taxonomies( array( 
			'_builtin' => false ) ) ) ) );

	$main_options->add_section( $filter );

} );
