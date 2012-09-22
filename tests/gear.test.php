<?php

class GearTest extends PHPUnit_Framework_TestCase {

	public function testCanRegisterGear()
	{
		$feather = $this->stub();

		$this->assertTrue($feather['gear']->registered('stub'));
	}

	public function testCanStartGear()
	{
		$feather = $this->stub();

		$this->assertInstanceOf('Feather\\Components\\Gear\\Container', $gear = $feather['gear']->start('stub'));
		$this->assertTrue($feather['gear']->started('stub'));
		$this->assertInstanceOf('Feather\\Gear\\Stub\\Mock', $gear['mock']);
	}

	public function testCanDisableGear()
	{
		$feather = $this->stub();
		
		$this->assertTrue($feather['gear']->registered('stub'));

		$feather['gear']->disable('stub');

		$this->assertTrue(!$feather['gear']->registered('stub'));
	}

	public function testGearEventsFire()
	{
		$feather = $this->stub();

		$this->assertEquals('cat', $feather['gear']->first('mock.callable'));
		$this->assertEquals('dog', $feather['gear']->first('mock.method'));
	}

	public function stub()
	{
		$feather = Feather\Components\Support\Facade::application();

		$feather['gear']->register(new Feather\Models\Gear(array(
			'identifier' => 'stub',
			'location'	 => 'path: ' . __DIR__ . DS . 'mock',
			'auto'		 => false
		)));

		return $feather;
	}

}