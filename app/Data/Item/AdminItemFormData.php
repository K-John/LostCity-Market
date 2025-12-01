<?php

namespace App\Data\Item;

use App\Models\Listing;
use App\Models\Username;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Data;

class AdminItemFormData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $cost,
        public bool $is_active,
        public bool $is_listable
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1'],
            'slug' => ['required', 'string', 'min:1'],
            'cost' => ['required', 'integer', 'min:1'],
            'is_active' => ['bool'],
        ];
    }
}
