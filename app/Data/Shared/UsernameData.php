<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;

class UsernameData extends Data
{
    public function __construct(
        public string $username,
    ) {
    }
}
