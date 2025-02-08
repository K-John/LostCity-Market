<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Models\Listing;
use App\Pages\UsersIndexPage;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class UsernameController
{
    public function __invoke(String $username, Request $request)
    {
        $listings = Listing::with('item')
            ->where('username', $username)
            ->where('updated_at', '>=', now()->subDays(2))
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return inertia('users/index/page', new UsersIndexPage(
            username: $username,
            listings: ListingData::collect($listings, PaginatedDataCollection::class)
        ));
    }
}
