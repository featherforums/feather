<?php namespace Feather;

class Config extends Components\Support\Facade {

	/**
	 * Gets the name of the facade component.
	 * 
	 * @return string
	 */
	protected static function accessor(){ return 'config'; }

}

class Auth extends Components\Support\Facade {

	/**
	 * Gets the name of the facade component.
	 * 
	 * @return string
	 */
	protected static function accessor(){ return 'auth'; }

}

class SSO extends Components\Support\Facade {

	/**
	 * Gets the name of the facade component.
	 * 
	 * @return string
	 */
	protected static function accessor(){ return 'sso'; }

}

class Gear extends Components\Support\Facade {

	/**
	 * Gets the name of the facade component.
	 * 
	 * @return string
	 */
	protected static function accessor(){ return 'gear'; }

}