<?php

return [

    'rollbar' => [		//not requirement of php laravel sdk since laravel 5.6
        'client_token'=>env('ROLLBAR_CLIENT_TOKEN'),
        'access_token' => env('ROLLBAR_TOKEN'),
        'public_domain' => env('PUBLIC_DOMAIN'),
        //'level' => env('ROLLBAR_LEVEL'),
		'code_version'=>env('GIT_REVISION_HASH'),//custom
    ],

];
