<?php

namespace App\Pages;

use App\Data\Token\TokenFormData;
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
        public string $token,
        public TokenFormData $tokenForm,
        public array $usernames
    ) {}
}