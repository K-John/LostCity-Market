<?php

namespace App\Pages\Items;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingFormData;
use Spatie\LaravelData\Data;

class ItemsShowPage extends Data
{
    public function __construct(
        public ItemData $item,
        public ListingFormData $listingForm
    ) {}
}