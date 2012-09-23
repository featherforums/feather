<?php namespace Feather;

use IoC;
use Bundle;
use Request;
use Autoloader;

/*
|--------------------------------------------------------------------------
| Path to Feather
|--------------------------------------------------------------------------
|
| Define some paths to Feather applications and resources.
|
*/

set_path('feather', __DIR__ . DS);

set_path('core', path('feather') . 'applications' . DS . 'core' . DS);

set_path('admin', path('feather') . 'applications' . DS . 'admin' . DS);

set_path('gears', path('feather') . 'gears' . DS);

set_path('themes', path('feather') . 'themes' . DS);

/*
|--------------------------------------------------------------------------
| Core Autoloading
|--------------------------------------------------------------------------
|
| Register the Feather namespaces and mappings with Laravel's autoloader.
|
*/

Autoloader::namespaces(array(
	'Feather\\Core'  => path('core') . 'models',
	'Feather\\Admin' => path('admin') . 'models',
	'Feather'		 => path('feather')
));

/*
|--------------------------------------------------------------------------
| Bootstrap Feather
|--------------------------------------------------------------------------
|
| Instantiate a new Feather application instance.
|
*/

$feather = new Components\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Feather Configuration Repository
|--------------------------------------------------------------------------
|
| Register the configuration repository with the Feather application, this
| provides access to database configuration and file based configuration
| for both Feather and Laravel.
|
*/

$feather['config'] = $feather->share(function($feather)
{
	return new Components\Config\Repository($feather);
});

define('FEATHER_DATABASE', 'feather');

$feather['config']->set('laravel: database.connections.' . FEATHER_DATABASE, $feather['config']->get('feather: feather.database'));

$feather['config']->db();

/*
|--------------------------------------------------------------------------
| Load Feather Bootstrapping
|--------------------------------------------------------------------------
|
| We can now bootstrap the remaining items.
|
*/

require path('feather') . 'bootstrap' . DS . 'views' . EXT;

require path('feather') . 'bootstrap' . DS . 'exceptions' . EXT;

require path('feather') . 'bootstrap' . DS . 'ioc' . EXT;

/*
|--------------------------------------------------------------------------
| Load Feather Components
|--------------------------------------------------------------------------
|
| Load in the Feather components.
|
*/

foreach($feather['config']->get('feather: feather.components') as $component => $resolver)
{
	if(is_callable($resolver))
	{
		$resolver($feather);
	}
}

/*
|--------------------------------------------------------------------------
| Load Feather Facades
|--------------------------------------------------------------------------
|
| Load in the Feather facades to give components a static interface through
| which methods can be accessed throughout the application.
|
*/

Components\Support\Facade::application($feather);

require path('feather') . 'facades' . EXT;

/*
|--------------------------------------------------------------------------
| Bootstrap Authentication
|--------------------------------------------------------------------------
|
| Authentication needs to be bootstrapped so that we have the correct
| auth driver set, any authenticators are registered, and the user is set.
|
*/

$feather['auth']->bootstrap();

/*
|--------------------------------------------------------------------------
| Register Feather Applications
|--------------------------------------------------------------------------
|
| Feather is split into separate applications for better separation of
| logic. Register each of the applications as defined in the configuration
| with the Laravel bundle manager.
|
*/

foreach($feather['config']->get('feather: feather.applications') as $application => $handles)
{
	$handles = trim(str_replace('(:feather)', Bundle::option('feather', 'handles'), $handles), '/');

	Bundle::register("feather {$application}", array(
		'handles'  => $handles,
		'location' => "feather/applications/{$application}"
	));

	starts_with(Request::uri(), $handles ?: Request::uri()) and Bundle::start("feather {$application}");
}

/*
|--------------------------------------------------------------------------
| Feather CLI
|--------------------------------------------------------------------------
|
| When Feather is running via the CLI we'll automatically load in Mockery
| for testing purposes only.
|
*/

if(Request::cli())
{
	require 'Mockery/Loader.php';
	require 'Hamcrest/Hamcrest.php';

	with(new \Mockery\Loader)->register();
}

/*
|--------------------------------------------------------------------------
| Theme Development Mode
|--------------------------------------------------------------------------
|
| When not running via the CLI and theme development mode is on we'll
| publish all the themes related assets.
|
*/
if($feather['config']->get('feather: db.forum.theme_development_mode') and !Request::cli())
{
	$publisher = IoC::resolve('feather: publisher');

	ob_start() and $publisher->theme((array) $feather['config']->get('feather: db.forum.theme')) and ob_clean();
}

/*
|--------------------------------------------------------------------------
| Theme Bootstrap
|--------------------------------------------------------------------------
|
| Because themes are registered as a Laravel bundle they can contain a
| start script to register any theme related assets with the container.
|
*/

Bundle::start('feather theme');