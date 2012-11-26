<?php namespace Feather\Migrator;

use Illuminate\Support\Manager;

class MigratorManager extends Manager {

	/**
	 * Database connection instance.
	 * 
	 * @var Illuminate\Database\Connection
	 */
	protected $connection;

	/**
	 * Migrator is active.
	 * 
	 * @var bool
	 */
	protected $active;

	/**
	 * Create a new manager instance.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @param  Illuminate\Database\Connection  $connection
	 * @param  bool  $active
	 * @return void
	 */
	public function __construct($app, $connection, $active = true)
	{
		$this->app = $app;
		$this->connection = $connection;
		$this->active = $active;
	}

	/**
	 * Get the default migrator driver name.
	 *
	 * @return string
	 */
	protected function getDefaultDriver()
	{
		return $this->app['config']->get('feather::migrator.driver');
	}

	/**
	 * Call a custom driver creator.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function callCustomCreator($driver)
	{
		return $this->customCreators[$driver]($this->app, $this->connection);
	}

	/**
	 * Create a new flux driver instance.
	 * 
	 * @return Feather\Migrator\Drivers\FluxDriver
	 */
	protected function createFluxDriver()
	{
		return new Drivers\FluxDriver($this->app);
	}

	/**
	 * Determine if the migrator is active.
	 * 
	 * @return bool
	 */
	public function isActive()
	{
		return $this->active;
	}

}