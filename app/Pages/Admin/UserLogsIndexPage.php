<?php

namespace App\Pages\Admin;

use App\Data\ActivityLog\ActivityLogData;
use App\Data\Listing\AdminListingData;
use App\Data\User\AdminUserData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class UserLogsIndexPage extends Data
{
    public function __construct(
        public AdminUserData $selected_user,
        /** @var PaginatedDataCollection<ActivityLogData> */
        public PaginatedDataCollection $logs,
        public UserFiltersData $filters
    ) {}
}
