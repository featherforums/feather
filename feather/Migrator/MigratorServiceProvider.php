<?php namespace Feather\Migrator;

use Feather\Models\Migrator;
use Illuminate\Support\ServiceProvider;

class MigratorServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['feather.migrator'] = $this->app->share(function($app)
		{
			// Get the active migrator driver from the database. We'll then determine the connection to be used
			// for the migrator.
			$migrator = new Migrator;

			$migrator = $migrator->getMigratorDriver($app['config']->get('feather::migrator.driver'));

			// By default the database connection will be Feather's database connection. This is useful when you want
			// the original tables from the old system to be on the same database as Feather. Migrators can overwrite
			// this default connection.
			$connection = $app['db']->connection(FEATHER_DATABASE);

			if ( ! is_null($migrator->options->database))
			{	
				$app['config']->set('database.connections.feather:migrator', array(
					'driver'    => $migrator->options->driver,
					'host'      => $migrator->options->host,
					'database'  => $migrator->options->database,
					'username'  => $migrator->options->username,
					'password'  => $migrator->options->password,
					'charset'   => $migrator->options->charset,
					'prefix'    => $migrator->options->prefix,
					'collation' => $migrator->options->collation
				));

				$connection = $app['db']->connection('feather:migrator');
			}

			return new MigratorManager($app, $connection, ! is_null($migrator));
		});
	}

}