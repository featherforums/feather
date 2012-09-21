<?php namespace Feather\Components\Feather;

use Closure;
use ArrayAccess;
use InvalidArgumentException;

class Application implements ArrayAccess {

	/**
	 * Applications container.
	 * 
	 * @var array
	 */
	private $container = array();

	/**
	 * Register a provider with the application.
	 * 
	 * @param  object  $provider
	 * @return void
	 */
	public function register($provider)
	{
		$provider->register($this);
	}

	/**
	 * Share a closure such that it is a singleton.
	 * 
	 * @param  Closure  $closure
	 * @return Closure
	 */
	public function share(Closure $closure)
	{
		return function($container) use ($closure)
		{
			// We'll declare a static object here, if the object is not defined
			// then we'll get execute the closure and return it.
			static $object;

			if(is_null($object))
			{
				$object = $closure($container);
			}

			return $object;
		};
	}

	/**
	 * Determine if an offset exists.
	 * 
	 * @param  string  $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return isset($this->container[$key]);
	}

	/**
	 * Get the value at a given offset.
	 * 
	 * @param  string  $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		if(!$this->offsetExists($key))
		{
			throw new InvalidArgumentException("Identifier {$key} is not in the container.");
		}

		return $this->container[$key]($this);
	}

	/**
	 * Set the value at a given offset.
	 * 
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		if(!$value instanceof Closure)
		{
			$value = function() use ($value)
			{
				return $value;
			};
		}

		$this->container[$key] = $value;
	}

	/**
	 * Unset a given offset.
	 * 
	 * @param  string  $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->container[$key]);
	}
	
}