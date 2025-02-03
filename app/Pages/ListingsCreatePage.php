<?php

namespace App\Pages;

use App\Data\Listing\ListingFormData;
use Spatie\LaravelData\Data;

class ListingsCreatePage extends Data
{
    public function __construct(
        public ListingFormData $listingForm,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
    ) {}
}