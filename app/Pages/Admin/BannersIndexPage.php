<?php

namespace App\Pages\Admin;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class BannersIndexPage extends Data
{
    public function __construct(
        /** @var PaginatedDataCollection<\App\Data\Banner\BannerData> */
        public PaginatedDataCollection $banners,
    ) {}
}