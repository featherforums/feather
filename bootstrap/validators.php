<?php namespace Feather;

/*
|--------------------------------------------------------------------------
| Alpha Dot
|--------------------------------------------------------------------------
|
| Text can only contain alpha characters and a period but can not end with
| a period.
|
*/

\Validator::register('alpha_dot', function($attribute, $value)
{
	return preg_match('/^([a-z0-9\.])+$/i', $value) ? (ends_with($value, '.') ? false : true) : false;
});