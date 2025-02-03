<?php

namespace App\Pages;

use App\Enums\ListingType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class HomeIndexPage extends Data
{
    public function __construct(
        public ListingType $listingType,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
    ) {}
}