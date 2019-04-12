<?php
return [

	'channels' => [
		'stack' => [
            'driver' => 'stack',
            'channels' => ['rollbar','daily'],
        ],
		
		'rollbar' => [		//sdk requirement
				'driver' => 'monolog',
				'code_version'=>env('GIT_REVISION_HASH',null),//custom
				'handler' => \Rollbar\Laravel\MonologHandler::class,
				'access_token' => env('ROLLBAR_TOKEN'),
				'token' => env('ROLLBAR_TOKEN'),	//needed due to https://github.com/rollbar/rollbar-php-laravel/issues/64
				'level' => env('ROLLBAR_LEVEL'),
		]
	]
];