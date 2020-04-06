<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

                if($this->auth->user()->isAdmin())
                    return Redirect::route('UserList');
                if($this->auth->user()->isUser())
                    return Redirect::route('userProfile');
               
                
        }

        return $next($request);
    }
}
