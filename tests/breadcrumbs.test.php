<?php

class BreadcrumbsTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();
	}

	public function testCanDropCrumb()
	{
		$this->feather['breadcrumbs']->drop('stub');

		$this->assertNotEmpty($this->feather['breadcrumbs']->crumbs);
	}

	public function testCanGetHTML()
	{
		$this->assertEquals('<li><a href="' . URL::home() . '">stub</a></li>', $this->feather['breadcrumbs']->item('/', 'stub'));
	}

}
