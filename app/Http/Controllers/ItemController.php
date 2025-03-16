<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingData;
use App\Pages\ItemsShowPage;
use App\Models\Item;
use App\Data\Listing\ListingFormData;
use Illuminate\Http\Request;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use App\Http\Traits\HandlesListingType;
use App\Services\UsernameService;
use Illuminate\Support\Facades\Auth;

class ItemController
{
    use HandlesListingType;

    public function show(Request $request, Item $item)
    {
        $listingType = $this->getListingType($request);

        $itemData = ItemData::from($item);

        $latestUsername = Auth::user()?->listings()->latest()->value('username');

        $listings = $item->listings()
            ->active()
            ->where('type', $listingType)
            ->paginate(20);

        $soldListings = $item->listings()
            ->whereNotNull('sold_at')
            ->orderBy('sold_at', 'desc')
            ->take(10)
            ->get();

        return inertia('items/show/page', new ItemsShowPage(
            listingType: $listingType,
            item: $itemData,
            listingForm: new ListingFormData(
                id: null,
                type: $listingType,
                price: '',
                quantity: null,
                notes: '',
                username: $latestUsername ?? '',
                item: $itemData,
                usernames: UsernameService::getAuthenticatedUsernames(),
            ),
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            soldListings: ListingData::collect($soldListings->map(function ($listing) {
                $listing->sold_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $listing->sold_at);
                return $listing;
            }), DataCollection::class),
        ));
    }
}
