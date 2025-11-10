<?php

namespace App\Pages\Admin;

use App\Data\Listing\AdminListingData;
use App\Data\User\AdminUserData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class UserListingsIndexPage extends Data
{
    public function __construct(
        public AdminUserData $selected_user,
        /** @var PaginatedDataCollection<AdminListingData> */
        public PaginatedDataCollection $listings,
        public UserListingFiltersData $filters
    ) {}
}
