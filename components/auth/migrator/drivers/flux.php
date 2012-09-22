<?php namespace Feather\Components\Auth\Migrator\Drivers;

class Flux extends Driver {

	/**
	 * Attempt to log a user in on the FluxBB forum system.
	 * 
	 * @param  array  $credentials
	 * @return bool|object
	 */
	public function login($credentials)
	{
		$user = $this->db()->where_username($credentials['username'])->first();

		if(is_null($user) or sha1($credentials['password']) != $user->password)
		{
			return false;
		}

		return $user;
	}
	
}