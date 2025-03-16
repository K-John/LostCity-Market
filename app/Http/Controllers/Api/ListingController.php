<?php

namespace App\Http\Controllers\Api;

use App\Data\Listing\ListingData;
use App\Http\Traits\HandlesListingType;
use App\Models\Listing;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class ListingController
{
    use HandlesListingType;

    public function index(Request $request)
    {
        $listingType = $this->getListingType($request);

        $listings = Listing::with('item')
            ->whereNull('deleted_at')
            ->where('updated_at', '>=', now()->subDays(1))
            ->where('type', $listingType)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return response()->json(ListingData::collect($listings, PaginatedDataCollection::class));
    }

    public function show($id)
    {
        $listing = Listing::findOrFail($id);

        return response()->json(ListingData::from($listing));
    }
}