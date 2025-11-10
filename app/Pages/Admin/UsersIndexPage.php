<?php

namespace App\Pages\Admin;

use App\Data\User\AdminUserData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class UsersIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<AdminUserData> */
        public PaginatedDataCollection $users,
        public UserFiltersData $filters
    ) {}
}
