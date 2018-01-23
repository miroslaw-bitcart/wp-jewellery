<?php
/**
 *
 *
 * @package ajc.orders
 */

define( 'AJC_AFTERSHIP_KEY', 'cbfbe0adc841c44993c049290fe038bd6d0134d6' );

/**
 * Interface for the AfterShip API
 * @package ajc.orders
 */
class AJC_AfterShip {

	static private $_instance = null;

	public static function & get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private $endpoint = 'https://api.aftership.com';

	private function __construct() {}

	/**
	 * Get the JSON list of couriers
	 * @return string 
	 */
	public function get_couriers() {
		return $this->get( '/v2/couriers.json', array( 'api_key' => AJC_AFTERSHIP_KEY ) );
	}

	/**
	 * Submit a code to AfterShip
	 * @param  string $code    the code to send
	 * @param  string $courier the carrier
	 * @return true|WP_Error
	 */
	public function submit_tracking_code( $code, $courier ) {
		$response = $this->post( '/v2/trackings.json', array( 
			'api_key' => AJC_AFTERSHIP_KEY,
			'tracking_number' => $code,
			'courier' => $courier ) );
		if( $response->success == true )
			return true;
		else {
			return new WP_Error( 'aftership_error', $response->message );
		}
	}

	/**
	 * Get some tracking info from AS
	 * @param  string $code    the code
	 * @param  string $courier the carrier
	 * @return string|WP_Error          
	 */
	public function get_tracking_info( $code, $courier ) {
		$response = $this->get( '/v2/trackings.json', array(
			'api_key' => AJC_AFTERSHIP_KEY,
			'tracking_number' => $code,
			'courier' => $courier ) );
		if( $response->created_at )
			return $response;
		else 
			return new WP_Error( 'aftership_error', $response->message );
	}

	/**
	 * Make a GET request to the API
	 * @param  string $path the path on the api
	 * @param  array $args the args to encode
	 * @throws Exception on API failure
	 * @return string       the response body
	 */
	private function get( $path, $args ) {
		$query = http_build_query( $args );
		$response = wp_remote_get( $this->endpoint . $path . '?' . $query );
		if( !$response['body'] || $response['response']['code'] == 500 ) {
			throw new Exception( 'Aftership API failure' );
		} else {
			return json_decode( $response['body'] );
		}
	}

	/**
	 * Make a POST request to the API
	 * @param  string $path the path on the api
	 * @param  array $args the args to encode
	 * @throws Exception on API failure
	 * @return string       the response body
	 */
	private function post( $path, $args ) {
		$options = array( 'body' => $args );
		$response = wp_remote_post( $this->endpoint . $path, $options );
		if( !$response['body'] || $response['response']['code'] == 500 ) {
			throw new Exception( 'Aftership API failure' );
		} else {
			return json_decode( $response['body'] );
		}
	}
}
