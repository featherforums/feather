<?php

class GearTest extends PHPUnit_Framework_TestCase {

	public function testCanRegisterGear()
	{
		$gear = new Feather\Models\Gear(array(
			'identifier' => 'stub',
			'location'	 => 'path: ' . __DIR__ . DS . 'mock',
			'auto'		 => false
		));

		$manager = new Feather\Components\Gear\Manager;

		$manager->register($gear);

		$this->assertTrue($manager->registered('stub'));
	}

	public function testCanStartGear()
	{
		$gear = new Feather\Models\Gear(array(
			'identifier' => 'stub',
			'location'	 => 'path: ' . __DIR__ . DS . 'mock',
			'auto'		 => false
		));

		$manager = new Feather\Components\Gear\Manager;

		$manager->register($gear);

		$this->assertInstanceOf('Feather\\Components\\Gear\\Container', $gear = $manager->start('stub'));
		$this->assertTrue($manager->started('stub'));
		$this->assertInstanceOf('Feather\\Gear\\Stub\\Mock', $gear['mock']);
	}

}