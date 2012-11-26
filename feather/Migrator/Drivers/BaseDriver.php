<?php namespace Feather\Migrator\Drivers;

use DB;
use Feather\Models\Migrator;

class BaseDriver {

	/**
	 * Illuminate application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Migrator database record.
	 * 
	 * @var Feather\Models\Migrator
	 */
	protected $migratorRecord;

	/**
	 * Create a new base driver instance.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct($app, $connection)
	{
		$this->app = $app;
	}

	/**
	 * Set the migrator database record.
	 * 
	 * @param  Feather\Models\Migrator  $migrator
	 */
	public function setMigratorRecord(Migrator $migrator)
	{
		$this->migratorRecord = $migrator;

		return $this;
	}

	/**
	 * Determine if the driver has a migrator database record.
	 * 
	 * @return bool
	 */
	public function hasMigratorRecord()
	{
		return ! is_null($this->migratorRecord);
	}

}