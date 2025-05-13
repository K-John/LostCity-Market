<?php

namespace App\Data\Referral;

use DateTime;
use Spatie\LaravelData\Data;

class ReferralData extends Data
{
    public function __construct(
        public int $id,
        public string $code,
        public DateTime $createdAt,
    ) {
    }
}
