<?php namespace Feather\Gear\Recaptcha;

use URI;
use Input;
use View;
use Request;
use Validator;
use Autoloader;
use Feather\Components\Gear\Foundation;

class Recaptcha extends Foundation {

	/**
	 * Register the event listeners for the reCAPTCHA plugin.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->listen('view: before register.rules', 'display');

		$this->listen('assets: change styles', 'styling');

		$this->listen('validation: before auth.register', 'validation');
	}

	/**
	 * Display the reCAPTCHA view within the registration page.
	 * 
	 * @return string
	 */
	public function display()
	{
		return View::make('gear: recaptcha recaptcha');
	}

	/**
	 * Inject the reCAPTCHA styles into the themes asset container.
	 * 
	 * @param  object  $container
	 * @return void
	 */
	public function styling($container)
	{
		if(URI::is('register'))
		{
			$container->add('gear: recaptcha', '../../gears/recaptcha/css/recaptcha.css');
		}
	}

	/**
	 * Add the reCAPTCHA validation rules and messages to the registration validation.
	 * 
	 * @param  object  $validator
	 * @return void
	 */
	public function validation($validator)
	{
		$validator->rule('recaptcha_response_field', array('required', 'recaptcha'))
				  ->message('recaptcha_response_field.required', 'gear: recaptcha messages.is_required')
				  ->message('recaptcha_response_field.recaptcha', 'gear: recaptcha messages.is_incorrect');

		// Map to the Recaptcha library so we can register our validation rule with the validation.
		Autoloader::map(array(
			'Recaptcha\\Recaptcha' => __DIR__ . DS . 'classes' . DS . 'recaptcha.php'
		));

		$feather = $this->feather;

		Validator::register('recaptcha', function($attribute, $value, $parameters) use ($feather)
		{
			$private = $feather['config']->get('gear: recaptcha keys.private');

			$recaptcha = \Recaptcha\Recaptcha::recaptcha_check_answer($private, Request::ip(), Input::get('recaptcha_challenge_field'), $value);

			return $recaptcha->is_valid;
		});
	}

}