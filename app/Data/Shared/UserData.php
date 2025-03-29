<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public bool $is_admin,
        /** @var DataCollection<\App\Data\Shared\UsernameData> */
        public ?DataCollection $usernames,
    ) {
    }
}
