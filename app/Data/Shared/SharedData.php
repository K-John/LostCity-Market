<?php

namespace App\Data\Shared;

use Closure;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

class SharedData extends Data
{
    public function __construct(
        #[TypeScriptType(UserData::class)]
        public ?Closure $user = null,
        public ?NotificationData $notification = null,
        /** @var DataCollection<\App\Data\Banner\BannerData> */
        public ?Closure $globalBanners = null
    ) {
        $this->shareNotification();
    }

    protected function shareNotification(): void
    {
        if (session('notification')) {
            $this->notification = new NotificationData(
                ...session('notification')
            );
        }
    }
}
