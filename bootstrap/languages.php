<?php namespace Feather;

use Str;
use Lang;
use Event;

/*
|--------------------------------------------------------------------------
| Laravel Language Loader
|--------------------------------------------------------------------------
|
| Override the Laravel Language Loader so that Gear language files can
| be loaded.
|
*/

Event::override(Lang::loader, function($bundle, $language, $file)
{
	// If the file contains a colon then we are loading based on Feather's file
	// naming convention. An example is 'gear: {gear.name} {key.item}'.
	if(str_contains($file, ': '))
	{
		list($directory, $file) = explode(': ', $file);

		// The directory porition of the resource is pluralized.
		$directory = Str::plural($directory);

		if(str_contains($file, ' '))
		{
			list($name, $file) = explode(' ', $file);

			$directory = $directory . DS . $name;
		}

		if(file_exists($path = path('feather') . $directory . DS . 'language' . DS . $language . DS . $file . EXT))
		{
			return require $path;
		}
	}

	return Lang::file($bundle, $language, $file);
});