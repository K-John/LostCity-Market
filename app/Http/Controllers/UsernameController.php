<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Models\Listing;
use App\Pages\UsersIndexPage;
use App\Services\UsernameService;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\PaginatedDataCollection;

class UsernameController
{
    public function show(String $username)
    {
        $listings = Listing::with('item')
            ->where('username', $username)
            ->where('updated_at', '>=', now()->subDays(1))
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return inertia('users/index/page', new UsersIndexPage(
            username: $username,
            listings: ListingData::collect($listings, PaginatedDataCollection::class)
        ));
    }

    public function update()
    {
        if (!Auth::check()) {
            return back()->error('You must be logged in to update your usernames');
        }

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            try {
                UsernameService::updateUsernamesForUser($user);
                return back()->success('Usernames updated from Lost City');

            } catch (\Exception $e) {
                dd($e);
                return back()->error('An error occurred while updating your usernames');
            }
        } else {
            return back()->error('User not found');
        }
    }
}
