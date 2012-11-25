<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Session Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the default session "driver" that will be used on
	| requets. By default, we will use the light-weight cookie driver but
	| you may specify any of the other wonderful drivers provided here.
	|
	| Supported: "cookie", file", "database", "apc",
	|            "memcached", "redis", "array"
	|
	*/

	'driver' => 'cookie',

	/*
	|--------------------------------------------------------------------------
	| Session Lifetime
	|--------------------------------------------------------------------------
	|
	| Here you may specify the number of minutes that you wish the session
	| to be allowed to remain idle for it is expired. If you want them
	| to immediately expire when the browser closes, set it to zero.
	|
	*/

	'lifetime' => 120,

	/*
	|--------------------------------------------------------------------------
	| Session File Location
	|--------------------------------------------------------------------------
	|
	| When using the "file" session driver, we need a location where session
	| files may be stored. A default has been set for you but a different
	| location may be specified. This is only needed for file sessions.
	|
	*/

	'path' => __DIR__.'/../storage/sessions',

	/*
	|--------------------------------------------------------------------------
	| Session Database Connection
	|--------------------------------------------------------------------------
	|
	| When using the "database" session driver, you may specify the database
	| connection that should be used to manage your sessions. This should
	| correspond to a connection in your "database" configuration file.
	|
	*/

	'connection' => null,

	/*
	|--------------------------------------------------------------------------
	| Session Database Table
	|--------------------------------------------------------------------------
	|
	| When using the "database" session driver, you may specify the table we
	| should use to manage the sessions. Of course, a sensible default is
	| provided for you; however, you are free to change this as needed.
	|
	*/

	'table' => 'sessions',

	/*
	|--------------------------------------------------------------------------
	| Session Sweeping Lottery
	|--------------------------------------------------------------------------
	|
	| Some session drivers must manually sweep their storage location to get
	| rid of old sessions from storage. Here are the chances that it will
	| happen on a given request. By default, the odds are 2 out of 100.
	|
	*/

	'lottery' => array(2, 100),

);
