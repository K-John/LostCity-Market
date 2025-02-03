<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use Spatie\LaravelData\Data;

class ListingFormData extends Data
{
    public function __construct(
        public ?int $id,
        public ListingType $type,
        public string $price,
        public ?int $quantity,
        public ?string $notes,
        public string $username,
        public ItemData $item,
    ) {
    }

    public static function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:buy,sell'],
            'price' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1'],
            'username' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'item.id' => ['required', 'integer', 'exists:items,id'],
        ];
    }

    public function getListingData(): array
    {
        return $this->except('item', 'id')->toArray();
    }
}