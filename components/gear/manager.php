<?php namespace Feather\Components\Gear;

use FilesystemIterator;
use Feather\Models\Gear;
use InvalidArgumentException;

class Manager {

	/**
	 * Registered Gears.
	 * 
	 * @var array
	 */
	protected $gears = array();

	/**
	 * Started Gears.
	 * 
	 * @var array
	 */
	protected $started = array();

	/**
	 * Register a Gear with the manager.
	 * 
	 * @param  Feather\Models\Gear  $gear
	 * @return bool
	 */
	public function register(Gear $gear)
	{
		if(!$gear instanceof Gear)
		{
			throw new InvalidArgumentException('Supplied Gear is a not supported type.');
		}		

		if(file_exists($path = path('gears') . $gear->location))
		{
			$this->gears[$gear->identifier] = $gear;

			// Start any gears that have been set to automatically start.
			if($gear->auto)
			{
				return $this->start($gear->identifier);
			}
		}

		return true;
	}

	/**
	 * Determines if a Gear has been registered.
	 * 
	 * @param  string  $gear
	 * @return bool
	 */
	public function registered($gear)
	{
		return isset($this->gears[$gear]);
	}

	/**
	 * Start a registered Gear.
	 * 
	 * @param  string  $gear
	 * @return bool
	 */
	public function start($gear)
	{
		if($this->started($gear))
		{
			return $this->started[$gear];
		}
		elseif(!$this->registered($gear))
		{
			return false;
		}

		$this->started[$gear] = new Container;

		// Spin through all the files within the gears directory and those that have
		// the .gear.php suffix we'll require and hopefully be able to instantiate
		// a new object.
		foreach(new FilesystemIterator(path('gears') . $this->gears[$gear]->location) as $file)
		{
			if(ends_with($file->getFilename(), '.gear.php'))
			{
				require $file->getPathname();

				$name = str_replace('.gear.php', null, $file->getFilename());

				$class = "\\Feather\\Gear\\{$this->gears[$gear]->identifier}\\{$name}";

				if(class_exists($class))
				{
					$this->started[$gear][$name] = new $class;
				}
			}
		}

		return $this->started[$gear];
	}

	/**
	 * Determines if a Gear has been started.
	 * 
	 * @param  string  $gear
	 * @return bool
	 */
	public function started($gear)
	{
		return isset($this->started[$gear]);
	}

	/**
	 * Gets a Gears container.
	 * 
	 * @param  string  $gear
	 * @return object
	 */
	public function get($gear)
	{
		if(!$this->started($gear) or !$this->registered($gear))
		{
			return false;
		}

		return $this->started[$gear];
	}

}