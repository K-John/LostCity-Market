<?php

namespace App\Data\Listing;

use App\Models\Listing;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ListingSaleFormData extends Data
{
    public function __construct(
        public int $id,
        public ?string $price,
        public int $quantity,
        /** @var DataCollection<ListingOfferData> */
        public DataCollection $offers,
        public ?string $redirect = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id' => ['required', 'exists:listings,id'],

            // Sale price (you can switch back to 'required' if you always want it)
            'price' => ['nullable', 'integer', 'min:1'],

            'quantity' => ['required', 'integer', 'min:1'],

            // OFFERS: structurally allow 0 or 1.
            // We will require them conditionally in withValidator().
            'offers' => ['nullable', 'array', 'max:1'],

            // Each (single) offer
            'offers.*.id' => ['nullable', 'integer'],
            'offers.*.listingId' => ['nullable', 'integer'],
            'offers.*.title' => ['nullable', 'string', 'max:255'],

            // Items inside the offer: structurally optional
            'offers.*.items' => ['nullable', 'array'],

            'offers.*.items.*.item_id' => [
                'required_with:offers.*.items',
                'integer',
                Rule::exists('items', 'id')->where('is_active', true),
            ],

            'offers.*.items.*.quantity' => [
                'required_with:offers.*.items',
                'integer',
                'min:1',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            'offers.max' => 'A listing can have only one offer when marking it as sold.',
        ];
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();
            $id = $data['id'] ?? null;
            $quantity = $data['quantity'] ?? null;
            $offers = $data['offers'] ?? [];

            // If there are already base rule errors, bail early
            if ($validator->errors()->any()) {
                return;
            }

            /** @var Listing|null $listing */
            $listing = Listing::find($id);

            // 1) Quantity <= available
            if ($listing && $quantity > $listing->quantity) {
                $validator->errors()->add(
                    'quantity',
                    'Quantity cannot be greater than the available quantity in the listing.'
                );
            }

            if (! $listing) {
                return;
            }

            $hasLegacyPrice = ! is_null($listing->price);
            $offersArray = is_array($offers) ? $offers : [];
            $offersCount = count($offersArray);

            // 2) Require one offer when the listing has NO legacy price
            if (! $hasLegacyPrice && $offersCount === 0) {
                $validator->errors()->add(
                    'offers',
                    'You must select what was offered for this listing.'
                );
            }

            // 3) Enforce "at most one" offer explicitly (max:1 already helps, this is clearer error)
            if ($offersCount > 1) {
                $validator->errors()->add(
                    'offers',
                    'Only one offer can be submitted when marking a listing as sold.'
                );
            }

            // 4) If an offer exists, ensure it has at least one item
            if ($offersCount === 1) {
                $firstOffer = $offersArray[0] ?? [];
                $items = $firstOffer['items'] ?? [];

                if (! is_array($items) || count($items) === 0) {
                    $validator->errors()->add(
                        'offers.0.items',
                        'The offer must include at least one item.'
                    );
                }
            }
        });
    }
}
