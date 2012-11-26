<?php namespace Feather\Extensions;

use Illuminate\Support\ServiceProvider;

class ExtensionServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @return void
	 */
	public function register()
	{
		$this->app['feather.extensions'] = $this->app->share(function($app)
		{
			return new Dispatcher($app['files'], $app['path.extensions']);
		});

		$this->app['feather.extensions']->setApplication($this->app);
	}

}