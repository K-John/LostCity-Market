<?php

namespace App\Services;

use App\Enums\FavoritesListingType;
use App\Enums\HomeTabType;
use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class HomeListingsService
{
    public function fetch(HomeTabType $tab, FavoritesListingType $filterType, int $page = 1): LengthAwarePaginator
    {
        $baseQuery = match ($tab) {
            HomeTabType::Buy, HomeTabType::Sell => Listing::active()->where('type', $tab->value),
            HomeTabType::Favorites => $this->buildFavoritesQuery($filterType),
        };

        $subQuery = $baseQuery
            ->select('listings.id')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY username ORDER BY updated_at DESC) as row_num');

        return Listing::with('item')
            ->joinSub($subQuery, 'filtered_listings', function ($join) {
                $join->on('listings.id', '=', 'filtered_listings.id');
            })
            ->where('row_num', '<=', 3)
            ->orderBy('updated_at', 'desc')
            ->paginate(20, ['*'], 'page', $page);
    }

    protected function buildFavoritesQuery(FavoritesListingType $filterType)
    {
        if (!Auth::check()) {
            return Listing::whereRaw('0 = 1'); // Return empty query
        }

        $itemIds = Auth::user()->favorites()->pluck('id');

        $query = Listing::active()->whereIn('item_id', $itemIds);

        if ($filterType !== FavoritesListingType::All) {
            $query->where('type', $filterType->value);
        }

        return $query;
    }
}
