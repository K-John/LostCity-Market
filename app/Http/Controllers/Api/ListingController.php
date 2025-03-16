<?php

namespace App\Http\Controllers\Api;

use App\Data\Listing\ListingData;
use App\Http\Traits\HandlesListingType;
use App\Models\Listing;
use Illuminate\Http\Request;
use Spatie\LaravelData\DataCollection;

class ListingController
{
    use HandlesListingType;

    public function index(Request $request)
    {
        $listingType = $this->getListingType($request);

        $cacheKey = 'listings_api_' . $listingType->name;

        $listings = cache()->remember($cacheKey, 30, function () use ($listingType) {
            return Listing::active()
                ->with('item')
                ->where('type', $listingType)
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