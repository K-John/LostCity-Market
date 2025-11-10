<?php

namespace App\Data\Item;

use DateTime;
use Spatie\LaravelData\Data;

class AdminItemData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $cost,
        public bool $isActive
    ) {}
}
