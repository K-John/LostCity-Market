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
        $listings = Listing::whereNull('deleted_at')
            ->where('updated_at', '>=', now()->subDays(2))
            ->latest()
            ->paginate(20);

        return inertia('home/index/page', new HomeIndexPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class)
        ));
    }
}
