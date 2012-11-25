<?php namespace Feather\Models;

use Cache;

class Extension extends BaseModel {

	/**
	 * Name of table.
	 * 
	 * @var string
	 */
	public $table = 'extensions';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Return all activated extensions.
	 * 
	 * @return  array
	 */
	public function getActivated()
	{
		return Cache::rememberForever('extensions', function()
		{
			return Extension::where('activated', 1)->get();
		});
	}

}