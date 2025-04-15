<?php

namespace App\Http\Middleware;

use App\Data\Banner\BannerData;
use App\Data\Shared\SharedData;
use App\Data\Shared\UserData;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Spatie\LaravelData\DataCollection;

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
            globalBanners: fn () => cache()->remember('banners.global.active', 3600, function () {
                return BannerData::collect(Banner::active()->global()->get(), DataCollection::class);
            })
        );

        return array_merge(parent::share($request), $state->toArray());
    }
}
