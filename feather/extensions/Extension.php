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

			// If the handler is a method that exists on the extension object then execute
			// the method and return its result.
			elseif (method_exists($extension, $handler))
			{
				return call_user_func_array(array($extension, $handler), $parameters);
			}

			// By default we'll return null, as the event dispatcher will expect a non
			// null result.
			return null;
		});
	}

	/**
	 * Executed when an extension is installed.
	 * 
	 * @return void
	 */
	public function installed(){}

	/**
	 * Executed when an extension is activated.
	 * 
	 * @return void
	 */
	public function activated(){}

	/**
	 * Executed when an extension is deactivated.
	 * 
	 * @return void
	 */
	public function deactivated(){}

	/**
	 * Executed when an extension is removed.
	 * 
	 * @return void
	 */
	public function removed(){}

}