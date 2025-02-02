<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use DateTime;
use Spatie\LaravelData\Data;

class ListingData extends Data
{
    public function __construct(
        public int $id,
        public ListingType $type,
        public int $price,
        public int $quantity,
        public ?string $notes,
        public string $username,
        public ?ItemData $item,
        public DateTime $createdAt,
    ) {
    }
}