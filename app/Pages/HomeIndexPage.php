<?php

namespace App\Pages;

use App\Enums\FavoritesListingType;
use App\Enums\HomeTabType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class HomeIndexPage extends Data
{
    public function __construct(
        public HomeTabType $tab,
        public FavoritesListingType $listingType,
        /** @var PaginatedDataCollection<\App\Data\Listing\ListingData> */
        public PaginatedDataCollection $listings,
        /** @var DataCollection<\App\Data\Item\ItemData> */
        public ?DataCollection $favorites = null,
    ) {}
}