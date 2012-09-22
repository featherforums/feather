<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Role Aliases
	|--------------------------------------------------------------------------
	|
	| Shorter names for some of the longer named roles. That is all.
	|
	*/

	'aliases' => array(
		'admin' => 'administrator'
	),

	/*
	|--------------------------------------------------------------------------
	| Action Rules
	|--------------------------------------------------------------------------
	|
	| Auth uses action rules when checking a user has permission to perform
	| a given action.
	|
	| For example, a user may delete users but may not delete themselves. To
	| do this we create a rule for that verb and action.
	|
	| Rules a placed within the verb for an action. The verbs are: 'access',
	| 'use', 'start', 'edit', 'delete', 'approve', and 'moderate'.
	|
	*/

	'rules' => array(

		/*
		|--------------------------------------------------------------------------
		| Delete Verb
		|--------------------------------------------------------------------------
		*/

		'delete' => array(

			/*
			|--------------------------------------------------------------------------
			| Deleting Users
			|--------------------------------------------------------------------------
			|
			| Users that can delete other users are not able to delete themselves.
			|
			*/

			'users' => function($deleting)
			{
				return ($deleting->id != Feather\Auth::user()->id);
			}
		),

		/*
		|--------------------------------------------------------------------------
		| Edit Verb
		|--------------------------------------------------------------------------
		*/

		'edit' => array(

			/*
			|--------------------------------------------------------------------------
			| Editing Discussion
			|--------------------------------------------------------------------------
			|
			| Users can edit their own discussions, unless they are Administrator or
			| Moderator, they can edit all.
			|
			*/

			'discussion' => function($discussion)
			{
				$user = Feather\Auth::user();

				if(Feather\Auth::is('admin'))
				{
					return true;
				}
				elseif(Feather\Auth::is('moderator'))
				{
					return true;		
				}

				// The easy checks have been done, now to see if the user is a place specific
				// moderator on the discussions place.
				if($discussion->place->moderators)
				{
					foreach($discussion->place->moderators as $moderator)
					{
						if($user->id == $moderator->user_id)
						{
							return true;
						}
					}
				}

				// Lastly if this discussion belongs to this user and they have permission to
				// edit their own discussions we'll allow them to edit this discussion.
				if($user->id == $discussion->user_id and Feather\Auth::can('edit: own discussions'))
				{
					return true;
				}

				return false;
			}

		),

		/*
		|--------------------------------------------------------------------------
		| View Verb
		|--------------------------------------------------------------------------
		*/

		'view' => array(

			/*
			|--------------------------------------------------------------------------
			| Viewing Discussion
			|--------------------------------------------------------------------------
			|
			| Users can view all discussions unless it is either private or a draft.
			|
			*/

			'discussion' => function($discussion)
			{
				$user = Feather\Auth::user();

				// Private discussions can only be viewed by participants, the original
				// poster, and administrators/moderators.
				if($discussion->private)
				{
					if($discussion->user_id == $user->id or Feather\Auth::is(array('admin', 'moderator')))
					{
						return true;
					}

					foreach($discussion->participants as $participant)
					{
						if(Feather\Auth::online() and $participant->user_id == $user->id)
						{
							return true;
						}
					}

					return false;
				}

				// Draft discussions can only be viewed by the original author and
				// administrators/moderators.
				elseif($discussion->draft)
				{
					if($discussion->user_id == $user->id or Feather\Auth::is(array('admin', 'moderator')))
					{
						return true;
					}

					return false;
				}

				return true;
			}

		)
	)

);