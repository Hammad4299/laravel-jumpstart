<?php
return [
	'ajax_base_url'=>env('AJAX_BASE_URL',null) === null ? url('/') : env('AJAX_BASE_URL',null),
	'static_content_base'=>env('STATIC_CONTENT_URL',null) === null ? url('/') : env('STATIC_CONTENT_URL',null),
	'forceScheme'=>env('FORCE_SCHEME',null),
	'providers'=>[
		App\Providers\HelperServiceProvider::class,
		Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class
	]
];