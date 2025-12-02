<?php

namespace App\Data\Item;

use Spatie\LaravelData\Data;

class ItemFormData extends Data
{
    public function __construct(
        public int $id,
        public int $game_id,
        public string $name,
        public string $slug,
        public int $cost
    ) {}
}
