<?php

namespace App\Pages\Admin;

use App\Data\User\AdminUserData;
use Spatie\LaravelData\Data;

class UsersShowPage extends Data
{
    public function __construct(
        public AdminUserData $selected_user,
        public int $listing_count,
        public array $usernames,
    ) {}
}
