<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        return $next($request);
    }
}
