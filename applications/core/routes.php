<?php

/*
|--------------------------------------------------------------------------
| Feather Homepage
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)', array('as' => 'feather', 'uses' => 'feather core::index@index'));

/*
|--------------------------------------------------------------------------
| User Authentication
|--------------------------------------------------------------------------
*/

Route::any('(:bundle)/login', array('as' => 'login', 'uses' => 'feather core::index@login'));
Route::any('(:bundle)/register', array('as' => 'register', 'uses' => 'feather core::index@register'));
Route::get('(:bundle)/logout', array('as' => 'logout', 'uses' => 'feather core::index@logout'));

/*
|--------------------------------------------------------------------------
| Members and Profiles
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)/members', array('as' => 'members', 'uses' => 'feather core::members@index'));
Route::get('(:bundle)/profile/(:any)', array('as' => 'profile', 'uses' => 'feather core::profile@index'));

/*
|--------------------------------------------------------------------------
| Places
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)/place/(:num)-(:any)/(:page?)', array('as' => 'place', 'uses' => 'feather core::place@index'));

/*
|--------------------------------------------------------------------------
| Discussions and Starting Discussions
|--------------------------------------------------------------------------
*/

Route::any('(:bundle)/discussion/start', array('as' => 'start.discussion', 'uses' => 'feather core::discussion@start'));
Route::any('(:bundle)/place/(:num)-(:any)/start', 'feather core::discussion@start');
Route::any('(:bundle)/discussion/(:num)-(:any)/(:page?)', array('as' => 'discussion', 'uses' => 'feather core::discussion@index'));
Route::any('(:bundle)/discussion/(:num)-(:any)/edit', array('as' => 'discussion.edit', 'uses' => 'feather core::discussion@edit'));
Route::post('(:bundle)/discussion/preview', 'feather core::discussion@preview');

/*
|--------------------------------------------------------------------------
| Rules
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)/rules', array('as' => 'rules', 'uses' => 'feather core::index@rules'));

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)/api/(:any)/(:any).(json)', 'feather core::api.(:1).(:2)@index');
Route::get('(:bundle)/api/(:any)/(:any)/(:any).(json)', 'feather core::api.(:1).(:2)@(:3)');

/*
|--------------------------------------------------------------------------
| Feather Catch-all
|--------------------------------------------------------------------------
|
| This catch-all route allows plugins to create new controllers and actions
| on the fly.
|
*/

Route::get('(:bundle)/(:all)', function($parameters)
{
	$parameters = explode('/', $parameters);

	// Update the Request::$route property so that it contains the controller
	// and controller_action properties.
	Request::$route->controller = array_shift($parameters);

	Request::$route->controller_action = empty($parameters) ? 'index' : array_shift($parameters);

	return IoC::resolve('controller: base')->execute(Request::$route->controller_action, $parameters);
});