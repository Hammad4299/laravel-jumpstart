<?php

namespace App\Http\Middleware;

use Closure;
use JavaScript;

//Add it as middleware for routes where js vars is neeeded
class JsRouteVariableRegistrarMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        JavaScript::put([
            'routes' => [
                'baseurl'=>config('app.ajax_base_url')
            ],
            'csrftoken'=>csrf_token(),
            'siteConfig' => [
				'code_version'=>config('services.rollbar.code_version'),
                'rollbarToken' => config('services.rollbar.client_token'),
                'environmment' => config('app.env'),
                'baseUrl' => url('/'),
            ]
        ]);

        return $next($request);
    }
}