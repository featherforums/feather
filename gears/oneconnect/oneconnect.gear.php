<?php namespace Feather\Gear\OneConnect;

use Request;
use Exception;
use Feather\Components\Gear\Foundation;

class OneConnect extends Foundation {

	/**
	 * The handler to use when fetching authentication data.
	 * 
	 * @var string
	 */
	protected $handler;

	/**
	 * Tell OneConnect to listen for its event to be fired so that it can run.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->listen('auth: bootstrap oneconnect', 'bootstrap');
	}

	/**
	 * Bootstrap the OneConnect plugin.
	 * 
	 * @return mixed
	 */
	public function bootstrap()
	{
		try 
		{
			$this->compatible();
		}
		catch (Exception $error)
		{
			return;
		}

		if($credentials = $this->{$this->handler}())
		{
			return $this->feather['sso']->authorize((array) $credentials);
		}
	}

	/**
	 * Fetch the authentication data using cURL.
	 * 
	 * @return mixed
	 */
	protected function curl()
	{
		$ch = curl_init($this->feather['config']->get('feather: db.auth.authenticate_url'));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, $this->cookies());

		$credentials = json_decode(curl_exec($ch));

		curl_close($ch);

		return $credentials;
	}

	/**
	 * Fetch the authentication data using file_get_contents().
	 * 
	 * @return mixed
	 */
	protected function file()
	{
		$opts = array(
			'http' => array(
				'method' => 'GET',
				'header' => 'Cookie: ' . $this->cookies()
			)
		);

		$context = stream_context_create($opts);

		$credentials = json_decode(file_get_contents($this->feather['config']->get('feather: db.auth.authenticate_url'), false, $context));

		return $credentials;
	}

	/**
	 * Returns a cookie string to be sent along with headers.
	 * 
	 * @return string
	 */
	protected function cookies()
	{
		$cookies = array();

		foreach(Request::foundation()->cookies->all() as $key => $value)
		{
			$cookies[] = "{$key}={$value}";
		}

		return implode('; ', $cookies);
	}

	/**
	 * OneConnect needs either cURL or file_get_contents to fetch the authentication data.
	 * When activating we make sure the plugin is compatible with the environment.
	 * 
	 * @return void
	 */
	protected function compatible()
	{
		if(!function_exists('curl_init'))
		{
			if(!function_exists('file_get_contents') or !ini_get('allow_url_fopen'))
			{
				throw new Exception('OneConnect is not compatible with your environment, cURL or allow_url_fopen is required.');
			}
			else
			{
				$this->handler = 'file';
			}
		}
		else
		{
			$this->handler = 'curl';
		}
	}

	/**
	 * Runs when the OneConnect plugin is activated.
	 * 
	 * @return bool
	 */
	public function activate()
	{
		try
		{
			$this->compatible();
		}
		catch (Exception $error)
		{
			throw $error;
		}
	}

}