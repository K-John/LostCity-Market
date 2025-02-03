<?php

namespace App\Http\Traits;

use App\Enums\ListingType;
use Illuminate\Http\Request;

trait HandlesListingType
{
    public function getListingType(Request $request): ListingType
    {
        // If a new `type` is passed in the request, store it in session
        if ($request->has('type') && $type = ListingType::tryFrom($request->query('type'))) {
            session(['listing_type' => $type->value]); // Store in session
        }

        // Return from session if exists, otherwise use default
        return ListingType::tryFrom(session('listing_type')) ?? ListingType::Buy;
    }
}