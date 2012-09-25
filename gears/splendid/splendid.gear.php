<?php namespace Feather\Gear\Splendid;

use Feather\Components\Gear\Foundation;

class Splendid extends Foundation {

	/**
	 * Register the event listeners for the Splendid gear.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->listen('assets: change scripts', 'scripting');

		$this->listen('assets: change styles', 'styling');
	}

	/**
	 * Adds the splendid.js file to the assets container.
	 * 
	 * @param  object  $container
	 * @return void
	 */
	public function scripting($container)
	{
		$container->add('splendid', '../../gears/splendid/js/splendid.js');
	}

	/**
	 * Adds the splendid.css file to the assets container.
	 * 
	 * @param  object  $container
	 * @return void
	 */
	public function styling($container)
	{
		$container->add('splendid', '../../gears/splendid/css/splendid.css');
	}

}