<?php

namespace App\Pages\Admin;

use App\Data\Item\AdminItemData;
use App\Data\Item\AdminItemFormData;
use Spatie\LaravelData\Data;

class ItemsEditPage extends Data
{
    public function __construct(
        public AdminItemData $item,
        public AdminItemFormData $itemForm
    ) {}
}
