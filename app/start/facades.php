<?php namespace Feather;

use Illuminate\Support\Facade;

class Extension extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'feather.extensions'; }

}

class Presenter extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'feather.presenter'; }

}