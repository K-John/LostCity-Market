<?php

namespace App\Pages;

use App\Data\Listing\ListingData;
use App\Data\Listing\ListingSaleFormData;
use Spatie\LaravelData\Data;

class ListingsSalePage extends Data
{
    public function __construct(
        public ListingData $listing,
        public ListingSaleFormData $listingSaleForm
    ) {}
}