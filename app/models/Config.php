<?php namespace Feather\Models;

use Cache;

class Config extends BaseModel {

	/**
	 * Name of table.
	 * 
	 * @var string
	 */
	public $table = 'config';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Load all config items.
	 * 
	 * @return array
	 */
	public function everything()
	{
		return Cache::rememberForever('config', function()
		{
			return Config::all();
		});
	}

}