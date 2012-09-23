<?php

return array(

	'login' => function($validator)
	{
		$validator->rule('username', 'required')
				  ->rule('password', 'required')
				  ->message('username.required', 'login.messages.username.is_required')
				  ->message('password.required', 'login.messages.password.is_required');
	},

	'register' => function($validator, $feather)
	{
		$validator->rule('username', array('required', 'min:3', 'max:20', 'alpha_dot', 'unique:users,username'))
				  ->rule('password', array('required', 'min:7', 'max:15', 'confirmed'))
				  ->rule('password_confirmation', array('required'))
				  ->rule('email', array('required', 'email', 'unique:users,email'))
				  ->message('username.required', 'register.messages.username.is_required')
				  ->message('username.min', array('register.messages.username.too_short', array('length' => 3)))
				  ->message('username.max', array('register.messages.username.too_long', array('length' => 20)))
				  ->message('username.alpha_dot', 'register.messages.username.is_invalid')
				  ->message('username.unique', 'register.messages.username.already_exists')
				  ->message('password.required', 'register.messages.password.is_required')
				  ->message('password.min', array('register.messages.password.too_short', array('length' => 7)))
				  ->message('password.max', array('register.messages.password.too_long', array('length' => 15)))
				  ->message('password.confirmed', 'register.messages.password.did_not_match')
				  ->message('password_confirmation.required', 'register.messages.password_confirmation.is_required')
				  ->message('email.required', 'register.messages.email.is_required')
				  ->message('email.email', 'register.messages.email.invalid')
				  ->message('email.unique', 'register.messages.email.already_exists');

		if($feather['config']->get('feather: db.registration.rules'))
		{
			$validator->rule('rules', 'accepted')
					  ->message('rules.accepted', 'register.messages.rules.not_accepted');
		}
	}
);