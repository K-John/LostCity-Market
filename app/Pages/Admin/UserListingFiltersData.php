<?php

namespace App\Pages\Admin;

use Spatie\LaravelData\Data;

class UserListingFiltersData extends Data
{
    public function __construct(
        public ?string $item,
        public ?string $sort,
    ) {}
}
