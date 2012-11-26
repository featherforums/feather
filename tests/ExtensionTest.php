<?php

use Mockery as m;

class ExtensionTest extends PHPUnit_Framework_TestCase {


	public function testExtensionsAreBootstrapped()
	{
		list($app, $dispatcher) = $this->getApplicationAndDispatcher();
		$extension = array('location' => 'extension/location', 'identifier' => 'foo', 'auto' => true);
		$dispatcher->register($extension);
		$this->assertEquals('success', $app['events']->first('start_test'));
	}


	public function testExtensionsCanListenForEvents()
	{
		list($app, $dispatcher) = $this->getApplicationAndDispatcher();
		$extension = array('location' => 'extension/location', 'identifier' => 'foo', 'auto' => true);
		$extension = $dispatcher->register($extension);
		$extension['loaded']['Feather\Extensions\extension\location\FooExtension']->listen('foobar', function()
		{
			return 'barfoo';
		});
		$extension['loaded']['Feather\Extensions\extension\location\FooExtension']->listen('barfoo', function()
		{
			return 'foobar';
		});
		$this->assertEquals('barfoo', $app['events']->first('foobar'));
		$this->assertEquals('foobar', $app['events']->first('barfoo'));
	}


	public function testExtensionsCanOverrideEvents()
	{
		list($app, $dispatcher) = $this->getApplicationAndDispatcher();
		$extension = array('location' => 'extension/location', 'identifier' => 'foo', 'auto' => true);
		$extension = $dispatcher->register($extension);
		$extension['loaded']['Feather\Extensions\extension\location\FooExtension']->listen('foobar', function()
		{
			return 'barfoo';
		});
		$extension['loaded']['Feather\Extensions\extension\location\FooExtension']->override('foobar', function()
		{
			return 'barbar';
		});
		$this->assertEquals('barbar', $app['events']->first('foobar'));
	}


	public function testExtensionsCanUseMethods()
	{
		list($app, $dispatcher) = $this->getApplicationAndDispatcher();
		$extension = array('location' => 'extension/location', 'identifier' => 'foo', 'auto' => true);
		$extension = $dispatcher->register($extension);
		$extension['loaded']['Feather\Extensions\extension\location\FooExtension']->listen('foobar', 'foo');
		$this->assertEquals('foomethod', $app['events']->first('foobar'));
	}


	protected function getApplicationAndDispatcher()
	{
		$app = new Illuminate\Container;
		$app['events'] = new Illuminate\Events\Dispatcher;
		$app['files'] = m::mock('Illuminate\Filesystem');
		$app['files']->shouldReceive('exists')->once()->andReturn(true);
		$dispatcher = m::mock('Feather\Extensions\Dispatcher[findExtensions,loadExtension]');
		$dispatcher->__construct($app['files'], 'path/to');
		$dispatcher->setApplication($app);
		$file = m::mock('stdClass');
		$file->shouldReceive('getBasename')->once()->andReturn('FooExtension');
		$file->shouldReceive('getExtension')->once()->andReturn('php');
		$instantiatedExtension = m::mock('Feather\Extensions\Extension[start]');
		$instantiatedExtension->__construct($app);
		$instantiatedExtension->shouldReceive('foo')->andReturn('foomethod');
		$instantiatedExtension->shouldReceive('start')->once()->andReturnUsing(function() use ($instantiatedExtension)
		{
			$instantiatedExtension->listen('start_test', function()
			{
				return 'success';
			});
		});
		$dispatcher->shouldReceive('findExtensions')->once()->with('path/to/extension/location')->andReturn(array($file));
		$dispatcher->shouldReceive('loadExtension')->with('Feather\Extensions\extension\location\FooExtension')->andReturn($instantiatedExtension);
		return array($app, $dispatcher);
	}


}