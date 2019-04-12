<?php
return [
	'ajax_base_url'=>env('AJAX_BASE_URL',null),
	'static_content_base'=>env('STATIC_CONTENT_PUBLIC_URL',null),
	'providers'=>[
		App\Providers\HelperServiceProvider::class,
		Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class
	]
];