<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use App\Models\Listing;
use App\Pages\ListingsEditPage;
use App\Pages\ListingsIndexPage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelData\PaginatedDataCollection;

class ListingController
{
    use AuthorizesRequests;

    public function index()
    {
        $token = Session::get('listing_token');

        $listings = Listing::with('item')
                ->where('token', $token)
                ->whereNull('deleted_at')
                ->where('updated_at', '>=', now()->subDays(2))
                ->orderBy('updated_at', 'desc')
                ->paginate(20);

        $maskedToken = $token ? substr($token, 0, 4) . str_repeat('*', strlen($token) - 8) . substr($token, -4) : null;

        return inertia('listings/index/page', new ListingsIndexPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            token: $maskedToken
        ));
    }

    public function create() {}

    public function store(ListingFormData $data)
    {
        $key = Session::get('listing_token');

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException('Too many requests. Please wait a few seconds before submitting again.');
        }

        // Allow only 1 attempt per 3 seconds
        RateLimiter::hit($key, 3);

        $listingData = collect($data->toArray())->except('item')->toArray();
        $listingData['item_id'] = $data->item->id;

        Listing::create($listingData);

        return to_route('items.show', $data->item->slug)->success('The listing has been created');
    }

    public function edit(Listing $listing) 
    {
        $this->authorize('update', $listing);

        return inertia('listings/edit/page', new ListingsEditPage(
            listingForm: new ListingFormData(
                id: $listing->id,
                type: \App\Enums\ListingType::from($listing->type),
                price: $listing->price,
                quantity: $listing->quantity,
                notes: $listing->notes,
                username: $listing->username,
                item: ItemData::from($listing->item),
            ),
        ));
    }

    public function update(ListingFormData $data, Listing $listing) 
    {
        $this->authorize('update', $listing);

        $listing->update($data->getListingData());

        return to_route('listings.index')->success('The listing has been updated');
    }

    public function destroy(Listing $listing) 
    {
        $this->authorize('delete', $listing);

        $listing->delete();

        return to_route('listings.index')->success('The listing has been deleted');
    }
}
