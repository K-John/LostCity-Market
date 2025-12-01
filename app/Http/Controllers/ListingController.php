<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemFormData;
use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use App\Data\Listing\ListingOfferData;
use App\Data\Listing\ListingOfferItemData;
use App\Enums\ListingType;
use App\Models\Item;
use App\Models\Listing;
use App\Models\User;
use App\Pages\ListingsCreatePage;
use App\Pages\ListingsDeletePage;
use App\Pages\ListingsEditPage;
use App\Pages\ListingsIndexPage;
use App\Services\UsernameService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ListingController
{
    use AuthorizesRequests;

    protected User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index(Request $request)
    {
        $listings = $this->user->listings()->active()->with('item', 'offers.items.item')->paginate(20);
        $expiredListings = $this->user->listings()->expired()->with('item', 'offers.items.item');
        $pausedListings = $this->user->listings()->paused()->with('item', 'offers.items.item');

        return inertia('listings/index/page', new ListingsIndexPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            expiredListings: ListingData::collect($expiredListings->get(), DataCollection::class),
            pausedListings: ListingData::collect($pausedListings->get(), DataCollection::class),
            usernames: UsernameService::getAuthenticatedUsernames(),
        ));
    }

    public function create()
    {
        $latestUsername = $this->user->listings()->latest()->value('username');

        $coins = Item::where('game_id', 995)->first();

        return inertia('listings/create/page', new ListingsCreatePage(
            listingForm: new ListingFormData(
                id: null,
                type: ListingType::Buy,
                quantity: null,
                notes: '',
                username: $latestUsername ?? '',
                item_id: null,
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
            )
        ));
    }

    public function store(ListingFormData $data, Request $request)
    {
        return DB::transaction(function () use ($data) {
            $listingData = collect($data->toArray())
                ->except(['item', 'usernames', 'offers'])
                ->toArray();

            $listing = Listing::create($listingData);

            $this->syncOffers($listing, $data->offers);

            $item = Item::findOrFail($data->item_id);

            return to_route('items.show', $item->slug)
                ->success('The listing has been created and will expire in 24 hours');
        });
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $listing->load('item', 'offers.items.item');

        $usernames = User::find($listing->user_id)
            ->usernames
            ->pluck('username')
            ->toArray();

        if ($listing->offers->isNotEmpty()) {
            $offersData = ListingOfferData::collect($listing->offers, DataCollection::class);

        } elseif (! is_null($listing->price)) {
            // Legacy listing with a price but no offers: synthesize a coin offer

            $coins = Item::where('game_id', 995)->first();

            $offerItems = ListingOfferItemData::collect([
                new ListingOfferItemData(
                    id: null,
                    listingOfferId: null,
                    quantity: (int) $listing->price,
                    item_id: $coins->id,
                    item: ItemFormData::from($coins),
                ),
            ], DataCollection::class);

            $offersData = ListingOfferData::collect([
                new ListingOfferData(
                    id: null,
                    listingId: $listing->id,
                    title: 'For each item:',
                    items: $offerItems,
                ),
            ], DataCollection::class);

            $listing->price = null;
        }

        return Inertia::modal('listings/edit/page', new ListingsEditPage(
            listing: ListingData::from($listing),
            listingForm: new ListingFormData(
                id: $listing->id,
                type: ListingType::from($listing->type),
                quantity: $listing->quantity,
                notes: $listing->notes,
                username: $listing->username,
                item_id: $listing->item_id,
                offers: $offersData,
                usernames: $usernames,
            ),
        ))->baseRoute('listings.index');
    }

    public function update(ListingFormData $data, Listing $listing)
    {
        $this->authorize('update', $listing);

        // Only bump if the listing was last updated more than 30 minutes ago
        if ($listing->updated_at->diffInMinutes(now()) < 30) {
            $listing->timestamps = false;
        }

        return DB::transaction(function () use ($data, $listing) {
            if (! is_null($listing->price) && $data->offers->count() > 0) {
                $listing->price = null;
            }
            $listing->update($data->getListingData());

            $this->syncOffers($listing, $data->offers);

            if ($listing->timestamps) {
                $listing->touch();
            }

            return back()->success('The listing has been updated');
        });
    }

    public function delete(Listing $listing, Request $request)
    {
        $this->authorize('delete', $listing);

        $redirect = $request->query('redirect', route('listings.index'));

        return Inertia::modal('listings/delete/page', new ListingsDeletePage(
            listing: ListingData::from($listing->load('item', 'offers.items.item')),
            redirect: $redirect,
        ))
            ->baseRoute('listings.index');
    }

    public function destroy(Listing $listing, Request $request)
    {
        $this->authorize('delete', $listing);

        $listing->delete();

        $back = $request->session()->pull('background_url'); // read + forget

        return $back
            ? redirect()->to($back)->success('The listing has been permanently deleted.')
            : redirect()->route('listings.index')->success('The listing has been permanently deleted.');
    }

    /**
     * Sync offers + offer items from the DTO to the database.
     */
    private function syncOffers(\App\Models\Listing $listing, DataCollection $offers): void
    {
        $listing->load('offers.items');

        $existingOffers = $listing->offers->keyBy('id');
        $incomingOfferIds = [];

        /** @var ListingOfferData $offerData */
        foreach ($offers as $offerData) {
            $offerId = $offerData->id;

            // Find existing or create new
            if ($offerId && $existingOffers->has($offerId)) {
                $offer = $existingOffers[$offerId];
                $offer->update([
                    'title' => $offerData->title,
                ]);
            } else {
                $offer = $listing->offers()->create([
                    'title' => $offerData->title,
                ]);
            }

            $incomingOfferIds[] = $offer->id;

            $this->syncOfferItems($offer, $offerData->items);
        }

        $offersToDelete = $listing->offers()
            ->when(! empty($incomingOfferIds), function ($q) use ($incomingOfferIds) {
                $q->whereNotIn('id', $incomingOfferIds);
            });

        foreach ($offersToDelete->get() as $offerToDelete) {
            $offerToDelete->items()->delete();
            $offerToDelete->delete();
        }
    }

    /**
     * Sync items for a given offer.
     */
    private function syncOfferItems(\App\Models\ListingOffer $offer, DataCollection $items): void
    {
        $offer->loadMissing('items');

        $existingItems = $offer->items->keyBy('id');
        $incomingItemIds = [];

        /** @var ListingOfferItemData $itemData */
        foreach ($items as $itemData) {
            $itemId = $itemData->id;

            if ($itemId && $existingItems->has($itemId)) {
                // Update existing item
                $item = $existingItems[$itemId];
                $item->update([
                    'item_id' => $itemData->item->id,
                    'quantity' => $itemData->quantity,
                ]);
            } else {
                // Create new item
                $item = $offer->items()->create([
                    'item_id' => $itemData->item->id,
                    'quantity' => $itemData->quantity,
                ]);
            }

            $incomingItemIds[] = $item->id;
        }

        $offer->items()
            ->when(! empty($incomingItemIds), function ($q) use ($incomingItemIds) {
                $q->whereNotIn('id', $incomingItemIds);
            })
            ->delete();
    }
}
