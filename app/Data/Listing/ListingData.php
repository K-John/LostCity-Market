<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use App\Models\Listing;
use DateTime;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class ListingData extends Data
{
    #[Computed]
    public bool $canManage;

    public function __construct(
        public int $id,
        public ListingType $type,
        public int $price,
        public int $quantity,
        public ?string $notes,
        public string $username,
        public ?ItemData $item,
        public DateTime $updatedAt,
    ) {
            $listingToken = Listing::where('id', $this->id)
                ->value('token');
            $this->canManage = $listingToken === session('listing_token');
    }
}