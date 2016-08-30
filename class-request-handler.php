<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Request_Handler
 *
 * @package EG_Routeing_Plugin
 */
class EG_Request_Handler {

	/**
	 * Holds Our Routes
	 *
	 * @var array
	 */
	protected static $routes = array();

	/**
	 * Our constructor
	 */
	public function __construct() {

	}

	/**
	 * Loads our routes file
	 *
	 * @return array Our routes array.
	 */
	public function load_routes() {
		$routes = require_once __DIR__ . '/routes.php';
		self::$routes = $routes;

		return $routes;
	}

	/**
	 * Handle Requests
	 *
	 * @return mixed
	 */
	public function handle() {
		global $wp;
		$action = $wp->query_vars['action'];
		if ( ! $action ) {
			$this->send_response( 'Please provide an action.' );
		}

		$routes = $this->load_routes();

		if ( ! isset( $routes[ $action ] ) ) {
			$this->send_response( 'Action not found.' );
		}

		$parts = explode( '@', $routes[ $action ] );

		return $this->send_response( call_user_func_array( array( new $parts[0], $parts[1] ), array() ) );
	}

	/**
	 * Response Handler
	 * This sends a JSON response to the browser
	 *
	 * @param string|array $msg A message to return in the json response.
	 */
	protected function send_response( $msg ) {
		$response = $msg;
		if ( ! is_array( $msg ) && ! is_object( $msg ) ) {
			$response = array();
			$response['message'] = $msg;
		}

		header( 'content-type: application/json; charset=utf-8' );
		echo wp_json_encode( $response ) . "\n";
		exit;
	}
}
