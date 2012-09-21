<?php namespace Feather\Components\Config;

use DB;
use Str;
use Cache;
use Event;
use Config;

class Repository {

	/**
	 * Dirty configuration items.
	 * 
	 * @var array
	 */
	protected $dirty = array();

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

			foreach(DB::connection(FEATHER_DATABASE)->table('config')->get() as $item)
			{
				array_set($config, $item->key, str_replace('(:feather)', '(:bundle)', $item->value));
			}

			return $config;
		});

		foreach($items as $key => $item)
		{
			array_set(Config::$items['feather']['db'], $key, $item);
		}
	}

	/**
	 * Reload the database configuration items.
	 * 
	 * @return void
	 */
	public function reload()
	{
		Cache::forget('config');

		unset(Config::$items['feather']['db']);

		$this->db();
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
		// If the key does not exist and it belongs to the database configuration the item is
		// dirty and needs to be inserted.
		if(!$this->has($key) and starts_with($this->idenfitier($key), 'db'))
		{
			$this->dirty[] = substr($this->idenfitier($key), 3);
		}

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
	 * Save a key, group of keys, or all configuration items to the database.
	 * Only keys that belong to the database configuration can be saved.
	 * 
	 * @param  array  $keys
	 * @return void
	 */
	public function save($keys = array())
	{
		$items = array();

		if($keys)
		{
			// Each key must belong to the database set of configuration items to be saved
			// back into the database.
			foreach((array) $keys as $key)
			{
				if(starts_with($this->idenfitier($key), 'db'))
				{
					$items[substr($this->idenfitier($key), 3)] = $this->get($key);
				}
			}
		}
		else
		{
			$items = $this->get('feather: db');
		}

		$update = $insert = array();

		foreach($items as $key => $value)
		{
			// Using variable variables we can assign the item to the correct array
			// depending on whether or not the item already exists within the
			// database.
			$variable = in_array($key, $this->dirty) ? 'insert' : 'update';

			if(is_array($value))
			{
				foreach($value as $key => $value)
				{
					${$variable}[] = compact('key', 'value');
				}

				continue;
			}

			${$variable}[] = compact('key', 'value');
		}

		// Using PDO transactions we'll spin through our array of keys to update and execute
		// the query for each of them. If something goes wrong the transaction will be rolled
		// back automatically.
		if($update)
		{
			DB::connection(FEATHER_DATABASE)->transaction(function() use ($update)
			{
				foreach($update as $item)
				{
					DB::connection(FEATHER_DATABASE)->table('config')->where_key($item['key'])->update(array('value' => $item['value']));
				}
			});
		}

		// Because inserts behave differently to an update we can perform a batch insert with
		// Fluent by providing an array of arrays. We'll use this method instead of transactions.
		if($insert)
		{
			DB::connection(FEATHER_DATABASE)->table('config')->insert($insert);
		}
	}

	/**
	 * Delete a key or group of keys from the database.
	 * Only keys that belong to the database configuration can be deleted.
	 * 
	 * @param  array  $keys
	 * @return void
	 */
	public function delete($keys)
	{
		$delete = array();

		// Each key must belong to the database set of configuration items to be deleted
		// from the database.
		foreach((array) $keys as $key)
		{
			if(starts_with($this->idenfitier($key), 'db'))
			{
				$delete[] = substr($this->idenfitier($key), 3);
			}
		}

		if($delete)
		{
			DB::connection(FEATHER_DATABASE)->table('config')->where_in('key', $delete)->delete();
		}
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

	/**
	 * Determine the idenfitier excluding any namespacing.
	 * 
	 * @param  string  $key
	 * @return string
	 */
	private function idenfitier($key)
	{
		if(!str_contains($key, '::') and !str_contains($key = $this->prefix($key), '::'))
		{
			return null;
		}

		list($namespace, $idenfitier) = explode('::', $key);

		return $idenfitier;
	}

}