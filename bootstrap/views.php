<?php namespace Feather;

use Str;
use View;
use Asset;
use Event;
use Bundle;

/*
|--------------------------------------------------------------------------
| View Bootstrapping
|--------------------------------------------------------------------------
*/

Asset::container('theme')->bundle('feather/themes/' . $feather['config']->get('feather: db.forum.theme', 'default'));

set_path('theme', path('themes') . $feather['config']->get('feather: db.forum.theme', 'default') . DS);

Bundle::register('feather theme', array(
	'location' => 'path: ' . path('theme')
));

/*
|--------------------------------------------------------------------------
| View Event Override
|--------------------------------------------------------------------------
|
| Feather views behave differently to standard Laravel views. Templates
| have the ability to override views found in applications. This allows
| templates to provide custom structuring depending on the features the
| template offers.
|
| To enable this functionality Feather must override the event loader of
| view files.
|
*/

Event::override(View::loader, function($bundle, $view)
{
	if(!str_contains($bundle, ' '))
	{
		$bundle = "feather {$bundle}";
	}

	$path = Bundle::path($bundle) . 'views';

	if(!is_null(View::file($bundle, $view, path('theme') . 'views')))
	{
		$path = path('theme') . 'views';
	}

	if(str_contains($view, ': '))
	{
		list($directory, $view) = explode(': ', $view);

		list($name, $view) = explode(' ', $view);

		if(!is_null(View::file($bundle, $view, path('feather') . Str::plural($directory) . DS . $name . DS . 'views')))
		{
			$path = path('feather') . Str::plural($directory) . DS . $name . DS . 'views';
		}
	}

	return View::file($bundle, $view, $path);
});

/*
|--------------------------------------------------------------------------
| Global Feather Variable
|--------------------------------------------------------------------------
|
| The feather global view variable is shared across all views. It contains
| some basic information about the forum as well as the currently logged in
| user.
|
*/

Event::listen('auth: started', function() use ($feather)
{
	$user = array();

	if($feather['auth']->online())
	{
		$user = $feather['auth']->user();
	}

	View::share('feather', (object) array_merge($feather['config']->get('feather: db.forum'), array('user' => (object) $user)));
});

/*
|--------------------------------------------------------------------------
| Default Variable Assignment
|--------------------------------------------------------------------------
|
| Some variables are used almost always inside a template. To ensure these
| varaiables are always set we'll set them if they are not currently set.
|
*/

$defaults = function($view)
{
	if(!isset($view->title)) $view->title = 'Index';

	if(!isset($view->alert)) $view->alert = null;
};

View::composer('feather core::template', $defaults);
View::composer('feather admin::template', $defaults);