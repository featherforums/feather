<?php namespace Feather\Extensions;

use FilesystemIterator;
use Illuminate\Container;
use Illuminate\Filesystem;
use Feather\Models\Extension as ExtensionModel;

class Dispatcher extends Container {

	/**
	 * Laravel application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Filesystem instance.
	 * 
	 * @var Illuminate\Filesystem
	 */
	protected $files;

	/**
	 * Path to extensions directory.
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * Started extensions.
	 * 
	 * @var array
	 */
	protected $started = array();

	/**
	 * Create a new dispatcher instance.
	 * 
	 * @param  Illuminate\Filesystem  $files
	 * @param  string  $path
	 * @return void
	 */
	public function __construct(Filesystem $files, $path)
	{
		$this->files = $files;
		$this->path = $path;
	}

	/**
	 * Set the Laravel application instance.
	 * 
	 * @param  Illuminate\Container  $app
	 * @return void
	 */
	public function setApplication(Container $app)
	{
		$this->app = $app;
	}

	/**
	 * Register an array of extensions with the dispatcher.
	 * 
	 * @param  array  $extensions
	 * @return void
	 */
	public function registerExtensions($extensions)
	{
		foreach ($extensions as $extension)
		{
			$this->register($extension);
		}
	}

	/**
	 * Register an extension with the dispatcher.
	 * 
	 * @param  Feather\Models\Extension  $extension
	 * @return void
	 */
	public function register(ExtensionModel $extension)
	{
		$path = $this->path.'/'.$extension->location;

		if ($this->files->exists($path))
		{
			$extension->path = $path;

			$extension->loaded = array();

			$this["extension.{$extension->identifier}"] = $extension;

			// If an extension is set to be automatically started then we'll hand it off to
			// the starting method.
			if ($extension->auto)
			{
				$this->start($extension->identifier);
			}
		}
	}

	/**
	 * Start an extension.
	 * 
	 * @param  string  $extension
	 * @return void
	 */
	public function start($extension)
	{
		if (in_array($extension, $this->started) or ! isset($this["extension.{$extension}"]))
		{
			return;
		}
		
		$extension = $this["extension.{$extension}"];

		foreach (new FilesystemIterator($extension->path) as $file)
		{
			$name = $file->getBasename(".{$file->getExtension()}");

			if (ends_with($name, 'Extension'))
			{
				$location = str_replace('/', '\\', $extension->location);

				$class = "Feather\\Extensions\\{$location}\\{$name}";

				// Instantiate the new extension class and assign it to the extensions loaded classes. The class
				// receives an instance of the Laravel application.
				$extension->loaded = array_merge($extension->loaded, array($class => new $class($this->app)));

				// If the extension has the start method we'll fire it, this is a great place for extensions
				// to do any bootstrapping or listening to events without having to override the constructor.
				if (method_exists($extension->loaded[$class], 'start'))
				{
					$extension->loaded[$class]->start($this->app);
				}
			}
		}
	}

	public function fire()
	{
		
	}

}