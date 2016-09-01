<?php
/**
 * Plugin Name: EG Routing Plugin
 * Description: A demo plugin for creating a simple routing system in WordPress
 * Version: 0.1.0
 * Author: Aron Marriott-Smith
 * Author URL: https://aronmarriottsmith.co.uk
 *
 * @package EG_Routing_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$globals['EG_Routing_Plugin'] = new EG_Routing_Plugin;

register_activation_hook( __FILE__, array( 'EG_Routing_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'EG_Routing_Plugin', 'deactivate' ) );

/**
 * Main plgin class
 */
class EG_Routing_Plugin {

	/**
	 * Construct
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Method callback for activation hook
	 */
	public static function activate() {
		EG_API_Endpoint::flush_permalinks();
	}

	/**
	 * Method callback for deactivation hook
	 */
	public static function deactivate() {
		EG_API_Endpoint::flush_permalinks();
	}

	/**
	 * Includes all the files need by out plugin
	 */
	public function load_dependencies() {
		require_once __DIR__ . '/class-eg-api-endpoint.php';
		require_once __DIR__ . '/class-request-handler.php';
		require_once __DIR__ . '/controllers/class-example-controller.php';
	}

	/**
	 * Redgister the hooks required by our plugin
	 */
	public function init_hooks() {
		add_action( 'init', array( (new EG_API_Endpoint), 'init' ) );
	}
}
