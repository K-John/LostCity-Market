<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Listing;
use App\Models\Username;
use Illuminate\Http\Request;
use App\Pages\UsersIndexPage;
use App\Data\Listing\ListingData;
use App\Services\UsernameService;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\PaginatedDataCollection;

class UsernameController
{
    public function show(String $username, Request $request)
    {
        $listings = Listing::active()
            ->with('item')
            ->where('username', $username)
            ->paginate(20);

        $back = $request->query('back');

        $is_banned = (bool) Username::where('username', $username)->first()?->user?->is_banned;

        return inertia('users/index/page', new UsersIndexPage(
            username: $username,
            is_banned: $is_banned,
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            back: $back
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
