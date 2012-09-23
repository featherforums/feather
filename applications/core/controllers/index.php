<?php

class Feather_Core_Index_Controller extends Feather_Base_Controller {

	/**
	 * Feather's Homepage. This overview page consists of a all the root level places
	 * plus some sub-places, a select number of discussions for each place is shown.
	 *
	 * @return void
	 */
	public function get_index()
	{
		$places = Feather\Core\Place::index($this->config->get('feather: db.overview.discussions_per_place'));

		$this->layout->nest('content', 'feather core::index.home', compact('places'));
	}

	/**
	 * Allows a user to enter their login details into a form and then become
	 * authenticated on the forums.
	 * 
	 * @return void
	 */
	public function get_login()
	{
		if($this->auth->online())
		{
			return $this->redirect->to_route('feather');
		}
		elseif($this->config->get('feather: db.auth.driver', 'internal') != 'internal')
		{
			return $this->redirect->before_login();
		}

		$this->breadcrumbs->drop(__('feather core::titles.login'));

		$this->layout->with('title', __('feather core::titles.login'))
					 ->nest('content', 'feather core::user.login');
	}

	/**
	 * Handles the posting of the login form and after validation, attempts to
	 * authenticate the user.
	 * 
	 * @return void
	 */
	public function post_login()
	{
		try
		{
			$this->validator->get('auth.login')->against(Input::get())->passes();
		}
		catch (FeatherValidationException $errors)
		{
			return $this->redirect->with_query('route: login')->with_input()->with_errors($errors->get());
		}

		if($this->auth->attempt(Input::get()))
		{
			return $this->redirect->to_previous('route: feather');
		}
			
		return $this->redirect->to_self()->with_input()->alert('error', 'feather core::login.failure');
	}

	/**
	 * Logs a user out of the forums and redirects them back to where they were, or depending
	 * on the authentication driver, a logout landing page.
	 * 
	 * @return void
	 */
	public function get_logout()
	{
		$this->auth->logout();

		// Return to the generated logout URL, this can change depending on the authentication
		// driver that is being used.
		return $this->redirect->after_logout();
	}

	/**
	 * If registrations are enabled a user can enter the required details into the registration
	 * form and register an account on the forums.
	 * 
	 * @return void
	 */
	public function get_register()
	{
		if($this->config->get('feather: db.auth.driver', 'internal') != 'internal')
		{
			return $this->redirect->before_register();
		}

		$this->breadcrumbs->drop(__('feather core::titles.register'));

		$this->layout->with('title', __('feather core::titles.register'))
					 ->nest('content', 'feather core::user.register');
	}

	/**
	 * Handles the posting of the registration form. Validates and then attempts to
	 * register a user with the details they supplied. If e-mail activation is enabled
	 * a user will also be sent an e-mail so they can activate their account.
	 * 
	 * @return void
	 */
	public function post_register()
	{
		try
		{
			$this->validator->get('auth.register')->against(Input::get())->passes();
		}
		catch (FeatherValidationException $errors)
		{
			return $this->redirect->to_self()->with_input()->with_errors($errors->get());
		}

		try
		{
			$user = Feather\Core\User::register(Input::get());
		}
		catch (FeatherModelException $errors)
		{
			return $this->redirect->to_self()->with_input()->alert('error', 'feather core::register.failure');
		}
		
		// Users must confirm their e-mail address once they have registered. We
		// will now send them an e-mail with their activation code.
		if($this->config->get('feather: db.registration.confirm_email'))
		{
			
		}

		$this->auth->login($user);

		return $this->redirect->to_previous('route: feather');
	}

	/**
	 * Simply shows the forums rules.
	 * 
	 * @return void
	 */
	public function get_rules()
	{
		$this->layout->with('title', __('feather core::titles.rules'))
					 ->nest('content', 'feather core::misc.rules');
	}

}