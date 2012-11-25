<?php namespace Feather\Auth;

use Illuminate\Auth\Guard;
use Feather\Models\Migrator;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register($app)
	{
		$app['auth']->extend('feather', function($app)
		{
			$provider = new FeatherUserProvider($app['hash'], $app['feather']['migrator']);

			return new Guard($provider, $app['session']);
		});

		$app['config']->set('auth.driver', 'feather');
	}

}