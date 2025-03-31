<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingData;
use App\Enums\HomeTabType;
use App\Http\Traits\HandlesHomeTabType;
use App\Pages\HomeIndexPage;
use App\Services\HomeListingsService;
use App\Services\UsernameService;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\DataCollection;

class HomeController
{
    use HandlesHomeTabType;

    public function __invoke(Request $request, HomeListingsService $service)
    {
        $tab = $this->getTabType($request);
        $type = $this->getFavoritesListingType($request);
        $page = (int) $request->query('page', 1);
        $cacheKey = "home_page_{$tab->value}_page_{$page}";

        if (in_array($tab, [HomeTabType::Buy, HomeTabType::Sell])) {
            $listings = Cache::tags('home_listings')->remember($cacheKey, now()->addMinutes(5), function () use ($service, $tab, $type, $page) {
                return $service->fetch($tab, $type, $page);
            });
        } else {
            // No caching for Favorites
            $listings = $service->fetch($tab, $type, $page);
        }

        // Get the signed in user's favorites if they are on favorites tab and they're signed in
        if ($tab === HomeTabType::Favorites && $request->user()) {
            $favorites = Cache::tags('user_favorites')->remember("user_{$request->user()->id}_favorites", now()->addHours(6), function () use ($request) {
                return ItemData::collect($request->user()->favorites()->latest()->get(), DataCollection::class);
            });
        }

        return inertia('home/index/page', new HomeIndexPage(
            tab: $tab,
            listingType: $type,
            listings: ListingData::collect($listings, PaginatedDataCollection::class),
            favorites: $favorites ?? null,
            usernames: UsernameService::getAuthenticatedUsernames()
        ));
    }
}
