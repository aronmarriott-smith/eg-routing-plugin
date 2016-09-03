<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Router
 *
 * @package EG_Routing_Plugin
 */
class EG_Router {

	/**
	 * Holds the namespace of our endpoints
	 *
	 * @var $namespace
	 */
	protected $namespace;

	/**
	 * Supported methods.
	 *
	 * @var array
	 */
	protected static $methods = array(
		'GET',
		'POST',
		'PUT',
		'PATCH',
		'DELETE',
	);

	/**
	 * Holds an array of data to build our routes
	 *
	 * @var $routes
	 */
	private $routes = array();

	/**
	 * Holds our option slug for the storage of routes in the options table.
	 *
	 * @var $option_slug
	 */
	protected static $option_slug = 'eg_routes';

	/**
	 * Initalise our class.
	 *
	 * @param string $namespace Our API namespace.
	 */
	public function __construct( $namespace = '' ) {
		$this->namespace = $namespace;
	}

	/**
	 * Get an array of route paramiters
	 *
	 * @return array
	 */
	public function get_routes() {
		return $this->routes;
	}

	/**
	 * Compiles our routes.
	 */
	public static function compile() {
		$this->routes = get_option( self::$option_slug, array() );

		foreach ( $this->routes as $route_data ) {
			register_rest_route(
				$route_data['namespace'],
				$route_data['route'],
				$route_data['args'],
				$route_data['override']
			);
		}
	}

	/**
	 * Cache the routes
	 */
	public function cache() {
		$cached_routes = get_option( self::$option_slug, array() );

		if ( $this->routes !== $cached_routes ) {
			update_option( self::$option_slug, $this->routes );
		}
	}

	/**
	 * Adds the paramiters needed to add a route to $this->routes
	 *
	 * @param string $route      The route.
	 * @param array  $parameters Additional arguments.
	 * @param bool   $override   Overide existing endpoints.
	 * @throws Exception When missing uses array peramiter.
	 *
	 * @return void
	 */
	public function add( $route, array $parameters = array(), $override = false ) {
		global $wp_rest_server;

		if ( ! isset( $parameters['uses'] ) ) {
			throw new Exception( "Missing array key 'uses'" );
		}

		$args = array(
			'methods'  => $parameters['method'],
			'callback' => $parameters['uses'],
			'args'     => isset( $parameters['args'] ) && is_array( $parameters['args'] ) ? $parameters['args'] : array(),
		);

		if ( strpos( $parameters['uses'], '@' ) !== false ) {
			$parts = explode( '@', $parameters['uses'] );
			$args['callback'] = array( new $parts[0], $parts[1] );
		}

		if ( isset( $parameters['auth'] ) ) {
			$args['permission_callback'] = $parameters['auth'];
			if ( strpos( $parameters['auth'], '@' ) !== false ) {
				$parts = explode( '@', $parameters['auth'] );
				$args['permission_callback'] = array( new $parts[0], $parts[1] );
			}
		}

		$this->routes[] = array(
			'namespace' => $this->namespace,
			'route' => $route,
			'args' => $args,
			'override' => $override,
		);
	}

	/**
	 * Nice trick to resolve our routers methods
	 *
	 * @param string $method     The name of the method that was called.
	 * @param array  $parameters The parameters that were passed to the method.
	 * @throws InvalidArgumentException When a method is not found.
	 *
	 * @return mixed
	 */
	public function __call( $method, array $parameters = array() ) {
		if ( method_exists( $this, $method ) ) {
			return call_user_func_array( array( $this, $method ), $parameters );
		}

		if ( in_array( strtoupper( $method ), static::$methods, true ) ) {
			$parameters[1]['method'] = strtoupper( $method );
			return call_user_func_array( array( $this, 'add' ), $parameters );
		}

		throw new InvalidArgumentException( "Method {$method} not defined" );
	}
}
