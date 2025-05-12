<?php

namespace App\Pages;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ListingsIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
        /** @var DataCollection<\App\Data\Listing\ListingData> */
        public DataCollection $expiredListings,
        /** @var DataCollection<\App\Data\Listing\ListingData> */
        public DataCollection $pausedListings,
        public array $usernames
    ) {}
}