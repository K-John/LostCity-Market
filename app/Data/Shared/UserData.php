<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email
    ) {
    }
}
