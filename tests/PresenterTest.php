<?php

use Mockery as m;
use Feather\Presenter\Presenter;

class PresenterTest extends TestCase {


	public function testCanPreparePresenter()
	{
		$app = new Illuminate\Container;
		$app['config'] = new Illuminate\Config\Repository(m::mock('Illuminate\Config\LoaderInterface'), 'production');
		$app['config']->getLoader()->shouldReceive('load')->once()->with('production', 'feather', null)->once()->andReturn(array('forum' => array('theme' => 'foo')));
		$app['files'] = m::mock('Illuminate\Filesystem');
		$app['files']->shouldReceive('exists')->once()->andReturn(false);
		$app['view'] = m::mock('Illuminate\View\ViewManager');
		$app['view']->shouldReceive('addLocation')->once()->with('themes/foo/views');
		$app['view']->shouldReceive('addLocation')->once()->with('app/views');
		$app['path'] = 'app';
		$app['path.themes'] = 'themes';
		$presenter = new Presenter($app);
		$presenter->prepare();
	}


}