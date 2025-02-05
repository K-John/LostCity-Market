<?php

namespace App\Http\Controllers\Api;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingData;
use App\Http\Traits\HandlesListingType;
use App\Models\Item;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class ItemController
{
    use HandlesListingType;

    public function index(Request $request)
    {
        $search = $request->query('q');

        if (!$search) {
            return response()->json([]);
        }

        $items = Item::where('name', 'LIKE', "%{$search}%")
            ->orderByRaw('CHAR_LENGTH(name)')
            ->take(5)
            ->get();

        return response()->json(ItemData::collect($items));
    }

    public function show(Request $request, Item $item)
    {
        $listingType = $this->getListingType($request);

        $listings = $item->listings()
            ->whereNull('deleted_at')
            ->where('type', $listingType)
            ->where('updated_at', '>=', now()->subDays(2))
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return response()->json(ListingData::collect($listings, PaginatedDataCollection::class));
    }
}
