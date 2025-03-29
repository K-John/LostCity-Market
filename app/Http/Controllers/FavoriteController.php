<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Models\Item;
use App\Pages\FavoritesIndexPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\PaginatedDataCollection;

class FavoriteController
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->latest()->paginate(20);

        return inertia('favorites/index/page', new FavoritesIndexPage(
            items: ItemData::collect($favorites, PaginatedDataCollection::class)
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $item = Item::findOrFail($request->item_id);

        $request->user()->favorites()->syncWithoutDetaching([$item->id]);

        // Clear the cache for the user's favorites
        Cache::tags('user_favorites')->forget("user_" . request()->user()->id . "_favorites");

        return back()->success("{$item->name} has been added to your favorites.");
    }

    public function destroy(Item $favorite)
    {
        request()->user()->favorites()->detach($favorite->id);

        // Clear the cache for the user's favorites
        Cache::tags('user_favorites')->forget("user_" . request()->user()->id . "_favorites");

        return back()->success("{$favorite->name} has been removed from your favorites.");
    }
}
