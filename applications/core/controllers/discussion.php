<?php

class Feather_Core_Discussion_Controller extends Feather_Base_Controller {

	/**
	 * Register the filters for all discussion actions.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->filter('before', 'csrf')->on(array('post', 'put'))->except(array('preview'));
	}

	/**
	 * Read a discussion.
	 * 
	 * @return object
	 */
	public function get_index($id, $slug)
	{
		// Make sure that the user has the correct permissions to view this discussion.
		if($discussion = $this->discussion($id) and $this->auth->can('view: discussion', $discussion))
		{
			// If the discussion is a draft, they cannot view it. Take them straight to the edit page.
			if($discussion->draft)
			{
				return $this->redirect->to_route('discussion.edit', array($discussion->id, $discussion->slug));
			}

			$author= $discussion->author;

			$this->breadcrumbs->drop($discussion->place)->drop($discussion->title);

			$this->layout->nest('content', 'feather core::discussion.view', compact('discussion'))
						 ->with('title', $discussion->title);
		}

		// User cannot view this discussion so the discussion is not found.
		else
		{
			$this->breadcrumbs->drop(__('feather core::discussion.discussion_not_found.title'));

			$error = array(
				'title' => __('feather core::discussion.discussion_not_found.title'),
				'error' => __('feather core::discussion.discussion_not_found.message'),
				'type'	=> 'error'
			);

			$this->layout->nest('content', 'feather core::error.page', compact('error'))
						 ->with('title', __('feather core::discussion.discussion_not_found.title'));
		}
	}

	/**
	 * Allows a new discussion to be started on a given place. If no place is provided the
	 * user can select a place.
	 * 
	 * @param  int  $place
	 * @return void
	 */
	public function get_start($place = null)
	{
		$places = Feather\Core\Place::options(array(
			'selected'	  => $place,
			'permissions' => true,
			'action'	  => 'start: discussions',
			'cascade'	  => false
		));

		$place = $this->place($place);

		// Make sure the user has the correct permissions to start a discussion and that there are
		// places to start a discussion on.
		if($this->auth->can('start: discussions', $place) and $places)
		{
			$this->breadcrumbs->drop(__('feather core::titles.start_discussion'));

			$preview = (Input::had('preview') and Input::had('body')) ? 
					   View::make('feather core::discussion.preview')->with('body', Feather\Gear\Markdown\Parse(Input::old('body'))) : 
					   null;

			$this->layout->nest('content', 'feather core::discussion.start', compact('places', 'preview'))
						 ->with('title', __('feather core::titles.start_discussion'));
		}
		else
		{
			// The user cannot start a discussion on this place in particular.
			if($this->auth->cannot('start: discussions', $place))
			{
				$this->breadcrumbs->drop(__('feather core::discussion.cannot_start_discussion.title'));

				$error = array(
					'title' => __('feather core::discussion.cannot_start_discussion.title'),
					'error' => __('feather core::discussion.cannot_start_discussion.message'),
					'type'	=> 'error'
				);

				$this->layout->with('title', __('feather core::discussion.cannot_start_discussion.title'));
			}

			// The user cannot start a discussion on any available places.
			else
			{
				$this->breadcrumbs->drop(__('feather core::discussion.no_places_for_discussion.title'));

				$error = array(
					'title' => __('feather core::discussion.no_places_for_discussion.title'),
					'error' => __('feather core::discussion.no_places_for_discussion.message'),
					'type'	=> 'error'
				);

				$this->layout->with('title', __('feather core::discussion.no_places_for_discussion.title'));
			}

			$this->layout->nest('content', 'feather core::error.page', compact('error'));
		}
	}

	/**
	 * Attempts to start a new discussion on the place provided.
	 * 
	 * @param  int  $place
	 * @return void
	 */
	public function post_start($place = null)
	{
		if(Input::has('preview'))
		{
			return $this->redirect->to_self()->with_input();
		}

		if($this->auth->cannot('start: discussions', $this->place(Input::get('place'))))
		{
			return $this->redirect->to_self()
								  ->with_input()
								  ->with_alert('error', 'feather core::discussion.cannot_start_discussion.message');
		}

		Input::merge(array('user' => $this->auth->user->id));

		try
		{
			$this->validator->get('discussion.start')->against(Input::get())->passes();
		}
		catch (FeatherValidationException $errors)
		{
			return $this->redirect->to_self()
								  ->with_input()
								  ->with_errors($errors->get());
		}

		try
		{
			$discussion = Feather\Core\Discussion::start(Input::get());
		}
		catch (FeatherModelException $errors)
		{
			return $this->redirect->to_self()
								  ->with_input()
								  ->with_alert('error', 'feather core::discussion.cannot_start_discussion.message');
		}

		if(Input::has('draft'))
		{
			return $this->redirect->to_route('discussion.edit', array($discussion->id, $discussion->slug))
								  ->with_alert('success', 'feather core::discussion.draft_saved');
		}
		else
		{
			return $this->redirect->to_route('discussion', array($discussion->id, $discussion->slug))
								  ->with_alert('success', 'feather core::discussion.started');
		}
	}

