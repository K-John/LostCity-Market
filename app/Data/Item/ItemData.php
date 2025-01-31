<?php

namespace App\Data\Item;

use Spatie\LaravelData\Data;

class ItemData extends Data
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}
}