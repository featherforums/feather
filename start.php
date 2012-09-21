<?php namespace Feather;

use Laravel\Bundle;

/*
|--------------------------------------------------------------------------
| Path to Feather
|--------------------------------------------------------------------------
|
| Define some paths to Feather applications and resources.
|
*/

set_path('feather', __DIR__ . DS);

set_path('core', path('feather') . 'app' . DS . 'core' . DS);

set_path('admin', path('feather') . 'app' . DS . 'admin' . DS);

set_path('gears', path('feather') . 'gears' . DS);

set_path('themes', path('feather') . 'themes' . DS);

/*
|--------------------------------------------------------------------------
| Core Autoloading
|--------------------------------------------------------------------------
|
| Register the Feather namespaces and mappings Laravel's autoloader.
|
*/

require path('feather') . 'start' . DS . 'autoloading' . EXT;

/*
|--------------------------------------------------------------------------
| Bootstrap Feather
|--------------------------------------------------------------------------
|
| Instantiate a new Feather application instance.
|
*/

$feather = new Components\Feather\Application;

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

$feather['config'] = $feather->share(function()
{
	return new Components\Config\Repository;
});

$feather['config']->set('laravel: database.connections.feather', $feather['config']->get('feather: database'));

//$feather['config']->db();

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

