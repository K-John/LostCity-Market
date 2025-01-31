<?php

namespace App\Http\Middleware;

use App\Data\Shared\SharedData;
use App\Data\Shared\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request)
    {
        return parent::version($request);
    }

    public function share(Request $request)
    {
        $state = new SharedData(
            user: fn () => Auth::check() ? UserData::from(Auth::user()) : null,
        );

        return array_merge(parent::share($request), $state->toArray());
    }
}
