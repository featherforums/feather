<?php

class ValidationTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();
	}

	public function testCanChangeApplication()
	{
		$validator = $this->feather['validator']->app('cat');

		$this->assertEquals('cat', $validator->application);
	}

	public function testCanValidateAgainstInput()
	{
		$validator = $this->feather['validator']->against(array('foo' => 'bar'));

		$this->assertEquals(array('foo' => 'bar'), $validator->input);
	}

	public function testCanSetCustomData()
	{
		$validator = $this->feather['validator']->with('foo', 'bar');

		$this->assertEquals(array('foo' => 'bar'), $validator->data);
	}

	public function testCanLoadValidatorClosure()
	{
		$this->feather['config']->set('feather: validation.test', function($validator){});

		$validator = $this->feather['validator']->get('test');

		$this->assertArrayHasKey('test', $validator->validating);
	}

	public function testCanAddRule()
	{
		$validator = $this->feather['validator']->rule('cat', 'dog');

		$this->assertEquals(array('cat' => array('dog')), $validator->rules);
	}

	public function testCanAddMessage()
	{
		$validator = $this->feather['validator']->message('cat', 'dog');

		$this->assertEquals(array('cat' => 'core::dog'), $validator->messages);
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
