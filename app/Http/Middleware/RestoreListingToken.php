<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class RestoreListingToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('listing_token') && Cookie::has('listing_token')) {
            Session::put('listing_token', Cookie::get('listing_token'));
        }

        return $next($request);
    }
}
