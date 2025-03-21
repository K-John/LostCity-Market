<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizeAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        return $next($request);
    }
}
