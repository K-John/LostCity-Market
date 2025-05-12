<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PauseController
{
    use AuthorizesRequests;

    protected User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Pauses all active listings for the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(): RedirectResponse
    {
        $listings = $this->user->listings()->active()->get();

        $listings->each(function ($listing) {
            $listing->timestamps = $listing->updated_at->diffInMinutes(now()) < 30 ? false : true;
            $listing->update(['paused_at' => now()]);
        });

        if ($listings->isEmpty()) {
            return back()->error('No listings were found to pause');
        }

        return back()->success('Listings paused successfully');
    }

    /**
     * Pauses a specific listing.
     *
     * @param Listing $listing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Listing $listing): RedirectResponse
    {
        $this->authorize('update', $listing);

        if ($listing->paused_at) {
            return back()->error('Listing is already paused');
        }

        // Only bump if the listing was last updated more than 30 minutes ago
        if ($listing->updated_at->diffInMinutes(now()) < 30) {
            $listing->timestamps = false;
        }

        $listing->update(['paused_at' => now()]);

        return back()->success('Listing paused successfully');
    }
}
