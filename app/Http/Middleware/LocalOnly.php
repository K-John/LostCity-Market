<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->environment(['local', 'testing'])) {
            abort(403);
        }

        return $next($request);
    }
}
