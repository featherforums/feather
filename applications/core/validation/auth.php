<?php

return array(

	'login' => function($validator)
	{
		$validator->rule('username', 'required')
				  ->rule('password', 'required')
				  ->message('username.required', 'login.messages.username.is_required')
				  ->message('password.required', 'login.messages.password.is_required');
	}

);