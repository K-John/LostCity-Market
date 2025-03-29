<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum HomeTabType: string
{
    case Buy = 'buy';
    case Sell = 'sell';
    case Favorites = 'favorites';
}
