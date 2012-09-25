<?php namespace Feather;

use View;
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

Autoloader::map(array(
	'Feather_API_Controller' => path('core') . 'controllers' . DS . 'api' . EXT,
	'Feather_Base_Controller' => path('core') . 'controllers' . DS . 'base' . EXT
));

/*
|--------------------------------------------------------------------------
| Core Template
|--------------------------------------------------------------------------
|
| Name the core template.
|
*/

View::name('feather core::template', 'template');