<?php

namespace App\Pages;

use App\Data\Listing\ListingData;
use App\Data\Listing\ListingDeleteFormData;
use Spatie\LaravelData\Data;

class ListingsDeletePage extends Data
{
    public function __construct(
        public ListingData $listing
    ) {}
}