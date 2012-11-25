<?php

Basset::collection('basic', function($collection)
{
	$collection->add('feather/themes/basic/css/theme.css');
})->apply('UriRewriteFilter', array($app['path.public']));