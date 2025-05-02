<?php

namespace App\Http\Controllers;

use App\Data\Banner\BannerData;
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
use Spatie\LaravelData\Support\Lazy\ClosureLazy;

class ItemController
{
    use HandlesListingType;

    public function show(Request $request, Item $item)
    {
        $listingType = $this->getListingType($request);

        $listings = $item->listings()
            ->active()
            ->where('type', $listingType)
            ->paginate(20);

        return inertia('items/show/page', new ItemsShowPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            item: ClosureLazy::closure(fn() => ItemData::from($item)),
            listingType: $listingType,
            listingForm: ClosureLazy::closure(fn() => $this->getListingFormData($item, $listingType)),
            soldListings: ClosureLazy::closure(fn() => ListingData::collect($this->getSoldListings($item), DataCollection::class)),
            usernames: ClosureLazy::closure(fn() => UsernameService::getAuthenticatedUsernames()),
            banners: ClosureLazy::closure(fn() => BannerData::collect($item->banners()->active()->get(), DataCollection::class)),
        ));
    }

    protected function getSoldListings(Item $item)
    {
        return cache()->remember(
            "item_{$item->id}_sold_listings",
            now()->addMinutes(30),
            fn() => $item->listings()
                ->whereNotNull('sold_at')
                ->orderBy('sold_at', 'desc')
                ->take(10)
                ->get()
        );
    }

    protected function getLatestUsername()
    {
        return Auth::user()?->listings()->latest()->value('username') ?? '';
    }

    protected function getListingFormData(Item $item, $listingType)
    {
        return new ListingFormData(
            id: null,
            type: $listingType,
            price: '',
            quantity: null,
            notes: '',
            username: $this->getLatestUsername(),
            item_id: $item->id,
            usernames: UsernameService::getAuthenticatedUsernames()
        );
    }
}
