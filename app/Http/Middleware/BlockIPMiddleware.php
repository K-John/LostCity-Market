<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIPMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('ip_checked')) {
            return $next($request);
        }

        $blockedIps = explode(',', env('BLOCKED_IPS', ''));
        $blockedIps = array_map('trim', $blockedIps);

        if (in_array($request->ip(), $blockedIps)) {
            return response()->json(['message' => 'Access Denied.'], 403);
        }

        $request->session()->put('ip_checked', true);

        return $next($request);
    }
}
