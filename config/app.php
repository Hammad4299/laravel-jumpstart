<?php
return [
	'ajax_base_url'=>env('FRONTEND_BASE_URL','/'),    #relative_url_after scheme,domain with leading 
	'providers'=>[
		App\Providers\HelperServiceProvider::class,
		Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class
	]
];