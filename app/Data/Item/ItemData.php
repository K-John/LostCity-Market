<?php

namespace App\Data\Item;

use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ItemData extends Data
{
    #[Computed]
    public bool $isFavorite;

    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $cost,
        /** @var DataCollection<\App\Data\Banner\BannerData> */
        public ?DataCollection $banners,
    ) {
        $this->isFavorite = self::determineIsFavorite($id);
    }

    private static function determineIsFavorite(int $id): bool
    {
        return Auth::check() && Auth::user()->favorites()->pluck('id')->contains($id);
    }
}
