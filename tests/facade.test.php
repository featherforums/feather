<?php

Bundle::start('feather');

class FacadeTest extends PHPUnit_Framework_TestCase {

	public function testFacadeCallsApplication()
	{
		FacadeStub::application(array('stub' => new ApplicationStub));

		$this->assertEquals('dog', FacadeStub::cat());
	}

}

class FacadeStub extends Feather\Components\Support\Facade {

	/**
	 * Gets the name of the facade component.
	 * 
	 * @return string
	 */
	protected static function accessor(){ return 'stub'; }

}

class ApplicationStub {

	public static function cat()
	{
		return 'dog';
	}

}