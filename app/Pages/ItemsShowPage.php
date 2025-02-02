<?php

namespace App\Pages;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingFormData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class ItemsShowPage extends Data
{
    public function __construct(
        public ItemData $item,
        public ListingFormData $listingForm,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
    ) {}
}