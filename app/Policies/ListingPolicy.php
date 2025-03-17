<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ListingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Listing $listing): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return true;
    }

    public function update(?User $user, Listing $listing): bool
    {
        if (!is_null($listing->sold_at)) {
            return false;
        }

        $existingListing = Listing::active()->where('type', $listing->type)
            ->when(Auth::check(), function ($query) use ($listing) {
                $query->whereIn('username', Auth::user()->usernames->pluck('username')->toArray() ?? [])
                      ->orWhere('user_id', $listing->user_id)
                      ->orWhere('token', session('listing_token'));
            }, function ($query) {
                $query->where('token', session('listing_token'));
            })
            ->where('item_id', request('item.id'))
            ->where('id', '!=', request('id'))
            ->first();

        if ($existingListing) {
            return false;
        }

        $listingUsername = strtolower($listing->username);
        $listingUsername = str_replace(' ', '_', $listingUsername);
        $listingUsername = trim($listingUsername, ' _');

        return $listing->token === session('listing_token') || // If the listing's token matches the session token
            ($user && ($user->id === $listing->user_id || // If the user is the listing's owner
                in_array($listingUsername, $user->usernames->pluck('username')->toArray()))); // If the user owns the listing's username
    }

    public function delete(?User $user, Listing $listing): bool
    {
        if (!is_null($listing->sold_at)) {
            return false;
        }

        $listingUsername = strtolower($listing->username);
        $listingUsername = str_replace(' ', '_', $listingUsername);
        $listingUsername = trim($listingUsername, ' _');

        return $listing->token === session('listing_token') || // If the listing's token matches the session token
            ($user && ($user->id === $listing->user_id || // If the user is the listing's owner
                in_array($listingUsername, $user->usernames->pluck('username')->toArray()))); // If the user owns the listing's username
    }

    public function restore(?User $user, Listing $listing): bool
    {
        return false;
    }

    public function forceDelete(?User $user, Listing $listing): bool
    {
        return false;
    }
}
