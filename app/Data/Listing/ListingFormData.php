<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use App\Models\Listing;
use App\Models\Username;
use Illuminate\Support\Facades\Auth;
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
        public ?int $item_id,
        public ?array $usernames = []
    ) {}

    public static function rules(): array
    {
        return [
            'price' => ['required', 'integer', 'min:1'],
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
                    
                    if (!in_array($value, Auth::user()->usernames->pluck('username')->toArray())) {
                        $fail('The selected username is invalid.');
                    }
                },
                function ($attribute, $value, $fail) {
                    $user = Username::where('username', $value)->first()?->user;

                    if ($user && $user->is_banned) {
                        $fail('You\'re banned.');
                    }
                }
            ],
            'notes' => ['nullable', 'string'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
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

    public function getListingData(): array
    {
        return $this->except('item', 'id', 'usernames')->toArray();
    }
}
