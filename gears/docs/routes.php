<?php

/*
|--------------------------------------------------------------------------
| Documentation Homepage
|--------------------------------------------------------------------------
|
| The landing page for the documentation.
|
*/

Route::get('(:bundle)', array('as' => 'docs.home', function()
{
	return View::make('gear: docs page')->with('content', md('home'));
}));

/*
|--------------------------------------------------------------------------
| Documentation Viewing
|--------------------------------------------------------------------------
|
| Viewing a specific page within the documentation.
|
*/

Route::get('(:bundle)/(:all)', array('as' => 'docs.page', function()
{
	$file = str_replace('-', '', rtrim(implode('/', func_get_args()), '/'));

	if(file_exists(__DIR__ . DS . 'documentation' . DS . $file . '.md'))
	{
		return View::make('gear: docs page')->with('content', md($file));
	}
	elseif(file_exists(__DIR__ . DS . 'documentation' . DS . $file . DS . 'home.md'))
	{
		return View::make('gear: docs page')->with('content', md($file . DS . 'home.md'));
	}

	return Response::error('404');
}));