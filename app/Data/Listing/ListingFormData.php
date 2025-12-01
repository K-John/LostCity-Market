<?php

namespace App\Data\Listing;

use App\Enums\ListingType;
use App\Models\Listing;
use App\Models\Username;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ListingFormData extends Data
{
    public function __construct(
        public ?int $id,
        public ListingType $type,
        public ?int $quantity,
        public ?string $notes,
        public string $username,
        public ?int $item_id,
        /** @var DataCollection<ListingOfferData> */
        public DataCollection $offers,
        public ?array $usernames = []
    ) {}

    public static function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
            'username' => [
                'required',
                'string',
                'max:12',
                'regex:/^(?! )[A-Za-z0-9 _]+(?<! )$/',
                function ($attribute, $value, $fail) {
                    if (Auth::user()->is_admin) {
                        return;
                    }

                    if (! in_array($value, Auth::user()->usernames->pluck('username')->toArray())) {
                        $fail('The selected username is invalid.');
                    }
                },
                function ($attribute, $value, $fail) {
                    $user = Username::where('username', $value)->first()?->user;

                    if ($user && $user->is_banned) {
                        $fail('You\'re banned.');
                    }
                },
            ],
            'notes' => ['nullable', 'string'],
            'item_id' => ['required', 'integer', Rule::exists('items', 'id')->where('is_active', true)],
            'offers' => ['required', 'array', 'min:1', 'max:3'],

            // Each offer
            'offers.*.id' => ['nullable', 'integer'],
            'offers.*.listingId' => ['nullable', 'integer'],

            // The items array inside each offer
            'offers.*.items' => [
                'required',
                'array',
                'min:1',
                'max:28',
                function ($attribute, $value, $fail) {
                    // $value is the items array for one offer
                    $ids = array_column($value, 'item_id');

                    if (count($ids) !== count(array_unique($ids))) {
                        $fail('Each offer cannot contain duplicate items.');
                    }
                },
            ],

            // Each item inside the items array
            'offers.*.items.*.item_id' => [
                'required',
                'integer',
                Rule::exists('items', 'id')->where('is_active', true),
            ],

            'offers.*.items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'type' => [
                'required',
                'string',
                'in:buy,sell',
                function ($attribute, $value, $fail) {
                    if (Auth::user()->is_admin) {
                        return;
                    }

                    $existingListings = Listing::whereNull('sold_at')
                        ->where('updated_at', '>=', now()->subDays(1))
                        ->where('user_id', Auth::id())
                        ->where('type', $value)
                        ->where('id', '!=', request('id'))
                        ->count();

                    if ($existingListings >= 8) {
                        $fail('You cannot have more than eight listings of the same type.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (Auth::user()->is_admin) {
                        return;
                    }

                    $existingListing = Listing::whereNull('sold_at')
                        ->where('updated_at', '>=', now()->subDays(1))
                        ->where('type', $value)
                        ->where('user_id', Auth::id())
                        ->where('item_id', request('item_id'))
                        ->where('id', '!=', request('id'))
                        ->first();

                    if ($existingListing) {
                        $fail('You cannot have multiple listings of the same item and type. Please update the existing listing instead.');
                    }
                },
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            // Top-level offers
            'offers.required' => 'Please add at least one offer.',
            'offers.array' => 'Offers must be sent as a valid list.',
            'offers.min' => 'Please add at least one offer.',
            'offers.max' => 'You cannot add more than three offers.',

            // Items collection on each offer
            'offers.*.items.required' => 'Each offer must include at least one item.',
            'offers.*.items.array' => 'Items on an offer must be sent as a valid list.',
            'offers.*.items.min' => 'Each offer must include at least one item.',

            // Individual item fields
            'offers.*.items.*.item_id.required' => 'Please select an item for each offer.',
            'offers.*.items.*.item_id.integer' => 'One of the selected items is invalid.',
            'offers.*.items.*.item_id.exists' => 'One of the selected items is not available.',

            'offers.*.items.*.quantity.required' => 'Please enter a quantity for each item.',
            'offers.*.items.*.quantity.integer' => 'Item quantity must be a whole number.',
            'offers.*.items.*.quantity.min' => 'Item quantity must be at least 1.',
        ];
    }

    public static function attributes(): array
    {
        return [
            'offers' => 'offers',
            'offers.*' => 'offer',
            'offers.*.items' => 'offer items',
            'offers.*.items.*.item_id' => 'item',
            'offers.*.items.*.quantity' => 'item quantity',
        ];
    }

    public function getListingData(): array
    {
        return $this->except('item', 'id', 'usernames', 'offers')->toArray();
    }
}
