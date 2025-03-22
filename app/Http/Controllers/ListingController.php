<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use App\Enums\ListingType;
use App\Models\Item;
use App\Models\Listing;
use App\Models\User;
use App\Pages\ListingsCreatePage;
use App\Pages\ListingsEditPage;
use App\Pages\ListingsIndexPage;
use App\Services\UsernameService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
        $listings = $this->user->listings()->active()->with('item')->paginate(20);
        $expiredListings = $this->user->listings()->expired()->with('item');

        return inertia('listings/index/page', new ListingsIndexPage(
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            expiredListings: ListingData::collect($expiredListings->get(), DataCollection::class),
            usernames: UsernameService::getAuthenticatedUsernames(),
        ));
    }

    public function create()
    {
        $latestUsername = $this->user->listings()->latest()->value('username');

        return inertia('listings/create/page', new ListingsCreatePage(
            listingForm: new ListingFormData(
                id: null,
                type: ListingType::Buy,
                price: '',
                quantity: null,
                notes: '',
                username: $latestUsername ?? '',
                item_id: null,
                usernames: UsernameService::getAuthenticatedUsernames()
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

        // Allow only 1 attempt per 3 seconds
        $key = $this->user->id;
        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException('Too many requests. Please wait a few seconds before submitting again.');
        }
        RateLimiter::hit($key, 3);

        $listingData = collect($data->toArray())->except(['item', 'usernames'])->toArray();
        $listingData['ip'] = $request->ip();

        Listing::create($listingData);

        return to_route('items.show', Item::find($data->item_id)->slug)->success('The listing has been created and will expire in 24 hours');
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $usernames = User::find($listing->user_id)->usernames->pluck('username')->toArray();

        return inertia('listings/edit/page', new ListingsEditPage(
            listingForm: new ListingFormData(
                id: $listing->id,
                type: \App\Enums\ListingType::from($listing->type),
                price: $listing->price,
                quantity: $listing->quantity,
                notes: $listing->notes,
                username: $listing->username,
                usernames: $usernames,
                item_id: $listing->item_id,
            ),
        ));
    }

    public function update(ListingFormData $data, Listing $listing)
    {
        $this->authorize('update', $listing);

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

        $listing->update(['sold_at' => now()]);

        return back()->success('The listing has been marked as sold');
    }
}
