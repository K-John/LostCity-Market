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
use Illuminate\Support\Facades\DB;

class HomeController
{
    use HandlesListingType;

    public function __invoke(Request $request)
    {
        $listingType = $this->getListingType($request);
        $cacheKey = "home_page_{$listingType->name}_page_" . ($request->query('page', 1));

        // Cache only the query results
        $listings = Cache::remember($cacheKey, 30, function () use ($listingType) {
            $subQuery = Listing::active()
                ->select('listings.id')
                ->selectRaw('ROW_NUMBER() OVER (PARTITION BY username ORDER BY updated_at DESC) as row_num')
                ->where('type', $listingType);

            return Listing::with('item') // Eager load the related item model
                ->joinSub($subQuery, 'filtered_listings', function ($join) {
                    $join->on('listings.id', '=', 'filtered_listings.id');
                })
                ->where('row_num', '<=', 3)
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
