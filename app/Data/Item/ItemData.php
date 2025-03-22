<?php

namespace App\Data\Item;

use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class ItemData extends Data
{
    #[Computed]
    public bool $isFavorite;

    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $cost
    ) {
        $this->isFavorite = self::determineIsFavorite($id);
    }

    private static function determineIsFavorite(int $id): bool
    {
        return Auth::check() && (Auth::user()->favorites->contains($id));
    }
}