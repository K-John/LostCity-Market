<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BumpController
{
    public function __invoke(Request $request)
    {
        $listings = Listing::when(Auth::check(), function ($query) {
                $query->whereIn('username', Auth::user()->usernames->pluck('username')->toArray() ?? []);
            }, function ($query) {
                $query->where('token', session('listing_token'));
            })
            ->whereNull('deleted_at')
            ->where('updated_at', '<', now()->subMinutes(30))
            ->where('updated_at', '>=', now()->subDays(2))
            ->get();

        if ($listings->isEmpty()) {
            return back()->error('No listings were found to bump');
        }

        $listings->each->touch();

        return back()->success('Listings bumped successfully');
    }
}
