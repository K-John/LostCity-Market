<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class BumpController
{
    use AuthorizesRequests;

    protected User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        $listings = $this->user->listings()
            ->whereNull('sold_at')
            ->whereBetween('updated_at', [now()->subDays(1), now()->subMinutes(30)])
            ->update(['updated_at' => now()]);

        if ($listings === 0) {
            return back()->error('No listings were found to bump');
        }

        return back()->success('Listings bumped successfully');
    }

    public function update(Listing $listing)
    {
        $this->authorize('update', $listing);

        if ($listing->updated_at->diffInMinutes(now()) < 30) {
            return back()->error('You can only bump a listing every 30 minutes');
        }

        $listing->touch();

        return back()->success('Listing bumped successfully');
    }
}
