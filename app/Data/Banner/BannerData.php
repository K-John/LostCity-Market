<?php

namespace App\Data\Banner;

use App\Enums\BannerType;
use DateTime;
use Spatie\LaravelData\Data;

class BannerData extends Data
{
    public function __construct(
        public int $id,
        public BannerType $type,
        public string $message,
        public bool $global,
        public ?DateTime $startAt,
        public ?DateTime $endAt,
    ) {
    }
}
