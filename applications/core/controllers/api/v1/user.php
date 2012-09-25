<?php

class Feather_Core_API_V1_User_Controller extends Feather_API_Controller {

	/**
	 * Search for users by their username.
	 * 
	 * @return array
	 */
	public function get_find()
	{
		$users = array();

		foreach(Feather\Core\User::where('username', 'LIKE', Input::get('term') . '%')->get() as $user)
		{
			$users[] = $this->data($user);
		}

		return $users;
	}

	/**
	 * Return a bunch of user details.
	 * 
	 * @return array
	 */
	public function get_bunch()
	{
		if(!$usernames = json_decode(Input::get('data')))
		{
			return array();
		}

		$users = array();

		foreach(Feather\Core\User::where_in('username', $usernames)->get() as $user)
		{
			$users[] = $this->data($user);
		}

		return $users;
	}

	/**
	 * Returns a user data array.
	 * 
	 * @param  object  $user
	 * @return array
	 */
	protected function data($user)
	{
		return array(
			'id' 				=> $user->id,
			'username' 			=> $user->username,
			'email' 			=> $user->email,
			'created_at' 		=> $user->created_at,
			'total_discussions' => $user->total_discussions,
			'total_replies' 	=> $user->total_replies,
			'avatar' 			=> $user->avatar
		);
	}

}