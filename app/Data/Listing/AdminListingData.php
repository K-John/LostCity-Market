<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use DateTime;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class AdminListingData extends Data
{
    #[Computed]
    public string $status;

    public function __construct(
        public int $id,
        public ListingType $type,
        public int $price,
        public int $quantity,
        public ?string $notes,
        public ?int $userId,
        public string $username,
        public ?ItemData $item,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $soldAt,
        public ?DateTime $deletedAt,
        public ?DateTime $pausedAt,
    ) {
        $this->status = self::determineStatus($updatedAt, $soldAt, $deletedAt, $pausedAt);
    }

    private static function determineStatus(DateTime $updatedAt, ?DateTime $soldAt, ?DateTime $deletedAt, ?DateTime $pausedAt): string
    {
        if ($soldAt !== null) {
            return 'Sold';
        }

        if ($deletedAt !== null) {
            return 'Deleted';
        }

        if ($updatedAt < (new DateTime)->modify('-7 days')) {
            return 'Expired';
        }

        if ($pausedAt !== null) {
            return 'Paused';
        }

        if ($updatedAt > (new DateTime)->modify('-1 day')) {
            return 'Active';
        }

        return 'Expiring';
    }
}
