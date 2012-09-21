<?php

class ConfigTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		Bundle::start('feather');
	}

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