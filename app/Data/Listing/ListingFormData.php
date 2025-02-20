<?php

namespace App\Data\Listing;

use App\Data\Item\ItemData;
use App\Enums\ListingType;
use App\Models\Listing;
use App\Models\Username;
use App\Services\UsernameService;
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
        public ?ItemData $item,
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
                    $value = str_replace(' ', '_', $value);
                    $value = trim($value, ' _');
                    $usernames = UsernameService::getAuthenticatedUsernames();
                    $usernameExists = Username::where('username', $value)->exists();
                    // User is using a username that is not theirs
                    if ($usernameExists && !in_array($value, $usernames)) {
                        $fail('The username is not yours.');
                    }
                    // User is authenticated and using a username that is not theirs
                    if (!empty($usernames) && !in_array($value, $usernames)) {
                        $fail('The username is not yours.');
                    }

                },
            ],
            'notes' => ['nullable', 'string'],
            'item.id' => ['required', 'integer', 'exists:items,id'],
            'type' => [
                'required',
                'string',
                'in:buy,sell',
                function ($attribute, $value, $fail) {
                    $existingListings = Listing::active()->where('type', $value)
                        ->when(Auth::check(), function ($query) {
                            $query->whereIn('username', Auth::user()->usernames->pluck('username')->toArray() ?? []);
                        }, function ($query) {
                            $query->where('token', session('listing_token'));
                        })
                        ->where('id', '!=', request('id'))
                        ->count();
                    if ($existingListings >= 8) {
                        $fail('You cannot have more than eight active listings of the same type.');
                    }
                },
                function ($attribute, $value, $fail) {
                    $existingListing = Listing::active()->where('type', $value)
                        ->when(Auth::check(), function ($query) {
                            $query->whereIn('username', Auth::user()->usernames->pluck('username')->toArray() ?? []);
                        }, function ($query) {
                            $query->where('token', session('listing_token'));
                        })
                        ->where('item_id', request('item.id'))
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
