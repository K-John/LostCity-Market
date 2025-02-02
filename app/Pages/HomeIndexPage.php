<?php

namespace App\Pages;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingFormData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class HomeIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
    ) {}
}