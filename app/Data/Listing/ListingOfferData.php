<?php

namespace App\Data\Listing;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ListingOfferData extends Data
{
    public function __construct(
        public ?int $id,
        public ?int $listingId,
        public string $title,
        /** @var DataCollection<ListingOfferItemData> */
        public DataCollection $items,
    ) {}
}
