<?php namespace Feather\Components\Support;

use URL;
use URI;
use Input;
use Request;
use Redirect;
use Feather\Auth;
use Feather\Config;
use Laravel\Messages;

class Redirector extends Redirect {

	/**
	 * Add an alert to the session flash data.
	 * 
	 * @param  string  $type
	 * @param  string  $key
	 * @param  array   $replacements
	 * @return object
	 */
	public function with_alert($type, $key, $replacements = array())
	{
		return $this->with('alert', array('type' => $type, 'message' => __($key, $replacements)));
	}

	/**
	 * Create a redirect response with the query string appended.
	 * 
	 * @param  string  $url
	 * @return object
	 */
	public function with_query($url)
	{
		$url = URL::to($this->pattern($url));

		if($query = urldecode(Request::getQueryString()))
		{
			$url = "{$url}?{$query}";
		}

		return $this->to($url);
	}

	/**
	 * Create a redirect response to the current page.
	 *
	 * @return object
	 */
	public function to_self()
	{
		return $this->with_query(URI::current());
	}

	/**
	 * Create a redirect response to the previous page.
	 * 
	 * @param  string  $default
	 * @return object
	 */
	public function to_previous($default = null)
	{
		return $this->to(Input::has('return') ? Input::get('return') : $this->pattern($default));
	}

	/**
	 * Create a redirect response after a logout.
	 * 
	 * @return object
	 */
	public static function after_logout()
	{
		return $this->for_auth(Config::get('feather: auth.logout_url'));
	}

	/**
	 * Create a redirect response before a registration.
	 * 
	 * @return object
	 */
	public static function before_register()
	{
		return $this->for_auth(Config::get('feather: auth.register_url'));
	}

	/**
	 * Create a redirect response before a login.
	 * 
	 * @return object
	 */
	public static function before_login()
	{
		return $this->for_auth(Config::get('feather: auth.login_url'));
	}

	/**
	 * Create a redirect response to an authentication page.
	 * 
	 * @param  string  $url
	 * @return object
	 */
	protected function for_auth($url)
	{
		$replace = array(
			'{feather}' => Bundle::option('feather', 'handles'),
			'{current}' => URL::to(Input::has('return') ? Input::get('return') : URI::current()),
			'{token}'	=> Auth::online() ? Auth::user()->authenticator_token : null
		);

		if(Config::get('feather: auth.driver') == 'internal')
		{
			$url = URL::to_route('feather');
		}
		else
		{
			$url = str_replace(array_keys($replace), array_values($replace), $url);
		}

		return $this->to($url);	
	}

	/**
	 * Builds a URL based on defined patterns.
	 * 
	 * @param  string  $url
	 * @return string
	 */
	protected function pattern($url)
	{
		if(starts_with($url, 'route: '))
		{
			return URL::to_route(substr($url, 7));
		}
		elseif(starts_with($url, 'action: '))
		{
			return URL::to_action(substr($url, 8));
		}

		return $url;
	}

	/**
	 * Flash a Validator's errors to the session data.
	 *
	 * @param  object|string  $container
	 * @return object
	 */
	public function with_errors($container)
	{
		$errors = !($container instanceof Messages) ? new Messages(array($container)) : $container;

		return $this->with('errors', $errors);
	}

}