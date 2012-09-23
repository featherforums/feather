<?php

/*
|--------------------------------------------------------------------------
| Feather Validation Exception
|--------------------------------------------------------------------------
|
| The exception class for validation exceptions.
|
*/

class FeatherValidationException extends Exception {

	/**
	 * Array of errors.
	 * 
	 * @var array
	 */
	protected $errors;

	/**
	 * Create a FeatherValidateException instance.
	 * 
	 * @param  object  $errors
	 * @return void
	 */
	public function __construct($errors)
	{
		if(!$errors instanceof Laravel\Messages)
		{
			throw new Exception('Errors received were not part of a Messages object.');
		}

		$this->errors = $errors;
	}

	/**
	 * Get the errors.
	 * 
	 * @return array
	 */
	public function get()
	{
		return $this->errors;
	}

}

/*
|--------------------------------------------------------------------------
| Feather Model Exception
|--------------------------------------------------------------------------
|
| The exception class for Feather models.
|
*/

class FeatherModelException extends Exception {}

/*
|--------------------------------------------------------------------------
| Auth Exception
|--------------------------------------------------------------------------
|
| The exception class for Feather's Authentication.
|
*/

class AuthException extends Exception {}