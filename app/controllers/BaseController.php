<?php namespace Feather\Controllers;

use Controller;
use Illuminate\Support\Facade;

class BaseController extends Controller {

	/**
	 * Layout to be used by Feather.
	 * 
	 * @var Illuminate\View\View
	 */
	public $layout = 'theme';

	/**
	 * Illuminate application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	public $app;

	/**
	 * Controller constructor.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->app = Facade::getFacadeApplication();

		if ($this->layout)
		{
			$this->layout = $this->app['view']->make($this->layout);
		}
	}

	/**
	 * Call the given action with the given parameters.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	protected function directCallAction($method, $parameters)
	{
		// If no response is returned by the controller method then we'll fall back to
		// the layout that was bound in the constructor.
		if ( ! $response = call_user_func_array(array($this, $method), $parameters))
		{
			$response = $this->layout;
		}

		return $response;
	}

}