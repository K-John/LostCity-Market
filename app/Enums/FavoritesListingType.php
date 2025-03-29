<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum FavoritesListingType: string
{
    case All = 'all';
    case Buy = 'buy';
    case Sell = 'sell';
}
