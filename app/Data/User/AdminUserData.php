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
        public ?DateTime $bannedAt,
        /** @var string[] */
        public array $usernames = []
    ) {}

    public static function fromModel(\App\Models\User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            discordId: $user->discord_id,
            createdAt: $user->created_at,
            isAdmin: $user->is_admin,
            isBanned: $user->is_banned,
            bannedAt: $user->banned_at,
            usernames: $user->usernames->pluck('username')->toArray()
        );
    }
}
