<?php namespace Feather\Models;

use Cache;
use Feather\Database\EloquentBuilder;
use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent {

	/**
	 * Cache time in minutes.
	 * 
	 * @var int
	 */
	protected $cacheTime = 720;

	/**
	 * Cachable items.
	 * 
	 * @var array
	 */
	protected $cachable = array(
		'place'  => array('place', 'place_id'),
		'user'   => array('user', 'user_id'),
		'author' => array('user', 'user_id')
	);

	/**
	 * Get a new query builder for the model's table.
	 *
	 * @return Feather\Models\Builder
	 */
	public function newQuery()
	{
		$builder = new EloquentBuilder($this->newBaseQueryBuilder());

		// Once we have the query builders, we will set the model instances so the
		// builder can easily access any information it may need from the model
		// while it is constructing and executing various queries against it.
		$builder->setModel($this);

		return $builder;
	}

	/**
	 * Handle the dynamic retrieval of attributes and associations.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if (isset($this->cachable[$key]) and ! isset($this->attributes[$key]) and ! isset($this->relations[$key]))
		{
			list($group, $foreign) = $this->cachable[$key];

			if (Cache::has("{$group}_{$this->$foreign}"))
			{
				return Cache::get("{$group}_{$this->$foreign}");
			}
		}

		return parent::__get($key);
	}

}