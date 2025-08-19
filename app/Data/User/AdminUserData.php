<?php

namespace App\Data\User;

use DateTime;
use Spatie\LaravelData\Data;

class AdminUserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $discordId,
        public DateTime $createdAt,
        public bool $isAdmin,
        public bool $isBanned,
        public ?DateTime $bannedAt
    ) {
    }
}
