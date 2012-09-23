<?php namespace Feather\Core;

use Str;
use Hash;
use Feather\Config;

class User extends Base {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'users';

	/**
	 * Timestamps are enabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = true;

	/**
	 * A user can have many and belong to many roles.
	 * 
	 * @return object
	 */
	public function roles()
	{
		return $this->has_many_and_belongs_to('Feather\\Core\\Role', 'user_roles', 'user_id', 'role_id');
	}

	/**
	 * When setting a password it must be hashed before stored in the database.
	 * 
	 * @param  string  $password
	 * @return void
	 */
	public function set_password($password)
	{
		$this->set_attribute('password', Hash::make($password));
	}

	/**
	 * Getter for a users slug.
	 * 
	 * @return string
	 */
	public function get_slug()
	{
		return Str::slug($this->get_attribute('username'));
	}

	/**
	 * Getter for a users name.
	 * 
	 * @return string
	 */
	public function get_name()
	{
		return $this->get_attribute('username');
	}

	/**
	 * Getter for a users avatar URL, provided by Gravatar.
	 * 
	 * @return string
	 */
	public function get_avatar()
	{
		return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->get_attribute('email'))));
	}

	/**
	 * Register a new user and return the new user object. 
	 * 
	 * @param  array   $input
	 * @param  bool    $activated
	 * @return object
	 */
	public static function register($input)
	{
		$user = new static(array(
			'username' => $input['username'],
			'password' => $input['password'],
			'email'	   => $input['email']
		));

		if(Config::get('feather: db.registration.confirm_email'))
		{
			$user->fill(array(
				'activation_key' => Str::random(30),
				'activated'		 => 0
			));
		}

		if(!$user->save())
		{
			throw new FeatherModelException;
		}

		// Attach the users role. If the user needs to confirm their e-mail address they are given
		// the confirming role status which has an ID of 4. If not then they receive the standard
		// member role which has an ID of 2.
		$user->roles()->attach(Config::get('feather: db.registration.confirm_email') ? 4 : 2);

		return $user;
	}

	public static function edit($user, $input)
	{

	}

}