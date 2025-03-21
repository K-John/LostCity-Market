<?php

namespace App\Http\Controllers\Api;

use App\Data\Listing\ListingData;
use App\Http\Traits\HandlesListingType;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController
{
    use HandlesListingType;

    public function index(Request $request)
    {
        $listingType = $this->getListingType($request);
        $since = $request->query('since');

        $cacheKey = 'listings_api_' . $listingType->name . ($since ? '_since_' . $since : '');

        $listings = cache()->remember($cacheKey, 30, function () use ($listingType, $since) {
            return Listing::active()
                ->with('item')
                ->where('type', $listingType)
                ->when($since, function ($query, $since) {
                    return $query->where('created_at', '>', $since);
                })
                ->limit(100)
                ->get();
        });

        return response()->json(ListingData::collect($listings));
    }

    public function show($id)
    {
        $listing = Listing::findOrFail($id);

        return response()->json(ListingData::from($listing));
    }
}