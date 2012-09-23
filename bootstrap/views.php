<?php namespace Feather;

use Str;
use HTML;
use View;
use Asset;
use Event;
use Blade;
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

/*
|--------------------------------------------------------------------------
| Blade Variable Assignment
|--------------------------------------------------------------------------
|
| Assign variables within Blade.
|
*/

Blade::extend(function($value)
{
	return preg_replace('/(\s*)@assign\s*\(\$(.*), (.*)\)(\s*)/', '$1<?php $$2 = $3; ?>$4', $value);
});

/*
|--------------------------------------------------------------------------
| Blade Events
|--------------------------------------------------------------------------
|
| Fire custom Gear events.
|
*/

Blade::extend(function($value)
{
	$matcher = Blade::matcher('event');

	return preg_replace($matcher, '$1<?php echo Feather\Gear::fire$2; ?>', $value);
});

/*
|--------------------------------------------------------------------------
| Blade Inline Errors
|--------------------------------------------------------------------------
|
| Display inline errors for a specific form element.
|
*/

Blade::extend(function($value)
{
	$matcher = Blade::matcher('error');

	return preg_replace($matcher, '$1<?php echo $errors->has$2 ? view("feather core::error.inline", array("error" => $errors->first$1)) : null; ?>', $value);
});

/*
|--------------------------------------------------------------------------
| Blade Errors
|--------------------------------------------------------------------------
|
| Display all errors for a form.
|
*/

Blade::extend(function($value)
{
	$matcher = Blade::matcher('errors');

	return preg_replace($matcher, '$1<?php echo $errors->all() ? view("feather core::error.page", array("errors" => $errors->all())) : null; ?>', $value);
});

/*
|--------------------------------------------------------------------------
| HTML::link_to_new_discussion() Macro
|--------------------------------------------------------------------------
|
| Custom HTML macro to link to the new discussion page.
|
*/

HTML::macro('link_to_new_discussion', function($title, $attributes = array()) use ($feather)
{
	$uri = URI::current();

	if(str_contains($uri, 'place'))
	{
		$url = preg_replace('/\/p([0-9]+)/', '', $uri) . (ends_with($uri, 'start') ? null : '/start');

		preg_match('/(\d+)-.*?/', $uri, $matches);

		// If the user cannot start discussions on the selected place, don't show the button.
		if($feather['auth']->cannot('start: discussions', Feather\Models\Place::find(array_pop($matches))))
		{
			return null;
		}
	}
	else
	{
		$url = URL::to_route('start.discussion');
	}

	return HTML::link($url, $title, $attributes);
});