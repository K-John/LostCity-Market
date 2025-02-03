<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Models\Listing;
use App\Pages\HomeIndexPage;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class HomeController
{
    public function __invoke()
    {
        $listings = Listing::with('item')
            ->whereNull('deleted_at')
            ->where('updated_at', '>=', now()->subDays(2))
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return inertia('home/index/page', new HomeIndexPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class)
        ));
    }
}
