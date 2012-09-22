<?php namespace Feather\Gear\Stub;

use Feather\Components\Gear\Foundation;

class Mock extends Foundation {

	public function __construct()
	{
		$this->listen('mock.callable', function()
		{
			return 'cat';
		});

		$this->listen('mock.method', 'cat');
	}

	public function cat()
	{
		return 'dog';
	}

}