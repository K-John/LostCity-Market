<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum BannerDisplayScope: string
{
    case Global = 'global';
    case Item = 'item';
    case User = 'user';
}
