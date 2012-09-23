<?php

/*
|--------------------------------------------------------------------------
| Login Language Strings
|--------------------------------------------------------------------------
|
| English language rules for login related tasks.
|
*/

return array(

	/*
	|--------------------------------------------------------------------------
	| Login Failure
	|--------------------------------------------------------------------------
	|
	| English language message for a failed login.
	|
	*/

	'failure' => 'Your username and/or password is incorrect.',

	/*
	|--------------------------------------------------------------------------
	| Login Validation Messages
	|--------------------------------------------------------------------------
	|
	| English language messages for individual field errors.
	|
	*/

	'messages' => array(
		'username' => array('is_required' => 'You did not enter a username.'),
		'password' => array('is_required' => 'You did not enter a password.')
	),

	/*
	|--------------------------------------------------------------------------
	| Login Form Labels
	|--------------------------------------------------------------------------
	|
	| English language messages for login page form labels.
	|
	*/

	'labels' => array(
		'remember' => array(
			'title'  => 'Remember me',
			'helper' => 'If yes, when you next return you\'ll be automatically signed in.'
		),
		'register' => array(
			'title' => 'No account? Create a new account today!'
		)
	)

);