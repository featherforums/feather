<?php namespace Feather\Gear\Skeleton;

/**
 * All gears should extend the Gear Foundation component.
 */
use Feather\Components\Gear\Foundation;

class Bone extends Foundation {

	/**
	 * The constructor of a gear contains the events that it listens to. Events can execute
	 * closures or methods on the class.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		// Listen for an event and execute a callback when the event is fired by Feather.
		$this->listen('event', function()
		{
			// Depending on the event you may be required to return a value or null.
		});

		// Listen for an event and execute a method on the class.
		$this->listen('event', 'method');

		// Override existing events that are listening for the same event.
		$this->override('event', 'method');
	}

	/**
	 * This method will be executed when the event is fired by Feather.
	 * 
	 * @return void
	 */
	public function method(){}

}