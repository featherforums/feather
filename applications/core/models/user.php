<?php namespace Feather\Core;

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

}