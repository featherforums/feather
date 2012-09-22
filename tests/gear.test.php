<?php

class GearTest extends PHPUnit_Framework_TestCase {

	public $gear;

	public $manager;

	public function setUp()
	{
		$this->gear = new Feather\Models\Gear(array(
			'identifier' => 'stub',
			'location'	 => 'path: ' . __DIR__ . DS . 'mock',
			'auto'		 => false
		));

		$this->manager = new Feather\Components\Gear\Manager;

		$this->manager->register($this->gear);

		$this->manager->start('stub');
	}

	public function testCanRegisterGear()
	{
		$this->assertTrue($this->manager->registered('stub'));
	}

	public function testCanStartGear()
	{
		$this->assertInstanceOf('Feather\\Components\\Gear\\Container', $gear = $this->manager->start('stub'));
		$this->assertTrue($this->manager->started('stub'));
		$this->assertInstanceOf('Feather\\Gear\\Stub\\Mock', $gear['mock']);
	}

	public function testCanDisableGear()
	{
		$this->assertTrue($this->manager->registered('stub'));

		$this->manager->disable('stub');

		$this->assertTrue(!$this->manager->registered('stub'));
	}

	public function testGearEventsFire()
	{
		$this->assertEquals('cat', Feather\Components\Gear\Manager::first('mock.callable'));
		$this->assertEquals('dog', Feather\Components\Gear\Manager::first('mock.method'));
	}

}