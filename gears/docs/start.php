<?php

/*
|--------------------------------------------------------------------------
| Markdown Parsing Function
|--------------------------------------------------------------------------
|
| This parsing function let's us easily parse a Markdown file.
|
*/

function md($file)
{
	// Before we parse with the Markdown parser we'll prefix any links with the plugins handler.
	$uri = URL::to_route('docs.page');

	$contents = preg_replace('/(\[.*?\])\(\/(.*?)\)/s', '$1(' . $uri . '/$2)', file_get_contents(__DIR__ . DS . 'documentation' . DS . $file . '.md'));

	return Feather\Gear\Markdown\Parse($contents);
}

/*
|--------------------------------------------------------------------------
| Navigation Item Function
|--------------------------------------------------------------------------
|
| Determines if the navigation item is to be selected or not.
|
*/

function navigation($uri)
{
	return ends_with(URI::current(), $uri) ? 'active' : null;
}