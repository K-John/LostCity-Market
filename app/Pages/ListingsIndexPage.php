<?php

namespace App\Pages;

use App\Data\Token\TokenFormData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class ListingsIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
        public string $token,
        public TokenFormData $tokenForm,
        public array $usernames
    ) {}
}