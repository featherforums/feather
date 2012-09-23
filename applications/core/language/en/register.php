<?php

/*
|--------------------------------------------------------------------------
| Register Language Strings
|--------------------------------------------------------------------------
|
| English language rules for register related tasks.
|
*/

return array(

	/*
	|--------------------------------------------------------------------------
	| Register Failure
	|--------------------------------------------------------------------------
	|
	| English language message for a failed registration.
	|
	*/

	'failure' => 'Your registration was unsuccessful. Please try again later or get in touch with an admin.',

	/*
	|--------------------------------------------------------------------------
	| Register Validation Messages
	|--------------------------------------------------------------------------
	|
	| English language messages for individual field errors.
	|
	*/

	'messages' => array(
		'username' => array(
			'is_required'    => 'You did not enter a username.',
			'too_short'      => 'Your username must be at least :length characters.',
			'too_long'	     => 'Your username must be less than :length characters.',
			'is_invalid'     => 'Your username contained invalid characters.',
			'already_exists' => 'An account already exists with that username.'
		),
		'password' => array(
			'is_required'   => 'You did not enter a password.',
			'too_short'	    => 'Your password must be at least :length characters.',
			'too_long'	    => 'Your password must be less than :length characters.',
			'did_not_match' => 'The passwords you entered did not match.',
		),
		'password_confirmation' => array(
			'is_required'   => 'You did not confirm your password.',
		),
		'email' => array(
			'is_required'    => 'You did not enter an e-mail address.',
			'invalid'	     => 'The e-mail you entered is invalid.',
			'already_exists' => 'An account already exists with that e-mail.',
			'does_not_exist' => 'The e-mail you entered does not exist.'
		),
		'rules' => array(
			'not_accepted' => 'You must read and accept the community rules.'
		)
	),

	/*
	|--------------------------------------------------------------------------
	| Register Form Labels
	|--------------------------------------------------------------------------
	|
	| English language messages for register page form labels.
	|
	*/

	'labels' => array(
		'password_confirmation' => array(
			'title' => 'Confirm Password'
		),
		'rules' => array(
			'helper' => 'I have read and agree to the :link.'
		)
	)
	

);