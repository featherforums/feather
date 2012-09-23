<?php

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

Route::get('(:bundle)/members', array('as' => 'members', 'uses' => 'feather::members@index'));
Route::get('(:bundle)/profile/(:any)', array('as' => 'profile', 'uses' => 'feather::profile@index'));

/*
|--------------------------------------------------------------------------
| Places
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)/place/(:num)-(:any)/(:page?)', array('as' => 'place', 'uses' => 'feather::place@place'));

/*
|--------------------------------------------------------------------------
| Discussions and Starting Discussions
|--------------------------------------------------------------------------
*/

Route::any('(:bundle)/discussion/start', array('as' => 'start.discussion', 'uses' => 'feather::discussion@start'));
Route::any('(:bundle)/place/(:num)-(:any)/start', 'feather::discussion@start');
Route::any('(:bundle)/discussion/(:num)-(:any)/(:page?)', array('as' => 'discussion', 'uses' => 'feather::discussion@index'));
Route::any('(:bundle)/discussion/(:num)-(:any)/edit', array('as' => 'discussion.edit', 'uses' => 'feather::discussion@edit'));

/*
|--------------------------------------------------------------------------
| Rules
|--------------------------------------------------------------------------
*/

Route::get('(:bundle)/rules', array('as' => 'rules', 'uses' => 'feather core::index@rules'));