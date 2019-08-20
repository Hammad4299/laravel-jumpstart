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
        $publicDomain = config('services.rollbar.public_domain', null);
        $publicDomain = $publicDomain !== null ? $publicDomain : getDomainWithPort(url('/'));

        $ajaxBase = config('app.ajax_base_url', null);
        $ajaxBase = $ajaxBase !== null ? $ajaxBase : url('/');
        
        $staticContentBase = config('app.static_content_base', null);
        $staticContentBase = $staticContentBase !== null ? $staticContentBase : url('/');
        
        JavaScript::put([
            'routes' => [
                'baseurl'=>$ajaxBase
            ],
            'csrftoken'=>csrf_token(),
            'siteConfig' => [
                'rollbarPublicDomain'=>$publicDomain,
                'appname' => config('app.name'),
				'code_version'=>config('services.rollbar.code_version'),
                'rollbarToken' => config('services.rollbar.client_token'),
                'environmment' => config('app.env'),
                'staticContentBaseUrl' => $staticContentBase,
            ]
        ]);

        return $next($request);
    }
}