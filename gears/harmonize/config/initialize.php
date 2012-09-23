<?php

/*
|--------------------------------------------------------------------------
| Harmonize Initialization
|--------------------------------------------------------------------------
|
| This closure should contain the logic for fetching a logged in user of
| your application. The 'id', 'username', and 'email' of that user need to
| be passed as credentials to the authorize method.
|
| That's it! Feather will take care of the rest and attempt to sign your
| authenticated user in.
|
*/

return function($feather)
{
	// Feather uses a custom driver for authentication. You should use the driver
	// your application uses for authentication to fetch the logged in user. Below
	// we are using the 'fluent' driver to fetch the logged in user.
	if($user = Auth::driver('fluent')->user())
	{
		$credentials = array(
			'id'	   => $user->id,
			'username' => $user->username,
			'email'	   => $user->email
		);

		// Attempt to authorize the user. Feather will deal with the response,
		// all that needs to be done is the credentials passed in.
		return $feather['sso']->authorize($credentials);
	}
}