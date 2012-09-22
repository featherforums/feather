<?php

class DateTest extends PHPUnit_Framework_TestCase {

	public $feather;

	public function setUp()
	{
		$this->feather = Feather\Components\Support\Facade::application();
	}

	public function testSetDateAsTimestamp()
	{
		$this->assertEquals('31/01/1991', $this->feather['date']->set(strtotime('31 January 1991'))->show('d/m/Y'));
	}

	public function testSetDateAsString()
	{
		$this->assertEquals('31/01/1991', $this->feather['date']->set('31 January 1991')->show('d/m/Y'));
	}

	public function testCanSetAlternateString()
	{
		$this->assertEquals('cat', $this->feather['date']->set('dog')->alternate('cat')->show('d/m/Y'));
	}

	public function testCanSetPrefixString()
	{
		$this->assertEquals('cat31/01/1991', $this->feather['date']->set('31 January 1991')->prefix('cat')->show('d/m/Y'));
	}

	public function testCanSetSuffixString()
	{
		$this->assertEquals('31/01/1991cat', $this->feather['date']->set('31 January 1991')->suffix('cat')->show('d/m/Y'));
	}

	public function testCanGetFuzzyDate()
	{
		$this->assertEquals('1 day ago', $this->feather['date']->set('Yesterday')->fuzzy());
	}

}
