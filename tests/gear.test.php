<?php

class GearTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();

		$this->feather['gear']->register(new Feather\Core\Gear(array(
			'identifier' => 'stub',
			'location'	 => 'path: ' . __DIR__ . DS . 'mock',
			'auto'		 => false
		)));
	}

	public function testCanRegisterGear()
	{
		$this->assertTrue($this->feather['gear']->registered('stub'));
	}

	public function testCanStartGear()
	{
		$this->assertInstanceOf('Feather\\Components\\Gear\\Container', $gear = $this->feather['gear']->start('stub'));
		$this->assertTrue($this->feather['gear']->started('stub'));
		$this->assertInstanceOf('Feather\\Gear\\Stub\\Mock', $gear['mock']);
	}

	public function testCanDisableGear()
	{
		$this->assertTrue($this->feather['gear']->registered('stub'));

		$this->feather['gear']->disable('stub');

		$this->assertTrue(!$this->feather['gear']->registered('stub'));
	}

	public function testGearEventsFire()
	{
		$this->assertEquals('cat', $this->feather['gear']->first('mock.callable'));
		$this->assertEquals('dog', $this->feather['gear']->first('mock.method'));
	}
}