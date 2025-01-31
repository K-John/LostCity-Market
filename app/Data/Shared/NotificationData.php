<?php

namespace App\Data\Shared;

use App\Enums\NotificationType;
use Spatie\LaravelData\Data;

class NotificationData extends Data
{
    public function __construct(
        public NotificationType $type,
        public string $body
    ) {
    }
}
