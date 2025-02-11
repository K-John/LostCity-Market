<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use App\Data\Token\TokenFormData;
use App\Enums\ListingType;
use App\Models\Listing;
use App\Pages\ListingsCreatePage;
use App\Pages\ListingsEditPage;
use App\Pages\ListingsIndexPage;
use ConsoleTVs\Profanity\Facades\Profanity;
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

        return inertia('listings/index/page', new ListingsIndexPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            token: $token ? substr($token, 0, 4) . str_repeat('*', strlen($token) - 8) . substr($token, -4) : "",
            tokenForm: new TokenFormData(token: ''),
        ));
    }

    public function create()
    {
        return inertia('listings/create/page', new ListingsCreatePage(
            listingForm: new ListingFormData(
                id: null,
                type: ListingType::Buy,
                price: '',
                quantity: null,
                notes: '',
                username: '',
                item: null,
            )
        ));
    }

    public function store(ListingFormData $data, Request $request)
    {
        $blockedIps = explode(',', env('BLOCKED_IPS', ''));
        $blockedIps = array_map('trim', $blockedIps);

        if (in_array($request->ip(), $blockedIps)) {
            return back()->error('You are not allowed to create listings');
        }

        $key = Session::get('listing_token');

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException('Too many requests. Please wait a few seconds before submitting again.');
        }

        // Allow only 1 attempt per 3 seconds
        RateLimiter::hit($key, 3);

        $listingData = collect($data->toArray())->except('item')->toArray();
        $listingData['item_id'] = $data->item->id;
        if ($listingData['username'] !== 'poon') {
            $listingData['username'] = Profanity::blocker($listingData['username'])->filter();
        }
        if (!empty($listingData['notes'])) {
            $listingData['notes'] = Profanity::blocker($listingData['notes'])->filter();
        }

        $listingData['ip'] = $request->ip();
        Listing::create($listingData);

        return to_route('items.show', $data->item->slug)->success('The listing has been created and will expire in 48 hours');
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

        if ($data->username !== 'poon') {
            $data->username = Profanity::blocker($data->username)->filter();
        }
        if (!empty($data->notes)) {
            $data->notes = Profanity::blocker($data->notes)->filter();
        }

        // Only bump if the listing was last updated more than 30 minutes ago
        if ($listing->updated_at->diffInMinutes(now()) < 30) {
            $listing->timestamps = false;
        }

        $listing->update($data->getListingData());

        return to_route('listings.index')->success('The listing has been updated');
    }

    public function destroy(Listing $listing, Request $request)
    {
        $this->authorize('delete', $listing);

        if ($request->has('force')) {
            $listing->delete();
            return back()->success('The listing has been permanently deleted');
        }

        $listing->update(['deleted_at' => now()]);

        return back()->success('The listing has been marked as sold');
    }

    public function bump(Listing $listing)
    {
        $this->authorize('update', $listing);

        if ($listing->updated_at->diffInMinutes(now()) < 30) {
            return back()->error('You can only bump a listing every 30 minutes');
        }

        $listing->touch();

        return back()->success('Listing bumped successfully');
    }
}
