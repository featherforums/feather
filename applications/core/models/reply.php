<?php namespace Feather\Core;

class Reply extends Base {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'replies';

	/**
	 * Timestamps are enabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = true;

	/**
	 * A reply has an author.
	 * 
	 * @return object
	 */
	public function author()
	{
		return $this->has_one('Feather\\Core\\User', 'user_id');
	}

	/**
	 * A reply may have an editor.
	 * 
	 * @return object
	 */
	public function editor()
	{
		return $this->has_one('Feather\\Core\\User', 'edited_by');
	}

	/**
	 * A reply may have a deleter.
	 * 
	 * @return object
	 */
	public function deleter()
	{
		return $this->has_one('Feather\\Core\\User', 'deleted_by');
	}
}