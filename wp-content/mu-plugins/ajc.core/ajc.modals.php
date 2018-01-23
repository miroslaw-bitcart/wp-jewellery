<?php
/**
 * @package ajc.frontend
 */

class AJC_Modal {

	/**
	 * The modal's name
	 * @var string
	 */
	var $name;

	/**
	 * Attrs to pass to the modal template
	 * @var array
	 */
	var $attrs;

	function __construct( $modalname, $attrs = array() ) {
		$this->name = $modalname;
		$this->attrs = $attrs;
	}

	/**
	 * Return the markup for this modal
	 * @return string some html
	 */
	function get_markup() {
		$this->attrs['return'] = true; //make sure we don't echo the markup
		$output = '<div class="modal-container">';
		$output .= hm_get_template_part( 'modals/' . $this->name, $this->attrs );
		$output .= '</div>';
		return $output;
	}

}