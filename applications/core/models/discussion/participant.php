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

}