<?php
/**
 * Bootstrap the plugin unit testing environment.
 *
 * Edit 'active_plugins' setting below to point to your main plugin file.
 *
 * @package wordpress-plugin-tests
 */

// Disable xdebug backtrace.
// if ( function_exists( 'xdebug_disable' ) ) {
// 	xdebug_disable();
// }

echo 'Welcome to the EG Routing Plugin Test Suite' . PHP_EOL . PHP_EOL;

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( 'eg-routing-plugin/eg-routing-plugin.php' ),
);

// If the develop repo location is defined (as WP_DEVELOP_DIR), use that
// location. Otherwise, we'll just assume that this plugin is installed in a
// WordPress develop SVN checkout.
if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
	require getenv( 'WP_DEVELOP_DIR' ) . '/tests/phpunit/includes/bootstrap.php';
} else {
	require '../../../../tests/phpunit/includes/bootstrap.php';
}

// Include unit test base class.
require_once dirname( __FILE__ ) . '/framework/class-eg-routing-plugin-test-case.php';
