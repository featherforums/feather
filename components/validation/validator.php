<?php namespace Feather\Components\Validation;

use DB;
use Laravel\Messages;
use InvalidArgumentException;
use FeatherValidationException;
use Feather\Components\Foundation\Component;

class Validator extends Component {

	/**
	 * Application to load from.
	 * 
	 * @var string
	 */
	public $application = 'core';

	/**
	 * The items currently validating.
	 * 
	 * @var array
	 */
	public $validating;

	/**
	 * Input to validate against.
	 * 
	 * @var array
	 */
	public $input = array();

	/**
	 * Rules to use for validation.
	 * 
	 * @var array
	 */
	public $rules = array();

	/**
	 * Error messages to use for validation.
	 * 
	 * @var array
	 */
	public $messages = array();

	/**
	 * Data to be used when setting rules.
	 * 
	 * @var array
	 */
	public $data = array();

	/**
	 * Sets the application.
	 * 
	 * @param  string  $application
	 * @return Feather\Components\Support\Validation
	 */
	public function app($application)
	{
		$this->application = $application;

		return $this;
	}

	/**
	 * Get a validator.
	 * 
	 * @param  string  $validator
	 * @return Feather\Components\Support\Validation
	 */
	public function get($validator)
	{
		$segments = explode('.', $validator);

		if(file_exists($path = path('applications') . $this->application . DS . 'validation' . DS . $segments[0] . EXT))
		{
			if($this->validating[$validator] = array_get(require $path, implode('.', array_slice($segments, 1)) ?: null))
			{
				return $this;
			}
		}

		if($this->validating[$validator] = $this->feather['config']->get("feather {$this->application}: validation.{$validator}"))
		{
			return $this;
		}

		throw new InvalidArgumentException("Invalid validator [{$validator}] supplied.");
	}

	/**
	 * Sets input to validate against.
	 * 
	 * @param  array  $input
	 * @return Feather\Components\Support\Validation
	 */
	public function against($input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Executes validation and returns true on success.
	 * 
	 * @return bool
	 */
	public function passes()
	{		
		// If a response is returned from the closure then we have a custom error messages
		// to throw with the exception. 
		if($responses = call_user_func_array(reset($this->validating), array($this)))
		{
			if(!is_array(reset($responses))) $responses = array(array_shift($responses) => array());

			foreach($responses as $response => $replacements)
			{
				$responses[$response] = __("{$this->application}::{$response}", $replacements)->get();
			}

			throw new FeatherValidationException(new Messages($responses));
		}

		$event = key($this->validating);

		$this->feather['gear']->fire("validation: before {$event}", array($this));

		$validator = new \Validator($this->input, $this->rules, $this->messages);

		if($validator->connection(DB::connection(FEATHER_DATABASE))->fails())
		{
			throw new FeatherValidationException($validator->errors);
		}

		return true;
	}

	/**
	 * Adds a rule or array of rules to the rules array.
	 * 
	 * @param  string        $name
	 * @param  array|string  $rule
	 * @return Feather\Components\Support\Validation
	 */
	public function rule($name, $rule)
	{
		if(!isset($this->rules[$name]))
		{
			$this->rules[$name] = array();
		}

		$this->rules[$name] = array_merge($this->rules[$name], (array) $rule);

		return $this;
	}

	/**
	 * Adds a message to the messages array.
	 * 
	 * @param  string        $rule
	 * @param  array|string  $message
	 * @return Feather\Components\Support\Validation
	 */
	public function message($rule, $message)
	{
		$replacements = array();

		if(is_array($message))
		{
			list($message, $replacements) = $message;
		}

		$this->messages[str_replace('.', '_', $rule)] = __("{$this->application}::{$message}", $replacements)->get();

		return $this;
	}

	/**
	 * Adds a data key/value pair to the data array.
	 * 
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return Feather\Components\Support\Validation
	 */
	public function with($key, $value)
	{
		$this->data[$key] = $value;

		return $this;
	}

}