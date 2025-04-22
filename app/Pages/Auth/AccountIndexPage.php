<?php

namespace App\Pages\Auth;

use DateTime;
use Spatie\LaravelData\Data;

class AccountIndexPage extends Data
{
    public function __construct(
        public string $name,
        public DateTime $register_date,
        public int $listing_count,
        public array $usernames
    ) {}
}