<?php

namespace App\Pages;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class UsersIndexPage extends Data
{
    public function __construct(
        public string $username,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
    ) {}
}