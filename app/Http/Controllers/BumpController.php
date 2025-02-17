<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BumpController
{
    public function __invoke(Request $request)
    {
        $token = Session::get('listing_token');

        $listings = Listing::where('token', $token)
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
