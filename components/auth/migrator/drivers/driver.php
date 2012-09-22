<?php namespace Feather\Components\Auth\Migrator\Drivers;

abstract class Driver {

	/**
	 * Driver object
	 *
	 * @var object
	 */
	protected static $driver;

	/**
	 * Array of driver extenders.
	 * 
	 * @var array
	 */
	public static $extenders = array();

	/**
	 * The drivers database connection.
	 * 
	 * @var Laravel\Database\Connection
	 */
	protected $connection;

	/**
	 * The feather instance.
	 * 
	 * @var Feather\Components\Foundation\Application
	 */
	protected $feather;

	/**
	 * Creates a new migration driver instance.
	 * 
	 * @param  Laravel\Database\Connection                $connection
	 * @param  Feather\Components\Foundation\Application  $feather
	 * @return void
	 */
	public function __construct(\Laravel\Database\Connection $connection, \Feather\Components\Foundation\Application $feather)
	{
		$this->connection = $connection;

		$this->feather = $feather;
	}

	/**
	 * Loads a migration driver.
	 * 
	 * @param  Feather\Components\Foundation\Application  $feather
	 * @param  Feather\Models\Migration                   $migration
	 * @return object
	 */
	public static function make(\Feather\Components\Foundation\Application $feather, \Feather\Models\Migration $migration)
	{
		$connection = DB::connection(FEATHER_DATABASE);

		if(!is_null($migration->database))
		{
			$feather['config']->set('laravel: database.connections.feather:migration', array(
				'driver'   => 'mysql',
				'host'     => $migration->host,
				'database' => $migration->database,
				'username' => $migration->username,
				'password' => $migration->password,
				'charset'  => 'utf8',
				'prefix'   => ''
			));

			$connection = DB::connection('feather:migration');
		}

		if(isset(static::$extenders[$migration->driver]))
		{
			$resolver = static::$extenders[$migration->driver];

			return static::$driver = $resolver($connection, $feather);
		}

		switch($migration->driver)
		{
			case 'flux':
				return static::$driver = new Flux($connection, $feather);
			break;
		}
	}

	/**
	 * Register a custom migration driver.
	 * 
	 * @param  string   $driver
	 * @param  Closure  $resolver
	 * @return void
	 */
	public static function extend($driver, $resolver)
	{
		static::$extenders[$driver] = $resolver;
	}

	/**
	 * Return the database connection for method chaining.
	 * 
	 * @return  Laravel\Database\Connection
	 */
	public function db()
	{
		return $this->connection;
	}

	/**
	 * Attempt to log a user in on the system.
	 * 
	 * @param  array  $credentials
	 * @return bool|object
	 */
	abstract public function login($credentials);

}