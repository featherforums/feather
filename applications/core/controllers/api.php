<?php

class Feather_API_Controller extends Feather_Base_Controller {
	
	/**
	 * API routes are RESTful
	 * 
	 * @var bool
	 */
	public $restful = true;

	/**
	 * API routes are not geared.
	 * 
	 * @var bool
	 */
	public $geared = false;

	/**
	 * Adjust the response header and content depending on the requested format.
	 * 
	 * @param  object  $response
	 * @return void
	 */
	public function after($response)
	{
		switch(File::extension(URI::current()))
		{
			case 'json':
				$response->header('content-type', 'application/json');

				$response->content = json_encode($response->content);
			break;
		}
	}
	
}