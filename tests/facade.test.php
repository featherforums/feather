<?php

Bundle::start('feather');

class FacadeTest extends PHPUnit_Framework_TestCase {

	public function testFacadeCallsApplication()
	{
		FacadeStub::application(array('foo' => new ApplicationStub));

		$this->assertEquals('apple', FacadeStub::bar());
	}

}

class FacadeStub extends Feather\Components\Support\Facade {

	protected static function accessor(){ return 'foo'; }

}

class ApplicationStub {

	public function bar()
	{
		return 'apple';
	}

}