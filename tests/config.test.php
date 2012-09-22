<?php

class ConfigTest extends PHPUnit_Framework_TestCase {

	public function testItemsCanBeSet()
	{
		$config = $this->getRepository();

		$config->set('name', 'jason');
		$this->assertEquals('jason', $config->get('name'));

		$config->set('person.name', 'jason');
		$this->assertEquals('jason', $config->get('person.name'));

		$config->set('namespace::person.name', 'jason');
		$this->assertEquals('jason', $config->get('namespace::person.name'));

		$config->set('gear: mock person.name', 'jason');
		$this->assertEquals('jason', $config->get('gear: mock person.name'));

		$config->set('theme: mock person.name', 'jason');
		$this->assertEquals('jason', $config->get('theme: mock person.name'));
	}

	public function testGetBasicItems()
	{
		$config = $this->getRepository();

		$this->assertEquals('bar', $config->get('test.foo'));
		$this->assertEquals('orange', $config->get('test.apple'));	
	}

	public function testEntireArrayCanBeReturned()
	{
		$config = $this->getRepository();

		$this->assertEquals($this->getItems(), $config->get('test'));
	}

	public function testCheckItemExistance()
	{
		$config = $this->getRepository();

		$config->set('foo', 'bar');
		$this->assertTrue($config->has('foo'));

		$this->assertTrue(!$config->has('apple'));
	}

	public function testItemsCanBeSaved()
	{
		$config = $this->getRepository();

		$config->set('feather: db.test', 'foobar');
		$config->save('feather: db.test');

		$config->reload();

		$this->assertEquals('foobar', $config->get('feather: db.test'));

		$config->delete('feather: db.test');
	}

	public function testItemsCanBeDeleted()
	{
		$config = $this->getRepository();

		$config->set('feather: db.test', 'foobar');
		$config->save('feather: db.test');

		$config->reload();

		$config->delete('feather: db.test');

		$config->reload();

		$this->assertEquals(null, $config->get('feather: db.test'));
	}

	private function getRepository()
	{
		$config = new Feather\Components\Config\Repository;

		$config->set('test', $this->getItems());

		return $config;
	}

	private function getItems()
	{
		return array('foo' => 'bar', 'apple' => 'orange');
	}

}