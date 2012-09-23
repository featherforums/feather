<?php namespace Feather\Gear\Harmonize;

use Feather\Components\Gear\Foundation;

class Harmonize extends Foundation {

	/**
	 * Tell Harmonize to listen for its event to be fired so that it can run.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->listen('auth: bootstrap harmonize', 'bootstrap');
	}

	/**
	 * Bootstrap the Harmonize plugin. Run the users initializer.
	 * 
	 * @return mixed
	 */
	public function bootstrap()
	{
		$initializer = $this->feather['config']->get('gear: harmonize initialize');

		if($response = $initializer($this->feather))
		{
			return $response;
		}
	}

}