<?php namespace Feather\Core;

class Gear extends Base {

	/**
	 * Name of table.
	 * 
	 * @var string
	 */
	public static $table = 'gears';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = false;

	/**
	 * Return all gears that have been enabled.
	 * 
	 * @return  array
	 */
	public static function enabled()
	{
		return static::where_enabled(1)->get();
	}

}