<?php namespace Feather\Presenter;

class Presenter {

	/**
	 * Illuminate application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Create a new theme instance.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Prepare the theme by requiring a starter file if it exists.
	 * 
	 * @return void
	 */
	public function prepare()
	{
		// If we are within theme development mode and not running within the console then we'll publish the current themes
		// assets. This is especially handy when you're making a lot of changes to your themes assets.
		if ($this->app['config']->get('feather.forum.theme_development_mode') and ! $this->app->runningInConsole())
		{
			$this->app['artisan']->call('feather:publish', array('name' => $this->app['config']->get('feather::forum.theme'), '--theme' => true));
		}

		// Assign a namespace and some cascading paths so that view files are first searched
		// for within a theme then within the core view directory.
		$this->app['view']->addLocation($this->app['path.themes'].'/'.$this->app['config']->get('feather.forum.theme').'/views');
		$this->app['view']->addLocation($this->app['path'].'/views');

		// If the theme has a start file require the file to bootstrap the theme.
		$start = $this->app['path.themes'].'/'.$this->app['config']->get('feather.forum.theme').'/start.php';

		if ($this->app['files']->exists($start))
		{
			// We'll make $app available to our start scripts so that they can access bound data and components.
			$app = $this->app;

			require $start;
		}
	}

}