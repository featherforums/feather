<?php namespace Feather\Models;

class Migrator extends BaseModel {

	/**
	 * Name of table.
	 * 
	 * @var string
	 */
	public $table = 'migrators';

	/**
	 * Timestamps are disabled.
	 * 
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Get the activated migrator.
	 * 
	 * @param  string  $driver
	 * @return Feather\Models\Migrator
	 */
	public function getMigratorDriver($driver)
	{
		return $this->where('driver', $driver)->first();
	}

	public function getOptions($options)
	{
		return (object) json_decode($options);
	}

}