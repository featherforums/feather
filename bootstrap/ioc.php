<?php

/*
|--------------------------------------------------------------------------
| Publisher Task
|--------------------------------------------------------------------------
|
| Register the publish task within the IoC container.
|
*/

IoC::register('feather: publisher', function()
{
	require path('feather') . 'tasks' . DS . 'publish' . EXT;

	return new Feather_Publish_Task;
});

/*
|--------------------------------------------------------------------------
| Base Controller
|--------------------------------------------------------------------------
|
| Register the base controller within the IoC container.
|
*/

IoC::register('controller: base', function()
{
	return new Feather_Core_Base_Controller;
});