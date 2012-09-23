<?php namespace Feather\Components\Auth;

use Auth;
use Route;
use Session;
use InvalidArgumentException;
use Feather\Components\Foundation\Component;

class Authorizer extends Component {

	/**
	 * The current logged in user.
	 * 
	 * @var mixed
	 */
	public $user;

	/**
	 * Bootstrap the authenticator.
	 * 
	 * @return void
	 */
	public function bootstrap()
	{
		Auth::extend('feather', function()
		{
			return new Driver;
		});

		// Override the Laravel authentication driver for this run only.
		$this->feather['config']->set('laravel: auth.driver', 'feather');

		$this->user = $this->user();

		// Depending on the authenticator being used their may be a response thrown back that
		// interupts the normal response of Feather.
		$authenticator = $this->feather['config']->get('feather: db.auth.driver');

		if(!$this->online() and ($response = $this->feather['gear']->first("auth: bootstrap {$authenticator}")))
		{
			Route::filter('feather::before', function() use ($response)
			{
				return $response;
			});
		}

		$this->feather['gear']->fire('auth: started');
	}

	/**
	 * Access control prevents users without specific permissions from accessing certain parts
	 * of Feather. Actions can have rules defined in the configuration file. This method will
	 * check a user to see if they have the correct permissions to perform the given action.
	 * 
	 * @param  string  $action
	 * @param  object  $resource
	 * @return bool
	 */
	public function can($action, $resource = null)
	{
		if(!str_contains($action, ': '))
		{
			throw new InvalidArgumentException("Invalid action [{$action}] supplied to ACL.");
		}

		list($verb, $action) = explode(': ', $action);

		// To make things look lovely when writing actions we'll replace any spaces with underscores
		// as that's how role permissions are stored in the database.
		$action = str_replace(' ', '_', $action);

		foreach($this->user->roles as $role)
		{
			if($role->{"{$verb}_{$action}"})
			{
				// If we have a valid resource then we'll need to see if we have a rule in the configuration
				// for this action. If there is no rule then there is further checking that needs to be done
				// on the resource.
				if(!is_null($resource))
				{
					if($this->feather['config']->has("feather: auth.rules.{$verb}.{$action}"))
					{
						$callback = $this->feather['config']->get("feather: auth.rules.{$verb}.{$action}");

						return $callback($resource);
					}

					continue;
				}
				
				// If there was no valid resource then the action was simply a role based action. We
				// can return true here because the user has the correct permissions for this action.
				return true;
			}
		}

		// Sorry chum, but you can't do that!
		return false;
	}

	/**
	 * The complete opposite of above, determines if a user is not able to perform an action.
	 * 
	 * @param  string  $action
	 * @param  object  $resource
	 * @return bool
	 */
	public function cannot($action, $resource = null)
	{
		return !$this->can($action, $resource);
	}

	/**
	 * Determines if a user is a given role or roles.
	 * 
	 * @param  array  $roles
	 * @return bool
	 */
	public function is($roles)
	{
		if(!is_array($roles))
		{
			$roles = array($roles);
		}

		// If the role has an aliased name then make sure we grab the correct name of
		// the role from the aliases array.
		$aliases = $this->feather['config']->get('feather: auth.aliases');

		foreach($roles as $key => $role)
		{
			if(array_key_exists(strtolower($role), $aliases))
			{
				$roles[$key] = $aliases[strtolower($role)];
			}
		}

		foreach($this->user->roles as $role)
		{
			if(in_array(strtolower($role->name), $roles))
			{
				return true;
			}

			// Because some roles can be granted a super moderator permission, if we
			// are checking that a user is a moderator we'll also check that the role
			// is a super moderator.
			if(in_array('moderator', $roles) and $role->super_moderator)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Determines if a user is not a given role or roles.
	 * 
	 * @param  array  $roles
	 * @return bool
	 */
	public function not($roles)
	{
		return !$this->is($roles);
	}

	/**
	 * Determines if a user is online.
	 * 
	 * @return bool
	 */
	public function online()
	{
		return !$this->is('guest');
	}

	/**
	 * Determines if a user is offline.
	 * 
	 * @return bool
	 */
	public function offline()
	{
		return !$this->online();
	}

	/**
	 * Determines if a user has activated their account.
	 * 
	 * @return bool
	 */
	public function activated()
	{
		return (bool) $this->user->activated;
	}

	/**
	 * Logs a user out and flushes the session.
	 * 
	 * @return void
	 */
	public function logout()
	{
		Session::flush();

		Auth::logout();
	}

	/**
	 * Route methods that are uncallable through to Laravel's Auth class. This provids a clean
	 * interface for accessing methods on that class.
	 * 
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array('Auth', $method), $parameters);
	}

}