<?php
/**
 * Tests for our plugin.
 *
 * @package EG_Routing_Plugin
 */

/**
 * EG_Routing_Plugin_Test
 */
class Example_Controler_Test extends EG_Routing_Plugin_UnitTestCase {



	/**
	 * We are testing the index method returns a string.
	 *
	 * @covers ExampleController::index
	 */
	public function test_index_method_retunrs_string() {
		$instance = new ExampleController;
		$res = $instance->index();
		$this->assertEquals( $res, 'Hello world' );
	}

}
