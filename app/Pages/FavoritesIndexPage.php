<?php

namespace App\Pages;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class FavoritesIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<\App\Data\Item\ItemData> */
        public PaginatedDataCollection $items,
    ) {}
}