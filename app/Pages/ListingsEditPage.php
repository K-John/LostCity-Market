<?php

namespace App\Pages;

use App\Data\Listing\ListingData;
use App\Data\Listing\ListingFormData;
use Spatie\LaravelData\Data;

class ListingsEditPage extends Data
{
    public function __construct(
        public ListingData $listing,
        public ListingFormData $listingForm
    ) {}
}