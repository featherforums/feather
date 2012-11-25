<?php namespace Feather\Models;

use Str;
use Hash;
use Illuminate\Auth\UserInterface;

class User extends BaseModel implements UserInterface {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public $table = 'users';

	/**
	 * Timestamps are enabled.
	 * 
	 * @var bool
	 */
	public $timestamps = true;

	/**
	 * Get the unique identifier for user authentication.
	 * 
	 * @return int
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for user authentication.
	 * 
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * A user can have many and belong to many roles.
	 * 
	 * @return object
	 */
	public function roles()
	{
		return $this->belongsToMany('Feather\\Core\\Role', 'user_roles', 'user_id', 'role_id');
	}

	/**
	 * When setting a password it must be hashed before stored in the database.
	 * 
	 * @param  string  $password
	 * @return void
	 */
	public function setPassword($password)
	{
		$this->setAttribute('password', Hash::make($password));
	}

	/**
	 * Getter for a users slug.
	 * 
	 * @return string
	 */
	public function getSlug()
	{
		return Str::slug($this->getAttribute('username'));
	}

	/**
	 * Getter for a users name.
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->getAttribute('username');
	}

	/**
	 * Getter for a users avatar URL, provided by Gravatar.
	 * 
	 * @return string
	 */
	public function getAvatar()
	{
		return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->getAttribute('email'))));
	}

	/**
	 * Register a new user and return the new user object. 
	 * 
	 * @param  array   $input
	 * @param  bool    $activated
	 * @return Feather\Core\User
	 */
	public function register($input)
	{
		$this->fill(array(
			'username' => $input['username'],
			'password' => $input['password'],
			'email'	   => $input['email']
		));

		if(Config::get('feather: db.registration.confirm_email'))
		{
			$this->fill(array(
				'activation_key' => Str::random(30),
				'activated'		 => 0
			));
		}

		if(!$this->save())
		{
			throw new FeatherModelException;
		}

		// Attach the users role. If the user needs to confirm their e-mail address they are given
		// the confirming role status which has an ID of 4. If not then they receive the standard
		// member role which has an ID of 2.
		$this->roles()->attach(Config::get('feather: db.registration.confirm_email') ? 4 : 2);

		return $this;
	}

	public static function edit($user, $input)
	{

	}

	/**
	 * Register a new user and associate it with an e-mail from an authenticator.
	 * 
	 * @param  array  $associate
	 * @param  array  $input
	 * @return Feather\Core\User
	 */
	public static function associate($associate, $input)
	{
		$user = new static(array(
			'username'						 => $input['username'],
			'authenticator'					 => Config::get('feather: db.auth.driver'),
			'authenticator_token'			 => $associate['token'],
			'authenticator_associated_email' => $associate['email'],
			'email'							 => $input['email']
		));

		if(!$user->save())
		{
			throw new FeatherModelException;
		}

		$user->roles()->attach(2);

		return $user;
	}

}