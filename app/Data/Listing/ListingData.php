<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
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
        public ?DateTime $soldAt,
        public ?DateTime $deletedAt,
        public ?DateTime $pausedAt,
        #[Hidden]
        public ?int $userId
    ) {
        $this->canManage = self::determineCanManage($userId);
    }

    private static function determineCanManage(?int $userId): bool
    {
        return Auth::check() && (Auth::id() === $userId || Auth::user()->is_admin);
    }
}
