<?php namespace Feather;

use Bundle;
use Request;

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

define('FEATHER_DATABASE', 'feather');

$feather['config']->set('laravel: database.connections.' . FEATHER_DATABASE, $feather['config']->get('feather: database'));

$feather['config']->db();

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

require path('feather') . 'start' . DS . 'facades' . EXT;

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