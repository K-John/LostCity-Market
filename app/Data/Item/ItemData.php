<?php

namespace App\Data\Item;

use Spatie\LaravelData\Data;

class ItemData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {}
}