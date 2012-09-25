<?php namespace Feather\Core;

use Str;
use Cache;
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
	 * Array of cachable items.
	 * 
	 * @var array
	 */
	public static $cachable =  array(
		'place' => array('place', 'place_id'),
		'user'  => array('user', 'user_id'),
		'author' => array('user', 'user_id')
	);

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

	/**
	 * Find a model by its primary key.
	 *
	 * @param  string  $id
	 * @param  array   $columns
	 * @return Model
	 */
	public function _find($id, $columns = array('*'))
	{
		$key = explode('\\', get_called_class());
		$key = Str::lower(array_pop($key));

		if(isset(static::$cachable[$key]))
		{
			list($group, $foreign) = static::$cachable[$key];

			if(Cache::has("{$group}_{$id}"))
			{
				return Cache::get("{$group}_{$id}");
			}
		}

		return $this->query()->where(static::$key, '=', $id)->first($columns);
	}

	/**
	 * Handle the dynamic retrieval of attributes and associations.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if(isset(static::$cachable[$key]) and !isset($this->attributes[$key]) and !isset($this->relationships[$key]))
		{
			list($group, $foreign) = static::$cachable[$key];

			if(Cache::has("{$group}_{$this->$foreign}"))
			{
				return Cache::get("{$group}_{$this->$foreign}");
			}
		}

		// Of course if it's not in there we'll just continue on as per normal.
		return parent::__get($key);
	}

}