<?php

class AuthTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();

		$this->feather['auth']->user = $this->user();
	}

	public function testCanDoAction()
	{
		$this->assertTrue($this->feather['auth']->can('do: something'));
	}

	public function testCannotDoAction()
	{
		$this->assertTrue($this->feather['auth']->cannot('view: something'));
	}

	public function testCanCheckRole()
	{
		$this->assertTrue($this->feather['auth']->is('administrator'));
		$this->assertTrue($this->feather['auth']->is('admin'));
		$this->assertTrue($this->feather['auth']->not('boggyman'));
	}

	public function testCanCheckOnline()
	{
		$this->assertTrue($this->feather['auth']->online());
	}

	public function testCanCheckActivation()
	{
		$this->assertTrue($this->feather['auth']->activated());
	}

	public function user()
	{
		return new Feather\Models\User(array(
			'roles' => array(
				new Feather\Models\Role(array('do_something' => 1)),
				new Feather\Models\Role(array('name' => 'Administrator')),
				new Feather\Models\Role(array('view_something' => 0))
			),
			'activated' => 1
		));
	}

}