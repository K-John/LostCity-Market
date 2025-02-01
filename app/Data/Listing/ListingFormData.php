<?php

namespace App\Data\Listing;

use App\Models\Item;
use App\Enums\ListingType;

class ListingFormData
{
    public function __construct(
        public ?int $id,
        public ListingType $type,
        public string $price,
        public int $quantity,
        public string $notes,
        public int $item_id,
    ) {
    }

    public static function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:buy,sell'],
            'price' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ];
    }
}