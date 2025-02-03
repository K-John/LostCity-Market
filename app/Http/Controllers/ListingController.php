<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use App\Models\Listing;
use App\Pages\ListingsIndexPage;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelData\PaginatedDataCollection;

class ListingController
{
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

    public function show(Listing $listing) {}

    public function edit(Listing $listing) {}

    public function update(Request $request, Listing $listing) {}

    public function destroy(Listing $listing) {}
}
