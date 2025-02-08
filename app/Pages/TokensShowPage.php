<?php

namespace App\Pages;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class TokensShowPage extends Data
{
    public function __construct(
        public string $token,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
    ) {}
}