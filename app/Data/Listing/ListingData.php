<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use App\Models\Listing;
use DateTime;
use Illuminate\Support\Facades\Auth;
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
        public string $tokenPrefix,
        public DateTime $updatedAt,
        public ?DateTime $soldAt,
        public ?DateTime $deletedAt,
        public ?int $userId
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
            substr($listing->token, 0, 4),
            $listing->updated_at,
            $listing->sold_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $listing->sold_at) : null,
            $listing->deleted_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $listing->deleted_at) : null,
            $listing->user_id
        );

        $listingUsername = strtolower($listing->username);
        $listingUsername = str_replace(' ', '_', $listingUsername);
        $listingUsername = trim($listingUsername, ' _');

        $instance->canManage = $listing->token === session('listing_token') || (Auth::user() && (Auth::user()->id === $listing->user_id || in_array($listingUsername, Auth::user()->usernames->pluck('username')->toArray())));

        return $instance;
    }
}