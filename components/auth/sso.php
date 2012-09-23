<?php namespace Feather\Components\Auth;

use View;
use Input;
use Request;
use Feather\Core\User;
use FeatherValidationException;
use Feather\Components\Foundation\Component;

class SSO extends Component {

	/**
	 * Default credentials.
	 * 
	 * @var array
	 */
	public $defaults = array(
		'id'	   => null,
		'username' => null,
		'email'	   => null,
		'token'	   => null	
	);

	/**
	 * Authorizes a user from an external application.
	 * 
	 * @param  array  $credentials
	 * @return mixed
	 */
	public function authorize(array $credentials)
	{
		$credentials = array_merge($this->defaults, $credentials);

		// If the current request is POST then the user is either registering or
		// connecting an account.
		if(Request::method() == 'POST')
		{
			$method = Input::has('create') ? 'register' : 'connect';

			return $this->{$method}($credentials);
		}

		$required = array('id', 'email', 'username');

		foreach($required as $credential)
		{
			if(!array_key_exists($credential, $credentials) or is_null($credentials[$credential]))
			{
				return;
			}
		}

		extract($credentials);		

		// If a user exists with an associated e-mail address that matches then this user has already
		// been authenticated with a SSO service.
		if($user = User::where_authenticator_associated_email($email)->first())
		{
			// An authenticators token may change from time to time. If the tokens are no longer
			// the same then the token must be updated.
			if($user->authenticator_token != $token)
			{
				$user->authenticator_token = $token;

				$user->save();
			}

			$this->feather['auth']->login($user);

			return $this->feather['redirect']->to_self();
		}
		
		// If there is a user in the database who has the same e-mail or username as our
		// user then they need to either link their account or create a new one.
		elseif($user = User::where_username($username)->or_where('email', '=', $email)->first())
		{
			$this->feather['crumbs']->drop(__('feather core::titles.connect_to_community'));

			return View::of('template')->with('title', __('feather core::titles.connect_to_community'))
									   ->nest('content', 'feather core::user.associate', compact('user'));
		}

		// If there is no user in the database then the user does not need to perform any action to
		// connect or create their account. We can simply authorize them to continue using Feather.
		// ALl of this is behind the scenes and very quick, the user sees nothing.
		$user = User::associate($credentials, $credentials);

		$this->feather['auth']->login($user);

		return $this->feather['redirect']->to_self();
	}

	/**
	 * Attempt to create a user to be associated with the foreign application.
	 * 
	 * @param  array  $associate
	 * @return object
	 */
	protected function create($associate)
	{
		try
		{
			$this->feather['validator']->get('auth.sso.create')->against(Input::get())->passes();
		}
		catch (FeatherValidationException $errors)
		{
			return $this->feather['redirect']->to_self()->with_input()->with_errors($errors->get());
		}

		Input::replace(array(
			'username' => Input::get('create_username'),
			'email'	   => Input::get('create_email')
		));

		if($user = User::associate($associate, $input))
		{
			$this->feather['auth']->login($user);

			return $this->feather['redirect']->to_self();
		}
		else
		{
			return $this->feather['redirect']->to_self()->with_input()->alert('failed', 'feather core::register.failure');
		}
	}

	/**
	 * Attempts to connect a user to an existing Feather account.
	 * 
	 * @param  array  $associate
	 * @return object
	 */
	protected function connect($associate)
	{
		try
		{
			$this->feather['validator']->get('auth.connect')->against(Input::get())->passes();
		}
		catch (FeatherValidationException $errors)
		{
			return $this->feather['redirect']->to_self()->with_input()->with_errors($errors->get());
		}

		$user = User::where_email(Input::get('connect_email'))->first();

		// If we are able to login with the username retrieved from the database and the password
		// provided by the user then we can update the assoicated e-mail and redirect the user.
		if($this->attempt(array('username' => $user->username, 'password' => Input::get('connect_password'))))
		{
			$user->fill(array(
				'authenticator'					 => $this->feather['config']->get('feather: db.auth.driver'),
				'authenticator_associated_email' => $associate['email'],
				'authenticator_token'			 => $associate['token']
			));

			$user->save();

			return $this->feather['redirect']->to_self();
		}

		return $this->feather['redirect']->to_self()->with_input()->alert('error', 'feather core::connect.failure');
	}

}