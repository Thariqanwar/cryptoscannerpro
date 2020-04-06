<?php

namespace PragmaRX\Google2FALaravel;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Auth;

class Middleware
{
    public function handle($request, Closure $next)
    {
    	
	    if(Auth::user()->authentication_status==1)
    			{
	        $authenticator = app(Authenticator::class)->boot($request);


	        if ($authenticator->isAuthenticated()) {
	            	return $next($request);
	     
	        }

    	
	        return $authenticator->makeRequestOneTimePasswordResponse();
	    }
	    else
	    {

	    	return $next($request);
	    }
    }
}
