<?php namespace Feather\Components\Gear;

use Event;
use FilesystemIterator;
use Feather\Models\Gear;
use InvalidArgumentException;
use Feather\Components\Foundation\Component;

class Manager extends Component {

	/**
	 * Registered Gears.
	 * 
	 * @var array
	 */
	public $gears = array();

	/**
	 * Started Gears.
	 * 
	 * @var array
	 */
	public $started = array();

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

		if(starts_with($gear->location, 'path: '))
		{
			$path = substr($gear->location, 6);
		}
		else
		{
			$path = path('gears') . $gear->location;
		}

		if(file_exists($path))
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
		if(starts_with($this->gears[$gear]->location, 'path: '))
		{
			$path = substr($this->gears[$gear]->location, 6);
		}
		else
		{
			$path = path('gears') . $this->gears[$gear]->location;
		}

		foreach(new FilesystemIterator($path) as $file)
		{
			if(ends_with($file->getFilename(), '.gear.php'))
			{
				require_once $file->getPathname();

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
	 * Disable a gear for this run only.
	 * 
	 * @param  string  $gear
	 * @return void
	 */
	public function disable($gear)
	{
		if($this->registered($gear))
		{
			unset($this->gears[$gear]);
		}
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

	/**
	 * Fire an event and implodes the returned results.
	 * 
	 * @param  string  $event
	 * @param  array   $parameters
	 * @return string
	 */
	public function fire($event, $parameters = array())
	{
		return implode(PHP_EOL, array_filter(Event::fire($event, array($parameters))));
	}

	/**
	 * Fire the first event in the queue.
	 * 
	 * @param  string  $event
	 * @param  array   $parameters
	 * @return string
	 */
	public function first($event, $parameters = array())
	{
		return Event::first($event, $parameters);
	}

}