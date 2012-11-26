<?php

use Mockery as m;
use Feather\Models\Extension;

class ExtensionDispatcherTest extends TestCase {


	public function testDispatcherRegisterExtension()
	{
		$dispatcher = $this->getDispatcher();
		$extension = new Extension(array(
			'location' => 'extension/location',
			'identifier' => 'foo',
			'auto' => false
		));
		$dispatcher->register($extension);
		$this->assertInstanceOf('Feather\Models\Extension', $dispatcher['extension.foo']);
		$this->assertEquals('foo', $dispatcher['extension.foo']->identifier);
		$this->assertEquals('extension/location', $dispatcher['extension.foo']->location);
		$this->assertTrue($dispatcher->isRegistered('foo'));
	}


	public function testDispatcherRegistersExtensionsFromArray()
	{
		$dispatcher = $this->getDispatcher();
		$extension = new Extension(array(
			'location' => 'extension/location',
			'identifier' => 'foo',
			'auto' => false
		));
		$dispatcher->registerExtensions(array($extension));
		$this->assertInstanceOf('Feather\Models\Extension', $dispatcher['extension.foo']);
		$this->assertEquals('foo', $dispatcher['extension.foo']->identifier);
		$this->assertEquals('extension/location', $dispatcher['extension.foo']->location);
	}


	public function testDispatcherAutoStartExtension()
	{
		$app = new Illuminate\Container;
		$app['events'] = new Illuminate\Events\Dispatcher;
		$files = m::mock('Illuminate\Filesystem');
		$files->shouldReceive('exists')->once()->andReturn(true);
		$dispatcher = m::mock('Feather\Extensions\Dispatcher[findExtensions,loadExtension]');
		$dispatcher->__construct($files, 'path/to');
		$dispatcher->setApplication($app);
		$extension = new Extension(array(
			'location' => 'extension/location',
			'identifier' => 'foo',
			'auto' => true
		));
		$file = m::mock('stdClass');
		$file->shouldReceive('getBasename')->once()->andReturn('FooExtension');
		$file->shouldReceive('getExtension')->once()->andReturn('php');
		$instantiatedExtension = m::mock('Feather\Extensions\Extension');
		$instantiatedExtension->shouldReceive('start')->once();
		$dispatcher->shouldReceive('findExtensions')->once()->with('path/to/extension/location')->andReturn(array($file));
		$dispatcher->shouldReceive('loadExtension')->with('Feather\Extensions\extension\location\FooExtension')->andReturn($instantiatedExtension);
		$dispatcher->register($extension);
		$this->assertContains('foo', $dispatcher->getStarted());
		$this->assertTrue($dispatcher->isStarted('foo'));
	}


	protected function getDispatcher()
	{
		$app = new Illuminate\Container;
		$app['events'] = new Illuminate\Events\Dispatcher;
		$files = m::mock('Illuminate\Filesystem');
		$files->shouldReceive('exists')->once()->andReturn(true);
		$dispatcher = m::mock(new Feather\Extensions\Dispatcher($files, 'path/to'));
		$dispatcher->setApplication($app);
		return $dispatcher;
	}


}

