<?php

namespace App\Pages;

use App\Data\Item\ItemData;
use App\Data\Listing\ListingFormData;
use App\Enums\ListingType;
use Spatie\LaravelData\Attributes\AutoLazy;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Lazy;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Support\Lazy\ClosureLazy;

class ItemsShowPage extends Data
{
    public function __construct(
        #[AutoLazy]
        public ListingType $listingType,

        #[AutoLazy]
        public ClosureLazy|ItemData $item,

        #[AutoLazy]
        public ClosureLazy|ListingFormData $listingForm,

        /**
         * @var PaginatedDataCollection<\App\Data\Listing\ListingData>
         */
        public PaginatedDataCollection $listings,

        /**
         * @var DataCollection<\App\Data\Listing\ListingData>
         */
        #[AutoLazy]
        public ClosureLazy|DataCollection $soldListings,

        #[AutoLazy]
        public ClosureLazy|array $usernames,

        /**
         * @var DataCollection<\App\Data\Banner\BannerData>
         */
        #[AutoLazy]
        public ClosureLazy|DataCollection $banners,
    ) {}
}
