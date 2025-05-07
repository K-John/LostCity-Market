<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Data\Listing\ListingSaleFormData;
use App\Models\Listing;
use App\Pages\ListingsSalePage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListingSaleController
{
    use AuthorizesRequests;

    public function create(Listing $listing)
    {
        $this->authorize('update', $listing);

        return Inertia::modal('listings/sell/page', new ListingsSalePage(
            listing: ListingData::from($listing->load('item')),
            listingSaleForm: new ListingSaleFormData(
                id: $listing->id,
                price: $listing->price,
                quantity: $listing->quantity,
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

        return back()
            ->success('Listing sold successfully')
            ->with('listing', ListingData::from($listing));
    }

    private function markPartialSale(Listing $listing, ListingSaleFormData $data): void
    {
        // Create a new listing for the sold quantity
        $soldListing = $listing->replicate();
        $soldListing->quantity = $data->quantity;
        $soldListing->price = $data->price;
        $soldListing->sold_at = now();
        $soldListing->save();

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
    }
}
