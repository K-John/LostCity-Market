<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingFormData;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController
{
    public function index()
    {
        return inertia('listings/index/page');
    }

    public function create() {}

    public function store(ListingFormData $data)
    {
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
