<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Data\Listing\ListingOfferData;
use App\Data\Listing\ListingSaleFormData;
use App\Models\Listing;
use App\Pages\ListingsSalePage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\LaravelData\DataCollection;

class ListingSaleController
{
    use AuthorizesRequests;

    public function create(Listing $listing, Request $request)
    {
        $this->authorize('update', $listing);

        $redirect = $request->query('redirect', route('listings.index'));

        return Inertia::modal('listings/sell/page', new ListingsSalePage(
            listing: ListingData::from($listing->load('item', 'offers.items.item')),
            listingSaleForm: new ListingSaleFormData(
                id: $listing->id,
                price: $listing->price,
                quantity: $listing->quantity,
                offers: ListingOfferData::collect($listing->offers, DataCollection::class),
                redirect: $redirect,
            ),
        ))
            ->baseRoute('listings.index');
    }

    public function store(ListingSaleFormData $data, Request $request)
    {
        $listing = Listing::findOrFail($data->id);
        $this->authorize('update', $listing);

        if ($data->quantity < $listing->quantity) {
            $this->markPartialSale($listing, $data);
        } else {
            $this->markFullSale($listing, $data);
        }

        $redirect = $data->redirect ?? route('listings.index');

        return redirect($redirect)
            ->success('Listing sold successfully')
            ->with('listing', ListingData::from($listing->load('item', 'offers.items.item')));
    }

    /**
     * Handle a partial sale: create a new "sold" listing with its own offers,
     * and reduce the quantity on the original listing.
     */
    private function markPartialSale(Listing $listing, ListingSaleFormData $data): void
    {
        // Create a new listing for the sold quantity
        $soldListing = $listing->replicate();
        $soldListing->quantity = $data->quantity;
        $soldListing->price = $data->price;
        $soldListing->sold_at = now();
        $soldListing->save();

        // Attach the sale offers (if any) to the sold listing snapshot
        $this->syncSaleOffers($soldListing, $data);

        // Reduce original listing's quantity
        $listing->quantity -= $data->quantity;

        // Only bump if the listing was last updated more than 30 minutes ago
        if ($listing->updated_at->diffInMinutes(now()) < 30) {
            $listing->timestamps = false;
        }

        $listing->save();
    }

    private function markFullSale(Listing $listing, ListingSaleFormData $data): void
    {
        $listing->sold_at = now();
        $listing->price = $data->price;
        $listing->save();

        $this->syncSaleOffers($listing, $data);
    }

    private function syncSaleOffers(Listing $listing, ListingSaleFormData $data): void
    {
        if ($data->offers->count() === 0) {
            return;
        }

        $listing->load('offers.items');

        foreach ($listing->offers as $existingOffer) {
            $existingOffer->items()->delete();
            $existingOffer->delete();
        }

        foreach ($data->offers as $offerData) {
            /** @var \App\Data\Listing\ListingOfferData $offerData */

            /** @var ListingOffer $offer */
            $offer = $listing->offers()->create([
                'title' => $offerData->title,
            ]);

            foreach ($offerData->items as $itemData) {
                /** @var \App\Data\Listing\ListingOfferItemData $itemData */

                $itemId = $itemData->item_id ?? $itemData->item->id;

                $offer->items()->create([
                    'item_id' => $itemId,
                    'quantity' => $itemData->quantity,
                ]);
            }
        }
    }
}
