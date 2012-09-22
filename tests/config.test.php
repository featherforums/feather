<?php

class ConfigTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();

		$this->feather['config']->set('test', $this->items());
	}

	public function testItemsCanBeSet()
	{
		$this->feather['config']->set('name', 'jason');
		$this->assertEquals('jason', $this->feather['config']->get('name'));

		$this->feather['config']->set('person.name', 'jason');
		$this->assertEquals('jason', $this->feather['config']->get('person.name'));

		$this->feather['config']->set('namespace::person.name', 'jason');
		$this->assertEquals('jason', $this->feather['config']->get('namespace::person.name'));

		$this->feather['config']->set('gear: mock person.name', 'jason');
		$this->assertEquals('jason', $this->feather['config']->get('gear: mock person.name'));

		$this->feather['config']->set('theme: mock person.name', 'jason');
		$this->assertEquals('jason', $this->feather['config']->get('theme: mock person.name'));
	}

	public function testGetBasicItems()
	{
		$this->assertEquals('bar', $this->feather['config']->get('test.foo'));
		$this->assertEquals('orange', $this->feather['config']->get('test.apple'));	
	}

	public function testEntireArrayCanBeReturned()
	{
		$this->assertEquals($this->items(), $this->feather['config']->get('test'));
	}

	public function testCheckItemExistance()
	{
		$this->feather['config']->set('foo', 'bar');

		$this->assertTrue($this->feather['config']->has('foo'));
		$this->assertTrue(!$this->feather['config']->has('apple'));
	}

	public function testItemsCanBeSaved()
	{
		$this->feather['config']->set('feather: db.test', 'foobar');
		$this->feather['config']->save('feather: db.test');

		$this->feather['config']->reload();

		$this->assertEquals('foobar', $this->feather['config']->get('feather: db.test'));

		$this->feather['config']->delete('feather: db.test');
	}

	public function testItemsCanBeDeleted()
	{
		$this->feather['config']->set('feather: db.test', 'foobar');
		$this->feather['config']->save('feather: db.test');

		$this->feather['config']->reload();

		$this->feather['config']->delete('feather: db.test');

		$this->feather['config']->reload();

		$this->assertEquals(null, $this->feather['config']->get('feather: db.test'));
	}

	public function items()
	{
		return array('foo' => 'bar', 'apple' => 'orange');
	}

}