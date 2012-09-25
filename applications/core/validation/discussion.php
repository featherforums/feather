<?php

return array(

	'start' => function($validator)
	{
		$validator->rule('body', 'required')
				  ->rule('place', 'required')
				  ->message('body.required', 'discussion.messages.body.is_required')
				  ->message('place.required', 'discussion.messages.place.is_required');

		if(isset($validator->input['start']))
		{
			$validator->rule('title', 'required')
					  ->message('title.required', 'discussion.messages.title.is_required');
		}
	},

	'edit' => function($validator)
	{
		$validator->rule('body', 'required')
				  ->rule('place', 'required')
				  ->message('body.required', 'discussion.messages.body.is_required')
				  ->message('place.required', 'discussion.messages.place.is_required');

		if(isset($validator->input['start']) or isset($validator->input['draft']))
		{
			$validator->rule('title', 'required')
					  ->message('title.required', 'discussion.messages.title.is_required');
		}
	}

);