<?php

class Feather_Base_Controller extends Controller {

	/**
	 * Controllers are RESTful.
	 * 
	 * @var bool
	 */
	public $restful = true;

	/**
	 * Controllers can be geared.
	 * 
	 * @var bool
	 */
	public $geared = true;

	/**
	 * The default layout to be used by Feather.
	 * 
	 * @var string
	 */
	public $layout = 'feather core::template';

	/**
	 * The feather instance.
	 * 
	 * @var Feather\Components\Foundation\Application
	 */
	public $feather;

	/**
	 * Create a new Controller instance.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->feather = Feather\Components\Support\Facade::application();
	}

	/**
	 * Determines if a place is valid, if it is return the place.
	 * 
	 * @param  int  $id
	 * @return Feather\Models\Place
	 */
	protected function place($id)
	{
		return is_numeric($id) ? Feather\Models\Place::with('permissions')->find($id) : null;
	}

	/**
	 * Determines if a discussion is valid, if it is return the discussion.
	 * 
	 * @param  int  $id
	 * @return Feather\Models\Discussion
	 */
	protected function discussion($id)
	{
		return is_numeric($id) ? Feather\Models\Discussion::with('participants')->find($id) : null;
	}

	/**
	 * Method to be run before each request.
	 * 
	 * @return void
	 */
	public function before()
	{
		if($this->geared)
		{
			$this->feather['gear']->controller('before', $this);
		}
	}

	/**
	 * Method to be run after each request.
	 *
	 * @return void
	 */
	public function after($response)
	{
		if($this->geared)
		{
			$this->feather['gear']->controller('after', $this);
		}

		$this->feather['gear']->fire('assets: change styles', array(Asset::container('theme')));

		$this->feather['gear']->fire('assets: change scripts', array(Asset::container('theme')));
	}

	/**
	 * Overload the execute method on the controller. If the controller is geared we'll
	 * run the first override gear and return the result accordingly.
	 * 
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return string|object
	 */
	public function execute($method, $parameters = array())
	{
		$response = $this->geared ? $this->feather['gear']->controller('override', $this) : null;

		// Only if the response is not null and we actually fired a gear event successfully.
		if(!is_null($response) and $response)
		{
			$response = is_string($response) ? $response : $this->layout;

			return $response;
		}

		return parent::execute($method, $parameters);
	}

	/**
	 * Magic method for calling Feather components.
	 * 
	 * @param  string  $component
	 * @return object
	 */
	public function __get($component)
	{
		if(isset($this->feather[$component]))
		{
			return $tihs->feather[$component];
		}

		throw new BadMethodCallException('Invalid component [{$component}] called on controller.');
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		$response = $this->geared ? $this->feather['gear']->controller('create', $this) : null;
		
		if(is_null($response) or !$response)
		{
			return Response::error('404');
		}
	}

}