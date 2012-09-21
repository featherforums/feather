<?php

/*
|--------------------------------------------------------------------------
| Feather Configuration
|--------------------------------------------------------------------------
|
| This is the main configuration file for Feather. Generally the only
| items you will need to configure is the database connection.
|
*/

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connection
	|--------------------------------------------------------------------------
	|
	| Your database connection information.
	|
	*/

	'database' => array(
		'host'	   => 'localhost',
		'database' => 'feather',
		'username' => 'root',
		'password' => '',
		'prefix'   => '',
		'charset'  => 'utf8',
		'driver'   => 'mysql'
	),


	/*
	|--------------------------------------------------------------------------
	| Feather Components
	|--------------------------------------------------------------------------
	|
	| Components to be registered at runtime with Feather. It is advised you
	| do not edit anything down there.
	|
	*/

	'components' => array(),

	/*
	|--------------------------------------------------------------------------
	| Feather Applications
	|--------------------------------------------------------------------------
	|
	| Applications to be registered at runtime with Feather. It is advised you
	| do not edit anything down there.
	|
	*/

	'applications' => array(
		'admin' => '(:feather)/admin',
		'core' 	=> '(:feather)'
	),

);