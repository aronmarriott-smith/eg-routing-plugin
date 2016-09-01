<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EG_API_Endpoint
 *
 * @package EG_Routing_Plugin
 */
class EG_API_Endpoint {

	/**
	 * Hook WordPress
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		add_action( 'parse_request', array( $this, 'sniff_requests' ), 0 );
		add_action( 'init', array( $this, 'add_endpoint' ), 0 );
	}

	/**
	 * Flush permalinks after changes
	 *
	 * @return void
	 */
	public static function flush_permalinks() {
		flush_rewrite_rules();
	}

	/**
	 * Add public query vars
	 *
	 * @param array $vars List of current public query vars.
	 * @return array $vars
	 */
	public function add_query_vars( $vars ) {
		$vars[] = '__api';
		$vars[] = 'action';

		return $vars;
	}

	/**
	 * Add API Endpoint
	 * This is where the magic happens
	 *
	 * @return void
	 */
	public function add_endpoint() {
		add_rewrite_rule( '^api/eg_routing_plugin/?([0-9]+)?/?','index.php?__api=1&action=$matches[1]','top' );
	}

	/**
	 * Sniff Requests
	 * This is where we hijack all API requests
	 *
	 * @return void
	 */
	public function sniff_requests() {
		global $wp;
		if ( isset( $wp->query_vars['__api'] ) ) {
			$handler = (new EG_Request_Handler);
			$handler->handle();
			exit;
		}
	}
}
