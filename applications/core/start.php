<?php namespace Feather;

use Autoloader;

/*
|--------------------------------------------------------------------------
| Feather Facade Instance
|--------------------------------------------------------------------------
|
| Get the Feather instance.
|
*/

$feather = Components\Support\Facade::application();

/*
|--------------------------------------------------------------------------
| Core Autoloadings
|--------------------------------------------------------------------------
|
| Register the core namespaces and mappings with Laravel's autoloader.
|
*/

Autoloader::namespaces(array(
	'Feather\\Models' => path('core') . 'models'
));