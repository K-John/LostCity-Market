<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ListingPolicy
{
    use HandlesAuthorization;

    public function viewAny(): bool
    {
        return true;
    }

    public function view(): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Listing $listing): bool
    {
        if (!is_null($listing->sold_at)) {
            return false;
        }

        return $user->id === $listing->user_id;
    }

    public function delete(?User $user, Listing $listing): bool
    {
        if (!is_null($listing->sold_at)) {
            return false;
        }

        return $user->id === $listing->user_id;
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
