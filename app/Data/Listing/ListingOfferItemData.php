<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Data\Item\ItemFormData;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class ListingOfferItemData extends Data
{
    public function __construct(
        public ?int $id,
        public ?int $listingOfferId,
        public int $quantity,
        public ?int $item_id,
        public ItemFormData $item,
    ) {}
}

