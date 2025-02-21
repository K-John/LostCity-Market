<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        if (!is_null($listing->deleted_at)) {
            return false;
        }

        return $listing->token === session('listing_token') || ($user && $user->id === $listing->user_id);
    }

    public function delete(?User $user, Listing $listing): bool
    {
        if (!is_null($listing->deleted_at)) {
            return false;
        }

        return $listing->token === session('listing_token') || ($user && $user->id === $listing->user_id);
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
