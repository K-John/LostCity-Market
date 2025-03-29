<?php

namespace App\Pages;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingFormData;
use App\Enums\ListingType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ItemsShowPage extends Data
{
    public function __construct(
        public ListingType $listingType,
        public ItemData $item,
        public ListingFormData $listingForm,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
        /** @var DataCollection<\App\Data\Listing\ListingData> */
        public DataCollection $soldListings,
        public array $usernames
    ) {}
}