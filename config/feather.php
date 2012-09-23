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

	/*
	|--------------------------------------------------------------------------
	| Feather Components
	|--------------------------------------------------------------------------
	|
	| Components to be registered at runtime with Feather. It is advised you
	| do not edit anything down there.
	|
	*/

	'components' => array(
		'auth' => function($feather)
		{
			$feather['auth'] = $feather->share(function($feather)
			{
				return new Feather\Components\Auth\Authorizer($feather);
			});
		},
		'sso' => function($feather)
		{
			$feather['sso'] = $feather->share(function($feather)
			{
				return new Feather\Components\Auth\SSO($feather);
			});
		},
		'gear' => function($feather)
		{
			$feather['gear'] = $feather->share(function($feather)
			{
				return new Feather\Components\Gear\Manager($feather);
			});
		},
		'redirect' => function($feather)
		{
			$feather['redirect'] = function($feather)
			{
				return new Feather\Components\Support\Redirector(null);
			};
		},
		'crumbs' => function($feather)
		{
			$feather['crumbs'] = $feather->share(function($feather)
			{
				return new Feather\Components\Support\Breadcrumbs($feather);
			});
		},
		'validator' => function($feather)
		{
			$feather['validator'] = function($feather)
			{
				return new Feather\Components\Validation\Validator($feather);
			};
		},
		'date' => function($feather)
		{
			$feather['date'] = function($feather)
			{
				return new Feather\Components\Support\Date($feather);
			};
		},
		'paginator' => function($feather)
		{
			$feather['paginator'] = function($feather)
			{
				return new Feather\Components\Pagination\Paginator($feather);
			};
		}
	),
);