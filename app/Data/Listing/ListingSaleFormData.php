<?php

namespace App\Data\Listing;

use App\Models\Listing;
use Illuminate\Contracts\Validation\Validator;
use Spatie\LaravelData\Data;

class ListingSaleFormData extends Data
{
    public function __construct(
        public int $id,
        public string $price,
        public int $quantity,
        public ?string $redirect = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id' => ['required', 'exists:listings,id'],
            'price' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $id = $validator->getData()['id'] ?? null;
            $quantity = $validator->getData()['quantity'] ?? [];

            if ($validator->errors()->any()) {
                return;
            }

            // Ensure quantity is not greater than the available quantity in the listing
            $listing = Listing::find($id);
            if ($listing && $quantity > $listing->quantity) {
                $validator->errors()->add('quantity', 'Quantity cannot be greater than the available quantity in the listing.');
            }
        });
    }
}
