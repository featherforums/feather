<?php

/*
|--------------------------------------------------------------------------
| Discussion Language Strings
|--------------------------------------------------------------------------
|
| English language rules for discussion related tasks.
|
*/

return array(

	/*
	|--------------------------------------------------------------------------
	| Discussion Not Found
	|--------------------------------------------------------------------------
	|
	| English language message for when a discussion cannot be found.
	|
	*/

	'discussion_not_found' => array(
		'title' => 'Discussion Not Found',
		'message' => '<strong>Oh shoot!</strong> We were unable to locate the discussion you have request. This may be due to a number of reasons, including the discussion being removed or incorrect permissions.'
	),

	/*
	|--------------------------------------------------------------------------
	| Cannot Edit Discussion
	|--------------------------------------------------------------------------
	|
	| English language message for when a discussion cannot be found.
	|
	*/

	'cannot_edit_discussion' => array(
		'title' => 'Cannot Edit Discussion',
		'message' => '<strong>Hold up there!</strong> You do not have the correct permissions to be editing this discussion.'
	),

	/*
	|--------------------------------------------------------------------------
	| No Places For Discussions
	|--------------------------------------------------------------------------
	|
	| English language message for when their are no places available to start
	| a new discussion.
	|
	*/

	'no_places_for_discussion' => array(
		'title' => 'Cannot Start Discussion',
		'message' => '<strong>Uh-oh!</strong> You are current unable to start a new discussion because there appear to be no places you can start your discussion. Please try starting a new discussion when there are possible places.'
	),

	/*
	|--------------------------------------------------------------------------
	| Cannot Start Discussion
	|--------------------------------------------------------------------------
	|
	| English language message for when user has incorrect permissions to start
	| a discussion on the selected place.
	|
	*/

	'cannot_start_discussion' => array(
		'title' => 'Cannot Start Discussion',
		'message' => '<strong>Hold up there!</strong> You do not have the correct permissions to start a discussion on this place.'
	),

	/*
	|--------------------------------------------------------------------------
	| Edit Failure
	|--------------------------------------------------------------------------
	|
	| English language message for when user failed to edit a discussion.
	|
	*/

	'edit_failure' => array(
		'title' => 'Discussion Edit Failure',
		'message' => '<strong>Oh shoot!</strong> The discussion could not be edited, there may have been a database related problem. Please try again.'
	),

	/*
	|--------------------------------------------------------------------------
	| Discussion Validation Messages
	|--------------------------------------------------------------------------
	|
	| English language messages for individual field errors.
	|
	*/

	'messages' => array(
		'body'  => array('is_required' => 'You need to write something for your discussion.'),
		'title' => array('is_required' => 'You need a title for your discussion.'),
		'place' => array('is_required' => 'You need to select a place for your discussion.')
	),

	/*
	|--------------------------------------------------------------------------
	| Discussion Form Labels
	|--------------------------------------------------------------------------
	|
	| English language messages for discussion page form labels.
	|
	*/

	'labels' => array(
		'place' => array(
			'title'  => 'Place',
			'helper' => 'Where do you want to start this discussion?'
		),
		'title' => array(
			'title' => 'Discussion title'
		),
		'participants' => array(
			'title'  => 'Discussion participants',
			'helper' => 'List a person or group of people who can participate in this discussion, by default anyone can participate.'
		),
		'save_draft' => array(
			'title'  => 'Save Draft',
			'helper' => 'Don\'t worry, people can\'t see it.'
		)
	),

	/*
	|--------------------------------------------------------------------------
	| Discussion Strings
	|--------------------------------------------------------------------------
	|
	| English language strings for discussion related tasks.
	|
	*/

	'anyone_can_participate' => 'Anyone can participate in this discussion.',
	'start_discussion'		 => 'Start Discussion',
	'save_discussion'		 => 'Save Discussion',
	'save_draft' 			 => 'Save Draft',
	'preview_discussion'	 => 'Preview Discussion',
	'draft_saved' 			 => '<strong>Awesome!</strong> Your changes to the discussion draft have been saved.',
	'started'				 => '<strong>Great stuff!</strong> Your discussions has now been started. Take a look!',
	'saved'					 => '<strong>Thanks for that!</strong> Your changes to the discussion have been saved.'

);