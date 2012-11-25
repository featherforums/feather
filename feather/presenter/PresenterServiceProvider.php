<?php namespace Feather\Presenter;

use Illuminate\View\Environment;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;

class PresenterServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register($app)
	{
		$app['feather.presenter'] = $app->share(function() use ($app)
		{
			return new Presenter($app);
		});
		
		$this->registerCompiler($app);
	}

	/**
	 * Register the view compiler.
	 * 
	 * @return void
	 */
	public function registerCompiler($app)
	{
		$app['view']->addExtension('blade.php', 'feather.compiler', function() use ($app)
		{
			// FeatherCompiler is used by Feather is an extension to the Blade compiler. Feather
			// has a few special methods that are used throughout views that need to be compiled
			// alongside the default Blade methods.
			$compiler = new Compilers\FeatherCompiler($app['files'], $app['path'].'/storage/views');
			
			return new CompilerEngine($compiler, $app['files']);
		});
	}

}