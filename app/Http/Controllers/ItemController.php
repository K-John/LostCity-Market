<?php

namespace App\Http\Controllers;

use App\Data\Item\ItemData;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        if (!$search) {
            return response()->json([]);
        }

        $items = Item::where('name', 'LIKE', "%{$search}%")
            ->take(5)
            ->get();

        return response()->json(ItemData::collect($items));
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Item $item) {}

    public function edit(Item $item) {}

    public function update(Request $request, Item $item) {}

    public function destroy(Item $item) {}
}
