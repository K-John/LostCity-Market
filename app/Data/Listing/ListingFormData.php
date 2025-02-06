<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use App\Models\Listing;
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
        public ?ItemData $item,
    ) {
    }

    public static function rules(): array
    {
        return [
            'price' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1'],
            'username' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'item.id' => ['required', 'integer', 'exists:items,id'],
            'type' => [
                'required',
                'string',
                'in:buy,sell',
                function ($attribute, $value, $fail) {
                    $existingListing = Listing::where('type', $value)
                        ->whereNull('deleted_at')
                        ->where('item_id', request('item.id'))
                        ->where('token', session('listing_token'))
                        ->where('id', '!=', request('id'))
                        ->where('updated_at', '>=', now()->subDays(2))
                        ->first();
                    if ($existingListing) {
                        $fail('You cannot have multiple listings of the same type. Please update the existing listing instead.');
                    }
                },
            ],
        ];
    }

    public function getListingData(): array
    {
        return $this->except('item', 'id')->toArray();
    }
}