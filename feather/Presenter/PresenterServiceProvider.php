<?php namespace Feather\Presenter;

use Illuminate\View\Environment;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;

class PresenterServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @return void
	 */
	public function register()
	{
		$this->app['feather.presenter'] = $this->app->share(function($app)
		{
			return new Presenter($app);
		});
		
		$this->registerCompiler();
	}

	/**
	 * Register the view compiler.
	 * 
	 * @return void
	 */
	public function registerCompiler()
	{
		// Our compiler needs an instance of the file system and the storage path of the compiled views.
		$files = $this->app['files'];

		$storagePath = $this->app['path'].'/storage/views';

		$this->app['view']->addExtension('blade.php', 'feather.compiler', function() use ($files, $storagePath)
		{
			// FeatherCompiler is used by Feather is an extension to the Blade compiler. Feather
			// has a few special methods that are used throughout views that need to be compiled
			// alongside the default Blade methods.
			$compiler = new Compilers\FeatherCompiler($files, $storagePath);
			
			return new CompilerEngine($compiler, $files);
		});
	}

}