<?php namespace Feather;

/*
|--------------------------------------------------------------------------
| Feather Version Definition
|--------------------------------------------------------------------------
|
| Define the Feather version.
|
*/

if ( ! defined('FEATHER_VERSION'))
{
	define('FEATHER_VERSION', '1.0.0');
}

/*
|--------------------------------------------------------------------------
| Feather Extension and Theme Paths
|--------------------------------------------------------------------------
|
| Define the paths to Feather's extension and theme directories.
|
*/

$app['path.extensions'] = $app['path'].'/extensions';
$app['path.themes'] = $app['path'].'/themes';

/*
|--------------------------------------------------------------------------
| Feather Configuration
|--------------------------------------------------------------------------
|
| Register some important configuration options that allows Feather to run
| smoothly.
|
*/

if ( ! $app->runningInConsole())
{
	if ($app['cache']->has('config'))
	{
		$app['cache']->forget('config');
	}

	$config = new Models\Config;

	foreach ($config->everything() as $item)
	{
		$app['config']->set("feather.{$item->name}", $item->value);
	}
}

/*
|--------------------------------------------------------------------------
| Feather Providers
|--------------------------------------------------------------------------
|
| Now that most of Feather has been bootstrapped we can register the other
| providers that Feather uses. This keeps everything neat and tidy.
|
*/

$providers = array(
	'Feather\Presenter\PresenterServiceProvider',
	'Feather\Extensions\ExtensionServiceProvider'
);

foreach ($providers as $provider)
{
	$app->register(new $provider($app));
}

/*
|--------------------------------------------------------------------------
| Require The Facades File
|--------------------------------------------------------------------------
|
| We'll now register some facades to easily access some of Feather's
| components statically.
|
*/

require_once __DIR__.'/facades.php';

/*
|--------------------------------------------------------------------------
| Require The Console File
|--------------------------------------------------------------------------
|
| Commands are registered with the application container and when Artisan
| is run they are resolved from the Artisan start file.
|
*/

require __DIR__.'/console.php';

/*
|--------------------------------------------------------------------------
| Prepare Feather Presenter
|--------------------------------------------------------------------------
|
| Presenter is responsible for Feather's views. We'll prepare the presenter
| now, which will start the theme and set some view paths.
|
*/

$app['feather.presenter']->prepare();

/*
|--------------------------------------------------------------------------
| Feather Extensions
|--------------------------------------------------------------------------
|
| Register the activated extensions with Feather.
|
*/

if ( ! $app->runningInConsole())
{
	$extensions = new Models\Extension;

	$app['feather.extensions']->registerExtensions($extensions->getActivated());
}

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require __DIR__.'/../filters.php';