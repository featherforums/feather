<?php namespace Feather\Components\Auth;

use Auth;
use Feather\Components\Foundation\Component;

class Protector extends Component {

	public function bootstrap()
	{
		Auth::extend('feather', function()
		{
			return new Driver;
		});

		$authenticator = $this->feather['config']->get('feather: db.auth.driver');

		dd($authenticator);
	}

}