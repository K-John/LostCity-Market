<?php

namespace App\Services;

use Illuminate\Http\Request;

class FilterService
{
    public static function resolve(
        Request $request,
        string $sessionKey,
        array $defaults,
    ): array {
        $allowedKeys = array_keys($defaults);

        // Reset, clear and go to the clean route
        if ($request->boolean('reset')) {
            $request->session()->forget($sessionKey);

            return $defaults;
        }

        // If this request has any query params, normalize and store them
        if ($request->query()) {
            $incoming = $request->only($allowedKeys);

            foreach ($incoming as $k => $v) {
                if ($v === '') {
                    $incoming[$k] = null;
                }
            }

            $filters = array_merge($defaults, $incoming);
            $request->session()->put($sessionKey, $filters);

            return $filters;
        }

        // No query string: try session and redirect to canonical URL if present
        if ($request->session()->has($sessionKey)) {
            $stored = $request->session()->get($sessionKey, []);

            return array_merge($defaults, array_intersect_key($stored, array_flip($allowedKeys)));
        }

        // First visit
        return $defaults;
    }
}
