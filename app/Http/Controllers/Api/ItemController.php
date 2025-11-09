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

        if (! $search) {
            return response()->json([]);
        }

        $items = Item::active()
            ->when($request->query('include_banners', false), function ($query) {
                $query->with('banners');
            })
            ->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('slug', 'LIKE', '%' . str_replace(' ', '_', $search) . '%');
            })
            ->orderByRaw('CHAR_LENGTH(name)')
            ->limit(5)
            ->get();

        return response()->json(ItemData::collect($items));
    }

    public function show(Request $request, Item $item)
    {
        $listingType = $this->getListingType($request);

        $listings = $item->listings()
            ->active()
            ->where('type', $listingType)
            ->paginate(20);

        return response()->json(ListingData::collect($listings, PaginatedDataCollection::class));
    }
}
