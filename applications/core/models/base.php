<?php namespace Feather\Models;

use Event;
use Eloquent;
use Feather\Config;

class Base extends Eloquent {

	/**
	 * The default connection used by Feather.
	 * 
	 * @var string
	 */
	public static $connection = FEATHER_DATABASE;

	/**
	 * Cache time in minutes.
	 * 
	 * @var int
	 */
	const cache_time = 720;

	/**
	 * Log a query that can not or will not be logged by the normal query logger.
	 * 
	 * @param  string  $sql
	 * @param  int     $time
	 * @param  array   $bindings
	 * @return void
	 */
	protected static function log($sql, $time, $bindings = array())
	{
		if(Config::get('laravel: database.profile'))
		{
			Event::fire('laravel.query', array($sql, $bindings, number_format((microtime(true) - $time) * 1000, 2)));
		}
	}

}