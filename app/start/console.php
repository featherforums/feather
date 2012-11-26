<?php namespace Feather;

/*
|--------------------------------------------------------------------------
| Feather Command
|--------------------------------------------------------------------------
|
| The feather command shows the current version of Feather.
|
*/

$app['command.feather'] = $app->share(function($app)
{
	return new Console\FeatherCommand;
});

/*
|--------------------------------------------------------------------------
| Publish Command
|--------------------------------------------------------------------------
|
| The publish command is responsible for publishing assets in both themes
| and extensions.
|
*/

$app['command.feather.publish'] = $app->share(function($app)
{
	return new Console\PublishCommand($app['asset.publisher'], $app['path.themes'], $app['path.extensions']);
});

/*
|--------------------------------------------------------------------------
| Install Command
|--------------------------------------------------------------------------
|
| The install command is responsible for installing Feather in the current
| location.
|
*/

$app['command.feather.install'] = $app->share(function($app)
{
	return new Console\InstallCommand;
});