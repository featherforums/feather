<?php

class CrumbTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();
	}

	public function testCanDropCrumb()
	{
		$this->feather['crumbs']->drop('stub');

		$this->assertNotEmpty($this->feather['crumbs']->crumbs);
	}

	public function testCanGetHTML()
	{
		$this->assertEquals('<li><a href="http://:/index.php/">stub</a></li>', $this->feather['crumbs']->item('/', 'stub'));
	}

}
