<?php

Route::get('(:bundle)', array('as' => 'feather', 'uses' => 'feather core::index@index'));

/*
|--------------------------------------------------------------------------
| User Authentication
|--------------------------------------------------------------------------
*/

Route::any('(:bundle)/login', array('as' => 'login', 'uses' => 'feather core::index@login'));
Route::any('(:bundle)/register', array('as' => 'register', 'uses' => 'feather core::login@register'));
Route::get('(:bundle)/logout', array('as' => 'logout', 'uses' => 'feather core::login@logout'));