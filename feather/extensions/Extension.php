<?php namespace Feather\Extensions;

use Illuminate\Container;

class Extension {

	/**
	 * Laravel application instance.
	 * 
	 * @var Illuminate\Container
	 */
	protected $app;

	/**
	 * Create a new extension instance.
	 * 
	 * @param  Illuminate\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;
	}

	/**
	 * Listen for an event and fire a method or closure handler.
	 * 
	 * @param  string          $event
	 * @param  string|Closure  $handler
	 * @return void
	 */
	public function listen($event, $handler)
	{
		$this->bindEvent('listen', $event, $handler);
	}

	/**
	 * Overrides existing events listening for an event and fire
	 * a method or closure handler.
	 * 
	 * @param  string          $event
	 * @param  string|Closure  $handler
	 * @return void
	 */
	public function override($event, $handler)
	{
		$this->bindEvent('override', $event, $handler);
	}

	/**
	 * Binds events for the extension.
	 * 
	 * @param  string          $type
	 * @param  string          $event
	 * @param  string|Closure  $handler
	 * @return void
	 */
	private function bindEvent($type, $event, $handler)
	{
		// Set the current extension instance so we can use it within the event closure.
		$extension = $this;

		$this->app['events']->$type($event, function($parameters = array()) use ($extension, $handler)
		{
			// If the handler is a callable closure then we'll execute the closure
			// and return its result.
			if (is_callable($handler))
			{
				return $handler($parameters);
			}

			// Finally assume that the handler is a method on the extension itself.
			return call_user_func_array(array($extension, $handler), $parameters);
		});
	}

}