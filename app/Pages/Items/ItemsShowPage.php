<?php

namespace App\Pages\Items;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingFormData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ItemsShowPage extends Data
{
    public function __construct(
        public ItemData $item,
        public ListingFormData $listingForm,
        /* @var DataCollection<ListingData> */
        public DataCollection $listings,
    ) {}
}