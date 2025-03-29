<?php

namespace App\Http\Traits;

use App\Enums\FavoritesListingType;
use App\Enums\HomeTabType;
use Illuminate\Http\Request;

trait HandlesHomeTabType
{
    public function getTabType(Request $request): HomeTabType
    {
        if ($request->has('tab') && $tab = HomeTabType::tryFrom($request->query('tab'))) {
            session(['homepage_tab' => $tab->value]);

            // Also update the listing type if possible
            if ($tab !== HomeTabType::Favorites) {
                session(['listing_type' => $tab->value]);
            }
        }

        return HomeTabType::tryFrom(session('homepage_tab')) ?? HomeTabType::Buy;
    }

    public function getFavoritesListingType(Request $request): FavoritesListingType
    {
        if (
            $request->has('type')
            && $type = FavoritesListingType::tryFrom($request->query('type'))
        ) {
            session(['favorites_listing_type' => $type->value]);
        }

        return FavoritesListingType::tryFrom(session('favorites_listing_type')) ?? FavoritesListingType::All;
    }
}
