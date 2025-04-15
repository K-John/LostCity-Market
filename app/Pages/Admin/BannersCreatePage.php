<?php

namespace App\Pages\Admin;

use App\Data\Banner\BannerFormData;
use Spatie\LaravelData\Data;

class BannersCreatePage extends Data
{
    public function __construct(
        public BannerFormData $bannerForm,
    ) {}
}