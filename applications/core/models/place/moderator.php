<?php namespace Feather\Core\Place;

use Feather\Core\Base;

class Moderator extends Base {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'place_moderators';

	/**
	 * A moderator has details, that is, their user account.
	 * 
	 * @return object
	 */
	public function details()
	{
		return $this->belongs_to('Feather\\Core\\User', 'user_id');
	}

	/**
	 * A moderator belongs to a place.
	 * 
	 * @return object
	 */
	public function place()
	{
		return $this->belongs_to('Feather\\Core\\Place', 'place_id');
	}

}