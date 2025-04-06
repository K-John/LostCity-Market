<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum BannerType: string
{
    case Default = 'default';
    case Success = 'success';
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
}
