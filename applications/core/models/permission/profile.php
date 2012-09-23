<?php namespace Feather\Core\Permission;

use Feather\Core\Base;

class Profile extends Base {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'permission_profiles';

	/**
	 * A profile has many permissions.
	 * 
	 * @return object
	 */
	public function permissions()
	{
		return $this->has_many('Feather\\Admin\\Core\\Permission', 'profile_id');
	}

}