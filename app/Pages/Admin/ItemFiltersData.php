<?php

namespace App\Pages\Admin;

use Spatie\LaravelData\Data;

class ItemFiltersData extends Data
{
    public function __construct(
        public ?string $search,
        public ?bool $is_active,
        public ?string $sort,
    ) {}
}
