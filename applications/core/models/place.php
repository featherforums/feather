<?php namespace Feather\Core;

class Place extends Ness {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'places';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = false;

	/**
	 * A place has many permissions.
	 * 
	 * @return object
	 */
	public function permissions()
	{
		return $this->has_many('Feather\\Models\\Permission', 'place_id');
	}

	/**
	 * A place has many moderators.
	 * 
	 * @return object
	 */
	public function moderators()
	{
		return $this->has_many('Feather\\Models\\Place\\Moderator', 'place_id');
	}

	/**
	 * A place has many discussions.
	 * 
	 * @return object
	 */
	public function discussions()
	{
		return $this->has_many('Feather\\Models\\Discussion', 'place_id');
	}

}