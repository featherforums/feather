<?php namespace Feather\Core;

use Feather\Core\Base;

class Permission extends Base {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'permissions';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = false;

	/**
	 * Permissions can belong to a profile.
	 * 
	 * @return object
	 */
	public function profile()
	{
		return $this->belongs_to('Feather\\Core\\Permission\\Profile', 'profile_id');
	}

}