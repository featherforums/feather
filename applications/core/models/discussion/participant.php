<?php namespace Feather\Core\Discussion;

use Feather\Core\Base;

class Participant extends Base {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'discussion_participants';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = false;

	/**
	 * A participant has details, this is their user account.
	 * 
	 * @return object
	 */
	public function details()
	{
		return $this->belongs_to('Feather\\Core\\User', 'user_id');
	}

}