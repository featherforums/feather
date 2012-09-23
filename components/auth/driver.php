<?php namespace Feather\Components\Auth;

use Hash;
use Cache;
use Feather\Core;

class Driver extends \Laravel\Auth\Drivers\Driver {

	/**
	 * Fetch the current logged in user of Feather. If no user is logged in a default
	 * Guest user is returned.
	 * 
	 * @param  int  $id
	 * @return object
	 */
	public function retrieve($id)
	{
		if(!is_null($id))
		{
			return Cache::remember("user_{$id}", function() use ($id)
			{
				$user = Core\User::with(array('roles'))->find($id);

				// To provide a cleaner syntax when accessing roles, we'll make each of the roles keys
				// the same as its ID.
				$roles = array();

				foreach($user->roles as $role) $roles[$role->id] = $role;

				$user->relationships['roles'] = $roles;

				return $user;
			}, Core\User::cache_time);
		}
		else
		{
			return Cache::remember('guest', function()
			{
				return new Core\User(array(
					'roles' => array(3 => Core\Role::find(3))	
				), true);
			}, Core\User::cache_time);
		}
	}

	/**
	 * Attempt to log a user in and if need be migrate them from an older system.
	 * 
	 * @param  array  $credentials
	 * @return bool|object
	 */
	public function attempt($credentials = array())
	{
		// Find the user in the database with the username they have provided in the
		// login form. If no user can be found then that's as far as we go.
		if(!$user = Core\User::where_username($credentials['username'])->first())
		{
			return false;
		}

		// If there is an active migration from another forum we'll need to perform
		// a couple of extra checks. A migrating user has only a single role, which
		// is the migrating roll. If the user has not been migrated then we'll
		// attempt to migrate them.
		if($migration = Core\Migration::active())
		{
			$driver = Migrator\Drivers\Driver::make($migration);

			if($user->roles()->only('roles.id') == 6 and ($old = $driver->login($credentials)))
			{
				// Because the users password is most likely hashed - it damn
				// well better be - we'll replace the password key in the array
				// with the password they logged in with. That way Feather can
				// re-hash it when storing it.
				$old = (object) array_merge((array) $old, array('password' => $credentials['password']));

				// Finally attempt to migrate the user. If the migration fails it's
				// logged so that the administrator can take further action if
				// required.
				if(!$user = $driver->migrate_user($old, $user))
				{
					dd('Failed to migrate user.');

					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			// For a user that doesn't need to be migrated a simple hash check is in order.
			if(!Hash::check($credentials['password'], $user->password))
			{
				return false;
			}
		}
		
		return $this->login($user, array_get($credentials, 'remember'));
	}

	/**
	 * Log a user in to Feather.
	 * 
	 * @param  int|Feather\Core\User  $user
	 * @param  bool                     $remember
	 * @return bool
	 */
	public function login($user, $remember = false)
	{
		if($user instanceof Core\User)
		{
			$user = $user->id;
		}

		return parent::login($user, $remember);
	}

}