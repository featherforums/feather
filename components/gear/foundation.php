<?php namespace Feather\Components\Gear;

use Event;

abstract class Foundation {

	/**
	 * Listen for an event and fire a method or closure handler.
	 * 
	 * @param  string          $event
	 * @param  string|Closure  $handler
	 * @return void
	 */
	public function listen($event, $handler)
	{
		$this->event('listen', $event, $handler);
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
		$this->event('override', $event, $handler);
	}

	/**
	 * Binds different types of events.
	 * 
	 * @param  string          $type
	 * @param  string          $event
	 * @param  string|Closure  $handler
	 * @return void
	 */
	private function event($type, $event, $handler)
	{
		$gear = $this;

		Event::$type($event, function($parameters = array()) use ($gear, $handler)
		{
			// If the handler is a callable closure then we'll execute the closure
			// and return its result.
			if(is_callable($handler))
			{
				return $handler($parameters);
			}

			// If the handler is a method that exists on the gear object then execute
			// the method and return its result.
			elseif(method_exists($gear, $handler))
			{
				return call_user_func_array(array($gear, $handler), $parameters);
			}

			// By default we'll return null, as the event manager will expect a non
			// null result.
			return null;
		});

		Event::fire('event');
	}

	/**
	 * Executed when a gear is enabled.
	 * 
	 * @return void
	 */
	public function enabled(){}

	/**
	 * Executed when a gear is disabled.
	 * 
	 * @return void
	 */
	public function disabled(){}

	/**
	 * Executed when a gear is removed.
	 * 
	 * @return void
	 */
	public function remove(){}

}