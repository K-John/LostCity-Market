<?php

namespace App\Pages;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class ReferralsIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<\App\Data\Referral\ReferralData> */
        public PaginatedDataCollection $referrals,
    ) {}
}