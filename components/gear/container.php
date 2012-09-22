<?php namespace Feather\Components\Gear;

use ArrayAccess;
use InvalidArgumentException;

class Container implements ArrayAccess {

	/**
	 * Gear services container.
	 * 
	 * @var array
	 */
	private $container = array();

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