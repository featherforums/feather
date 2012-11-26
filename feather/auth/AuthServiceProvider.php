<?php namespace Feather\Auth;

use Illuminate\Auth\Guard;
use Feather\Models\Migrator;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @return void
	 */
	public function register()
	{
		$this->app['auth']->extend('feather', function($app)
		{
			$provider = new FeatherUserProvider($app['hash'], $app['feather']['migrator']);

			return new Guard($provider, $app['session']);
		});

		$this->app['config']->set('auth.driver', 'feather');
	}

}