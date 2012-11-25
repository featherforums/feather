<?php namespace Feather\Auth;

use Feather\Models\User;
use Illuminate\Auth\GenericUser;
use Illuminate\Auth\UserInterface;
use Feather\Migrator\MigratorManager;
use Illuminate\Hashing\HasherInterface;
use Illuminate\Auth\UserProviderInterface;

class FeatherUserProvider implements UserProviderInterface {

	/**
	 * Hasher implementation.
	 *
	 * @var Illuminate\Hashing\HasherInterface
	 */
	protected $hasher;

	/**
	 * Migrator instance.
	 * 
	 * @var Feather\Migrator\MigratorManager
	 */
	protected $migrator;

	/**
	 * Create a new database user provider.
	 *
	 * @param  Illuminate\Hashing\HasherInterface  $hasher
	 * @param  Feather\Migrator\MigratorManager  $migrator
	 * @return void
	 */
	public function __construct(HasherInterface $hasher, MigratorManager $migrator = null)
	{
		$this->hasher = $hasher;
		$this->migrator = $migrator;
	}

	/**
	 * Retrieve a user by their unique idenetifier.
	 *
	 * @param  mixed  $identifier
	 * @return Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByID($identifier)
	{
		$user = User::find($identifier);

		if ( ! is_null($user))
		{
			return $user;
		}
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// First we'll attempt to locate a user by checking for the username they provided when logging in.
		if ( ! $user = User::where('username', $credentials['username'])->first())
		{
			return;
		}

		// If we have a migrator interface then we need confirm that the user has been migrated previously. If
		// the user has not been migrated then we'll leave it to the migrator to handle it.
		if ($this->migrator->isActive())
		{
			dd('here');
		}


		if ( ! is_null($user))
		{
			return $user;
		}
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  Illuminate\Auth\UserInterface  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials)
	{
		$plain = $credentials['password'];

		return $this->hasher->check($plain, $user->getAuthPassword());
	}

}