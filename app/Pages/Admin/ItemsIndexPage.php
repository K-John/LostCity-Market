<?php

namespace App\Pages\Admin;

use App\Data\Item\AdminItemData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class ItemsIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<AdminItemData> */
        public PaginatedDataCollection $items,
        public ItemFiltersData $filters
    ) {}
}
