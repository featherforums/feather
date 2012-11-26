<?php

use Mockery as m;
use Feather\Presenter\Compilers\FeatherCompiler;

class FeatherCompilerTest extends TestCase {


	public function testAssignmentsAreCompiled()
	{
		$compiler = new FeatherCompiler($this->getFiles(), __DIR__);
		$this->assertEquals('<?php $foo = \'bar\'; ?>', $compiler->compileString('@assign($foo, \'bar\')'));
		$this->assertEquals('<?php $foo = $bar; ?>', $compiler->compileString('@assign($foo, $bar)'));
	}


	public function testExtensionEventsAreCompiled()
	{
		$compiler = new FeatherCompiler($this->getFiles(), __DIR__);
		$expected = '<?php echo Feather\Extension::fire(\'foo\'); ?>';
		$this->assertEquals($expected, $compiler->compileString('@event(\'foo\')'));
	}


	public function testInlineErrorsAreCompiled()
	{
		$compiler = new FeatherCompiler($this->getFiles(), __DIR__);
		$expected = '<?php echo $errors->has(\'foo\') ? view("feather::errors.inline", array("error" => $errors->first(\'foo\'))) : null; ?>';
		$this->assertEquals($expected, $compiler->compileString('@error(\'foo\')'));
	}


	public function testErrorsAreCompiled()
	{
		$compiler = new FeatherCompiler($this->getFiles(), __DIR__);
		$expected = '<?php echo $errors->all() ? view("feather::errors.page", array("errors" => $errors->all())) : null; ?>';
		$this->assertEquals($expected, $compiler->compileString('@errors'));
	}


	public function getFiles()
	{
		return m::mock('Illuminate\Filesystem');
	}

}