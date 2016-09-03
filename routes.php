<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This is your routes file.
 * Here you can define your basic routes.
 * We have provided an example to get you started.
 */
$router->get( 'say-hello', array( 'as' => 'say_hello', 'uses' => 'ExampleController@index' ) );