	/**
	 * Allows users to edit a discussion if it belongs to them, or if they are a moderator or
	 * administrator.
	 * 
	 * @param  int     $id
	 * @param  string  $slug
	 * @return void
	 */
	public function get_edit($id, $slug)
	{
		// Make sure the user has the correct permissions to edit this discussion.
		if($discussion = $this->discussion($id) and $this->auth->can('edit: discussion', $discussion))
		{
			$this->breadcrumbs->drop($discussion->place)->drop(__('feather core::titles.edit_discussion', array('discussion' => $discussion->title)));

			$participants = $discussion->participants_to_string();

			$places = Feather\Core\Place::options(array(
				'selected'	  => $discussion->place_id,
				'permissions' => true,
				'action'	  => 'start: discussions',
				'cascade'	  => false
			));

			$preview = (Input::had('preview') and Input::had('body')) ? 
						   View::make('feather core::discussion.preview')->with('body', Feather\Gear\Markdown\Parse(Input::old('body'))) : 
						   null;
			
			$this->layout->nest('content', 'feather core::discussion.edit', compact('discussion', 'places', 'participants', 'preview'))
						 ->with('title', __('feather core::titles.edit_discussion', array('discussion' => $discussion->title)));
		}
		else
		{
			// The discussion the user is attempting to edit could not be found.
			if(!$discussion)
			{
				$this->breadcrumbs->drop(__('feather core::discussion.discussion_not_found.title'));

				$error = array(
					'title' => __('feather core::discussion.discussion_not_found.title'),
					'error' => __('feather core::discussion.discussion_not_found.message'),
					'type'	=> 'error'
				);

				$this->layout->with('title', __('feather core::discussion.discussion_not_found.title'));
			}

			// The user does not have the permission to edit this discussion.
			else
			{
				$this->breadcrumbs->drop(__('feather core::discussion.cannot_edit_discussion.title'));

				$error = array(
					'title' => __('feather core::discussion.cannot_edit_discussion.title'),
					'error' => __('feather core::discussion.cannot_edit_discussion.message'),
					'type'	=> 'error'
				);

				$this->layout->with('title', __('feather core::discussion.cannot_edit_discussion.title'));
			}

			$this->layout->nest('content', 'feather core::error.page', compact('error'));
		}
	}

	/**
	 * Attempts to edit a discussion.
	 * 
	 * @param  int     $id
	 * @param  string  $slug
	 * @return void
	 */
	public function put_edit($id, $slug)
	{
		if(Input::has('preview'))
		{
			return $this->redirect->to_self()->with_input();
		}

		if(!$discussion = $this->discussion($id))
		{
			return $this->redirect->to_route('feather')
								  ->with_alert('error', 'feather core::discussion.invalid');
		}

		if($this->auth->cannot('start: discussions', $this->place(Input::get('place'))))
		{
			return $this->redirect->to_self()
								  ->with_input()
								  ->with_alert('error', 'feather core::discussion.cannot_start_discussion.message');
		}

		Input::merge(array('user' => $this->auth->user->id));

		try
		{
			$this->validator->get('discussion.edit')->against(Input::get())->passes();
		}
		catch (FeatherValidationException $errors)
		{
			return $this->redirect->to_self()
								  ->with_input()
								  ->with_errors($errors->get());
		}

		try
		{
			$discussion = Feather\Core\Discussion::edit($discussion, Input::get());
		}
		catch (FeatherModelException $errors)
		{
			return $this->redirect->to_self()
								  ->with_input()
								  ->with_alert('error', 'feather core::discussion.edit_failure.message');
		}

		if(Input::has('draft'))
		{
			return $this->redirect->to_route('discussion.edit', array($discussion->id, $discussion->slug))
								  ->with_alert('success', 'feather core::discussion.draft_saved');
		}
		else
		{
			$redirect = $this->redirect->to_route('discussion', array($discussion->id, $discussion->slug));

			if(Input::has('save'))
			{
				$redirect->with_alert('success', 'feather core::discussion.saved');
			}
			else
			{
				$redirect->with_alert('success', 'feather core::discussion.started');
			}

			return $redirect;
		}
	}

}