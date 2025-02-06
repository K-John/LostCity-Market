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
        public ?DateTime $deletedAt
    ) {
    }

    public static function fromModel(Listing $listing): self
    {
        $instance = new self(
            $listing->id,
            ListingType::from($listing->type),
            $listing->price,
            $listing->quantity,
            $listing->notes,
            $listing->username,
            $listing->item ? ItemData::from($listing->item) : null,
            $listing->updated_at,
            $listing->deleted_at
        );

        $instance->canManage = $listing->token === session('listing_token');

        return $instance;
    }
}