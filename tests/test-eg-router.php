<?php
/**
 * Tests for our plugin.
 *
 * @package EG_Routing_Plugin
 */

/**
 * EG_Routing_Plugin_Test
 */
class EG_Router_Test extends EG_Routing_Plugin_UnitTestCase {

	/**
	 * Holds an instance of our plugin class.
	 *
	 * @var EG_Routing_Plugin
	 */
	private $instance;

	/**
	 * Holds an instance of WP Rest Server.
	 *
	 * @var WP_REST_Server
	 */
	private $server;

	/**
	 * This method is called before every single test is run.
	 */
	public function setUp() {
		parent::setUp();

		$this->instance = new EG_Router( 'test/v1/' );
		global $wp_rest_server;
		$this->server = $wp_rest_server = new WP_REST_Server;

		delete_option( 'eg_routes' );
	}

	/**
	 * This method is called after every single test is run.
	 */
	public function tearDown() {
		parent::tearDown();

		global $wp_rest_server;
		$wp_rest_server = null;
	}

	/**
	 * We are testing that routes are stored in an array.
	 *
	 * @covers EG_Router::get_routes()
	 */
	public function test_routes_are_stored_as_array() {
		$this->assertTrue( is_array( $this->instance->get_routes() ) );
	}

	/**
	 * We are testing an InvalidArgumentException is thrown when a method that does not exist and is not in our whitelist is called.
	 *
	 * @covers EG_Router::__call
	 * @expectedException InvalidArgumentException
	 */
	public function test_an_exception_is_thrown_when_a_nonexistent_or_not_whitelisted_method_is_called() {
		$exception_instance = new InvalidArgumentException;
		$this->assertInstanceOf( $exception_instance, $this->instance->foobar() );
	}

	/**
	 * We are testing that when we make calls to nonexistent but whitelisted methods our routes are added to the routes array
	 *
	 * @covers EG_Router::__call()
	 */
 	public function test_when_we_make_calls_to_nonexistent_but_whitelisted_methods_our_routes_are_added() {
		// Call our whitelisted methods that don't exist.
		$this->instance->get('test-get-route', array( 'uses' => 'ExampleController@index' ) );
		$this->instance->post('test-post-route', array( 'uses' => 'ExampleController@index' ) );
		$this->instance->put('test-put-route', array( 'uses' => 'ExampleController@index' ) );
		$this->instance->patch('test-patch-route', array( 'uses' => 'ExampleController@index' ) );
		$this->instance->delete('test-delete-route', array( 'uses' => 'ExampleController@index' ) );


		// Cache the routes.
		$this->instance->cache();
		// Bind the compile method to the rest_api_init action.
		add_action( 'rest_api_init', array( $this->instance, 'compile' ) );
		do_action( 'rest_api_init' );

		// Continue to test that our route endpoints exist.
		$request = new WP_REST_Request( 'GET', '/test/v1/test-get-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );

		$request = new WP_REST_Request( 'POST', '/test/v1/test-post-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );

		$request = new WP_REST_Request( 'PUT', '/test/v1/test-put-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );

		$request = new WP_REST_Request( 'PATCH', '/test/v1/test-patch-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );

		$request = new WP_REST_Request( 'DELETE', '/test/v1/test-delete-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );
	}

	/**
	 * We are testing the cache method caches routes in the options table
	 *
	 * @covers EG_Router::cache()
	 */
	public function test_cache_caches_routes() {
		// Before caching.
		$routes = $this->instance->get_routes();
		$cached_routes = get_option( 'eg_routes', false );
		$this->assertFalse( $routes === $cached_routes );

		// We then add a route to the cache.
		$this->instance->get( 'test-get-route', array( 'uses' => 'ExampleController@index' ) );
		$this->instance->cache();

		// After caching.
		$routes = $this->instance->get_routes();
		$cached_routes = get_option( 'eg_routes', false );
		$this->assertEquals( $routes, $cached_routes );
	}

	/**
	 * We are testing the construct method sets the API namespace.
	 *
	 * @covers EG_Router::__construct()
	 */
	public function test_construct_sets_the_api_namespace() {
		$router = new EG_Router( 'test/namespace' );
		$this->assertEquals( 'test/namespace', $router->get_namespace() );
	}

	/**
	 * We are testing that the namespace is returned.
	 *
	 * @covers EG_Router::get_namespace()
	 */
	public function test_get_namespace_returns_the_namespace() {
		$this->assertEquals( 'test/v1/', $this->instance->get_namespace() );
	}


	/**
	 * We are testing that the compile method will build our wp_rest endpoints
	 *
	 * @covers EG_Router::compile()
	 */
	public function test_that_compile_builds_our_endpoints() {
		// Add a new route.
		$this->instance->get('test-get-route', array( 'uses' => 'ExampleController@index' ) );
		$this->instance->cache();

		// Check our endpoint does not exist.
		$request = new WP_REST_Request( 'GET', '/test/v1/test-get-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 404, $response );

		// Compile the endpoints
		add_action( 'rest_api_init', array( $this->instance, 'compile' ) );
		do_action( 'rest_api_init' );

		// Now check the endpoint does exist.
		$request = new WP_REST_Request( 'GET', '/test/v1/test-get-route' );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );
	}

	/**
	 * We are testing that the add method adds the route data to the routes array.
	 *
	 * @covers EG_Router::add()
	 */
	public function test_route_data_is_extracted_to_an_array() {
		$paramiters = array(
			'method' => 'GET',
			'uses' => 'ExampleController@index',
			'args' => array(),
		);
		$this->instance->add( 'say-hello', $paramiters, false );
		$expected_route_data = array(
			array(
				'namespace' => 'test/v1/',
				'route' => 'say-hello',
				'args' => array(
					'methods' => 'GET',
					'callback' => array(
						new ExampleController(),
						'index',
					),
					'args' => array(),
				),
				'override' => false,
			)
		);
		$this->assertEquals( $this->instance->get_routes(), $expected_route_data );
	}

	/**
	 * We are testing that an Exception is thrown when the uses array key is missing.
	 *
	 * @covers EG_Router::add()
	 * @expectedException Exception
	 */
	public function test_an_exception_is_thrown_when_the_uses_key_is_missing() {
		$paramiters = array(
			'method' => 'GET',
			'args' => array(),
		);
		$this->assertInstanceOf( Exception::class, $this->instance->add( 'say-hello', $paramiters, false ) );
	}

}
