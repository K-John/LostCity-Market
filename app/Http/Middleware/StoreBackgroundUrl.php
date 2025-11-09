<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreBackgroundUrl
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            $ref = $request->headers->get('referer');
            if ($ref && str_starts_with($ref, config('app.url'))) {
                $path = parse_url($ref, PHP_URL_PATH);
                $query = parse_url($ref, PHP_URL_QUERY);
                $background = $path . ($query ? '?' . $query : '');

                $request->session()->put('background_url', $background);
            }
        }

        return $next($request);
    }
}
