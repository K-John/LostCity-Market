<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesListingType;
use App\Data\Listing\ListingData;
use App\Enums\ListingType;
use App\Models\Listing;
use App\Pages\HomeIndexPage;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Support\Facades\Cache;

class HomeController
{
    use HandlesListingType;

    public function __invoke(Request $request)
    {
        $listingType = $this->getListingType($request);
        $cacheKey = "home_page_{$listingType->name}_page_" . ($request->query('page', 1));

        // Cache only the query results
        $listings = Cache::remember($cacheKey, 30, function () use ($listingType) {
            return Listing::with('item')
                ->whereNull('deleted_at')
                ->where('updated_at', '>=', now()->subDays(2))
                ->where('type', $listingType)
                ->orderBy('updated_at', 'desc')
                ->paginate(20);
        });

        // Return the Inertia response using the cached data
        return inertia('home/index/page', new HomeIndexPage(
            listingType: $listingType,
            listings: ListingData::collect($listings, PaginatedDataCollection::class)
        ));
    }
}
