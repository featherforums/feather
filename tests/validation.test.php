<?php

class ValidationTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();
	}

	public function tearDown()
	{
		$this->feather['validator']->app('core');

		$this->feather['validator']->rules = array();

		$this->feather['validator']->messages = array();
	}

	public function testCanChangeApplication()
	{
		$this->feather['validator']->app('cat');

		$this->assertEquals('cat', $this->feather['validator']->application);
	}

	public function testCanValidateAgainstInput()
	{
		$this->feather['validator']->against(array('foo' => 'bar'));

		$this->assertEquals(array('foo' => 'bar'), $this->feather['validator']->input);
	}

	public function testCanSetCustomData()
	{
		$this->feather['validator']->with('foo', 'bar');

		$this->assertEquals(array('foo' => 'bar'), $this->feather['validator']->data);
	}

	public function testCanLoadValidatorClosure()
	{
		$this->feather['config']->set('feather: validation.test', function($validator){});

		$this->feather['validator']->get('test');

		$this->assertArrayHasKey('test', $this->feather['validator']->validating);
	}

	public function testCanAddRule()
	{
		$this->feather['validator']->rule('cat', 'dog');

		$this->assertEquals(array('cat' => array('dog')), $this->feather['validator']->rules);
	}

	public function testCanAddMessage()
	{
		$this->feather['validator']->message('cat', 'dog');

		$this->assertEquals(array('cat' => 'core::dog'), $this->feather['validator']->messages);
	}

	public function testValidationDoesPass()
	{
		$this->feather['config']->set('feather: validation.test', function($validator){
			$validator->rule('cat', 'required');
		});

		$this->assertTrue($this->feather['validator']->get('test')->against(array('cat' => 'dog'))->passes());
	}

	public function testValidationExceptionIsThrown()
	{
		$this->feather['config']->set('feather: validation.test', function($validator){
			$validator->rule('cat', 'required');
		});

		try {
			$this->feather['validator']->get('test')->against(array())->passes();
		}
		catch (FeatherValidationException $exception) {}

		$this->assertInstanceOf('FeatherValidationException', $exception);
	}

}
