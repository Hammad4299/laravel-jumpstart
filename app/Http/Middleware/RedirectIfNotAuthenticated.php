<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectIfNotAuthenticated
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
        if (!Auth::guard($guard)->check()) {
            if(!$request->ajax()) {
                Session::put('url.intended',url()->full()); //So, indented method works on redirector.
            }

            if($request->ajax()) {
                return response("Session timed out",419);
            } else {
                $redirectUrl = url()->route('user.login');
                return redirect($redirectUrl);
            }
        }

        return $next($request);
    }
}
