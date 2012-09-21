<?php namespace Feather\Components\Config;

use Str;
use Cache;
use Event;
use Config;

class Repository {

	/**
	 * Bootstrap the config repository, override the Laravel event for configuration
	 * file loading so that Gear and Theme items can be picked up.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		Event::override(Config::loader, function($bundle, $file)
		{
			if(str_contains($file, ':'))
			{
				list($item, $file) = explode(':', $file);

				// Configuration items that are colon separated are for Gears and Themes.
				// A requirement for these items is that they be prefixed with the name of
				// the Gear or Theme, followed by a space, followed by the configuration
				// item to fetch.
				list($name, $file) = explode(' ', $file);

				$path = path(Str::plural($item)) . $name . DS . 'config' . DS . $file . EXT;

				if(file_exists($path))
				{
					return require $path;
				}
			}

			return Config::file($bundle, $file);
		});
	}

	/**
	 * Load and cache the database configuration items.
	 * 
	 * @return void
	 */
	public function db()
	{
		Cache::forget('config');

		$items = Cache::sear('config', function()
		{
			$config = array();

			foreach(Models\Config::all() as $item)
			{
				array_set($config, $item->key, str_replace('(:feather)', '(:bundle)', $item->value));
			}

			return $config;
		});
	}

	/**
	 * Determine if a key exists.
	 * 
	 * @param  string  $key
	 * @return bool
	 */
	public function has($key)
	{
		return !is_null($this->get($key));
	}

	/**
	 * Set a config key to a given value.
	 * 
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function set($key, $value)
	{
		return Config::set($this->prefix($key), $value);
	}

	/**
	 * Get a key from the configuration.
	 * 
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return Config::get($this->prefix($key), $default);
	}

	/**
	 * Determine the prefix for a given key.
	 * 
	 * @param  string  $key
	 * @return string
	 */
	private function prefix($key)
	{
		if(!str_contains($key, ': '))
		{
			return $key;
		}

		list($prefix, $key) = explode(': ', $key);

		switch($prefix)
		{
			// The feather prefix is applied to both database and file-based
			// configuration items located within the bundle.
			case 'feather':
				return "feather::{$key}";
			break;

			// The gear prefix is applied to Feather gears, the gear
			// name should appear before the file and key to be used.
			case 'gear':
				list($gear, $key) = explode(' ', $key);

				return "feather::gear:{$gear} {$key}";
			break;
			default:
				return $key;
		}
	}

}