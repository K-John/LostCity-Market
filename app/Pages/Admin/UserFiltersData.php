<?php

namespace App\Pages\Admin;

use Spatie\LaravelData\Data;

class UserFiltersData extends Data
{
    public function __construct(
        public ?string $search,
        public ?bool $is_banned,
        public ?bool $is_admin,
        public ?string $sort,
    ) {}
}
