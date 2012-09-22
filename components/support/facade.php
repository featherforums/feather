<?php namespace Feather\Components\Support;

use RuntimeException;

abstract class Facade {

	/**
	 * Feather instance being facaded.
	 * 
	 * @var Feather\Components\Feather\Application
	 */
	protected static $feather;

	/**
	 * Set or get the application facade instance.
	 * 
	 * @param  Feather\Components\Feather\Application  $app
	 * @return Feather\Components\Feather\Application
	 */
	public static function application($app = null)
	{
		if(is_null($app))
		{
			return static::$feather;
		}

		static::$feather = $app;
	}

	/**
	 * Gets the name of the facade component.
	 * 
	 * @return string
	 */
	protected static function accessor()
	{
		throw new RuntimeException('Accessor method on facade has not been implemented.');
	}

	/**
	 * Executes dynamic method calls on a facaded component.
	 * 
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		$name = static::accessor();

		$instance = is_object($name) ? $name : static::$feather[$name];

		return call_user_func_array(array($instance, $method), $parameters);
	}
	
}