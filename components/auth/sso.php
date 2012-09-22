<?php namespace Feather\Components\Auth;

use Input;
use Request;
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
		elseif($user = User::where('username', '=', $username)->or_where('email', '=', $email)->first())
		{
			Breadcrumbs::drop(__('feather::titles.connect_to_community'));

			return View::of('layout')->with('title', __('feather::titles.connect_to_community'))
									 ->nest('content', 'feather::user.associate', compact('user'));
		}

		// If there is no user in the database then the associated user is our actual user. Does
		// that make sense? Well... we basically just create them a new account.
		return static::create($credentials, $credentials);
	}

}