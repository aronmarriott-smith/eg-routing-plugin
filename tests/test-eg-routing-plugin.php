<?php
/**
 * Tests for our plugin.
 *
 * @package EG_Routing_Plugin
 */

/**
 * EG_Routeing_Plugin_Test
 */
class EG_Routing_Plugin_Test extends EG_Routeing_Plugin_UnitTestCase {

	/**
	 * Holds an instance of our plugin class.
	 *
	 * @var EG_Routing_Plugin
	 */
	private $instance;

	/**
	 * This method is called before our tests are run.
	 */
	public function setUp() {
		parent::setUp();

		$eg_routing_plugin = new EG_Routing_Plugin();
		$this->instance = $eg_routing_plugin;
	}

	/**
	 * We are testing that our plugin has a method called activate.
	 *
	 * @covers EG_Routing_Plugin::activate
	 */
	public function test_it_has_method_activate() {
		$this->assertTrue( method_exists( $this->instance, 'activate' ) );
	}

	/**
	 * We are testing that our plugin has a method called deactivate.
	 *
	 * @covers EG_Routing_Plugin::deactivate
	 */
	public function test_it_has_method_deactivate() {
		$this->assertTrue( method_exists( $this->instance, 'deactivate' ) );
	}

}
