<?php

namespace App\Http\Controllers;

use App\Data\Banner\BannerData;
use App\Data\Item\ItemData;
use App\Data\Item\ItemFormData;
use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use App\Data\Listing\ListingOfferData;
use App\Data\Listing\ListingOfferItemData;
use App\Http\Traits\HandlesListingType;
use App\Models\Item;
use App\Pages\ItemsShowPage;
use App\Services\UsernameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Support\Lazy\ClosureLazy;

class ItemController
{
    use HandlesListingType;

    public function show(Request $request, Item $item)
    {
        if (! $item->is_active || ! $item->is_listable) {
            abort(404);
        }

        $listingType = $this->getListingType($request);

        $listings = $item->listings()
            ->with('offers.items.item')
            ->active()
            ->where('type', $listingType)
            ->paginate(20);

        return inertia('items/show/page', new ItemsShowPage(
            listingType: $listingType,
            item: ClosureLazy::closure(fn () => ItemData::from($item)),
            listingForm: ClosureLazy::closure(fn () => $this->getListingFormData($item, $listingType)),
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            soldListings: ClosureLazy::closure(fn () => ListingData::collect($this->getSoldListings($item), DataCollection::class)),
            usernames: ClosureLazy::closure(fn () => UsernameService::getAuthenticatedUsernames()),
            banners: ClosureLazy::closure(fn () => BannerData::collect($item->banners()->active()->get(), DataCollection::class)),
        ));
    }

    protected function getSoldListings(Item $item)
    {
        return cache()->remember(
            "item_{$item->id}_sold_listings",
            now()->addMinutes(30),
            fn () => $item->listings()
                ->with('offers.items.item')
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
        $coins = Item::where('game_id', 995)->first();

        return new ListingFormData(
            id: null,
            type: $listingType,
            quantity: null,
            notes: '',
            username: $this->getLatestUsername(),
            item_id: $item->id,
            offers: ListingOfferData::collect([
                new ListingOfferData(
                    id: null,
                    listingId: null,
                    title: 'For each item:',
                    items: ListingOfferItemData::collect([
                        new ListingOfferItemData(
                            id: null,
                            listingOfferId: null,
                            quantity: 0,
                            item_id: $coins->id,
                            item: ItemFormData::from($coins)
                        ),
                    ], DataCollection::class)
                ),
            ], DataCollection::class),

            usernames: UsernameService::getAuthenticatedUsernames()
        );
    }
}
