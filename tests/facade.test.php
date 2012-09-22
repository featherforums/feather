<?php

Bundle::start('feather');

class FacadeTest extends PHPUnit_Framework_TestCase {

	public function testFacadeCallsApplication()
	{
		$feather = Feather\Components\Support\Facade::application();

		FacadeStub::application(array('stub' => new ApplicationStub));
		$this->assertEquals('dog', FacadeStub::cat());

		Feather\Components\Support\Facade::application($feather);
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